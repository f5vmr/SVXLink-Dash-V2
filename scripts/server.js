const WebSocket = require('ws');
const { spawn } = require('child_process');

const wsPort = 8001; // WebSocket port for dashboard

// Function to start arecord safely
function startRecording() {
  console.log('Starting audio capture...');
  const record = spawn('arecord', [
    '-D', 'plughw:Loopback,1,0',
    '-f', 'S16_LE',
    '-r', '48000',
    '-c', '1'
  ], {
    stdio: ['ignore', 'pipe', 'ignore'] // stdout only
  });

  record.on('exit', (code, signal) => {
    console.warn(`arecord exited (code ${code}, signal ${signal}). Restarting...`);
    setTimeout(startRecording, 1000);
  });

  return record;
}

// Start arecord
let record = startRecording();

// Create WebSocket server
const wss = new WebSocket.Server({ port: wsPort });

wss.on('connection', (ws) => {
  console.log('Dashboard connected');

  record.stdout.on('data', (chunk) => {
    if (ws.readyState === WebSocket.OPEN) {
      ws.send(chunk);
    }
  });

  ws.on('close', () => console.log('Dashboard disconnected'));
});

wss.on('listening', () => {
  console.log(`WebSocket server listening on ws://0.0.0.0:${wsPort}/`);
});

// Handle clean shutdown
process.on('exit', () => record.kill());
process.on('SIGINT', () => process.exit());
//end of script
