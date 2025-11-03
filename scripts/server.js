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

const activeListeners = new Map(); // Track which clients are actively playing

wss.on('connection', (ws) => {
    console.log('Dashboard connected');

    // Handle messages from client
    ws.on('message', (msg) => {
        try {
            const data = JSON.parse(msg);
            if (data.type === 'start') {
                activeListeners.set(ws, true);
                broadcastActiveListeners();
            } else if (data.type === 'stop') {
                activeListeners.delete(ws);
                broadcastActiveListeners();
            }
        } catch (e) {
            console.error('Invalid message from client:', msg);
        }
    });

    // Send audio as before
    const audioHandler = (chunk) => {
        if (ws.readyState === WebSocket.OPEN) ws.send(chunk);
    };
    record.stdout.on('data', audioHandler);

    ws.on('close', () => {
        console.log('Dashboard disconnected');
        activeListeners.delete(ws);
        record.stdout.off('data', audioHandler);
        broadcastActiveListeners();
    });
});

// Broadcast active listener count
function broadcastActiveListeners() {
    const count = activeListeners.size;
    const msg = JSON.stringify({ type: 'listenerCount', count });
    wss.clients.forEach(client => {
        if (client.readyState === WebSocket.OPEN) {
            client.send(msg);
        }
    });
}


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