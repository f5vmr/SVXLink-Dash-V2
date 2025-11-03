const WebSocket = require('ws');
const { spawn } = require('child_process');

const wsPort = 8001;

// Spawn arecord with stderr suppressed to avoid ALSA noise
const arecord = spawn('arecord', [
  '-D', 'plughw:Loopback,1,0',
  '-f', 'S16_LE',
  '-r', '48000',
  '-c', '1'
], {
  stdio: ['ignore', 'pipe', 'ignore'] // stdin ignored, stdout piped, stderr suppressed
});

// Set of connected clients
const clients = new Set();

// Create WebSocket server
const wss = new WebSocket.Server({ port: wsPort });

wss.on('connection', (ws) => {
  console.log('Dashboard connected');
  clients.add(ws);
  broadcastListenerCount();

  // Send ALSA audio data to client
  const audioListener = (chunk) => {
    if (ws.readyState === WebSocket.OPEN) {
      ws.send(chunk);
    }
  };
  arecord.stdout.on('data', audioListener);

  ws.on('close', () => {
    console.log('Dashboard disconnected');
    clients.delete(ws);
    broadcastListenerCount();
    arecord.stdout.off('data', audioListener);
  });
});

// Broadcast listener count to all connected clients
function broadcastListenerCount() {
  const countMsg = JSON.stringify({ type: 'listeners', count: clients.size });
  clients.forEach((c) => {
    if (c.readyState === WebSocket.OPEN) {
      c.send(countMsg);
    }
  });
}

wss.on('listening', () => {
  console.log(`WebSocket server listening on ws://0.0.0.0:${wsPort}/`);
});

// Handle errors
arecord.stderr.on('data', (data) => {
  // Suppress error if MultiTx isn't transmitting
  if (!data.toString().includes('MultiTx')) {
    console.error(`arecord error: ${data}`);
  }
});

process.on('exit', () => arecord.kill());
process.on('SIGINT', () => process.exit());
