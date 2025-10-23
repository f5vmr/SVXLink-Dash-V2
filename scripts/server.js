const WebSocket = require('ws');
const { spawn } = require('child_process');

const wsPort = 8001;                 // WebSocket port for dashboard
const alsaDevice = 'plughw:Loopback,1,0'; // Capture device
const sampleRate = 48000;            // Match SVXLink output
const channels = 1;                  

// Start arecord process
const arecord = spawn('arecord', [
  '-D', alsaDevice,
  '-f', 'S16_LE',
  '-r', sampleRate,
  '-c', channels
]);

// Create WebSocket server
const wss = new WebSocket.Server({ port: wsPort });

wss.on('connection', (ws) => {
  console.log('Dashboard connected');

  // Send ALSA audio data to client
  arecord.stdout.on('data', (chunk) => {
    if (ws.readyState === WebSocket.OPEN) {
      ws.send(chunk);
    }
  });

  ws.on('close', () => console.log('Dashboard disconnected'));
});

wss.on('listening', () => {
  console.log(`WebSocket server listening on ws://0.0.0.0:${wsPort}/`);
});

// Handle errors
arecord.stderr.on('data', (data) => {
  console.error(`arecord error: ${data}`);
});

process.on('exit', () => arecord.kill());
process.on('SIGINT', () => process.exit());
