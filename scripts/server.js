const WebSocket = require('ws');
const { spawn } = require('child_process');

const wsPort = 8001;

// Start arecord
function startRecording() {
  console.log('Starting audio capture...');
  const record = spawn('arecord', [
    '-D', 'plughw:Loopback,1,0',
    '-f', 'S16_LE',
    '-r', '48000',
    '-c', '1'
  ], { stdio: ['ignore', 'pipe', 'ignore'] });

  record.on('exit', (code, signal) => {
    console.warn(`arecord exited (code ${code}, signal ${signal}). Restarting...`);
    setTimeout(startRecording, 1000);
  });

  return record;
}

let record = startRecording();

// WebSocket server
const wss = new WebSocket.Server({ port: wsPort });

function broadcastListenerCount() {
  const count = [...wss.clients].filter(c => c.readyState === WebSocket.OPEN).length;
  const msg = JSON.stringify({ type: 'listenerCount', count });
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(msg);
    }
  });
}

wss.on('connection', (ws) => {
  console.log('Dashboard connected');
  broadcastListenerCount();

  const audioHandler = (chunk) => {
    if (ws.readyState === WebSocket.OPEN) ws.send(chunk);
  };

  // Send audio stream
  record.stdout.on('data', audioHandler);

  ws.on('close', () => {
    console.log('Dashboard disconnected');
    record.stdout.off('data', audioHandler);
    broadcastListenerCount();
  });
});

wss.on('listening', () => {
  console.log(`WebSocket server listening on ws://0.0.0.0:${wsPort}/`);
});

process.on('exit', () => record.kill());
process.on('SIGINT', () => process.exit());
