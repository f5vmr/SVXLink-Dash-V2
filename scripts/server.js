const WebSocket = require('ws');
const { spawn } = require('child_process');

const wsPort = 8001; // WebSocket port for dashboard

// Start arecord
function startRecording() {
  console.log('Starting audio capture...');
  const record = spawn('arecord', [
    '-D', 'plughw:Loopback,1,0',
    '-f', 'S16_LE',
    '-r', '48000',
    '-c', '1'
  ], {
    stdio: ['ignore', 'pipe', 'ignore']
  });

  record.on('exit', (code, signal) => {
    console.warn(`arecord exited (code ${code}, signal ${signal}). Restarting...`);
    setTimeout(startRecording, 1000);
  });

  return record;
}

let record = startRecording();

// WebSocket server
const wss = new WebSocket.Server({ port: wsPort });
let listenerCount = 0;

wss.on('connection', (ws) => {
    console.log('Dashboard connected');

    // Send listener count to all clients
    function broadcastListenerCount() {
        const count = wss.clients.size;
        const msg = JSON.stringify({ type: 'listenerCount', count });
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) client.send(msg);
            
        });
    }

    // Immediately send updated count on new connection
    broadcastListenerCount();


    ws.on('close', () => {
        console.log('Dashboard disconnected');
        broadcastListenerCount();
    });
});


// Broadcast listener count to all connected clients
function broadcastListenerCount() {
  const countMessage = JSON.stringify({ type: 'listenerCount', count: listenerCount });
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) client.send(countMessage);
  });
}

wss.on('listening', () => {
  console.log(`WebSocket server listening on ws://0.0.0.0:${wsPort}/`);
});

process.on('exit', () => record.kill());
process.on('SIGINT', () => process.exit());
//end of script