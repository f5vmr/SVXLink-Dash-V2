const http = require('http');
const fs = require('fs');
const path = require('path');
const os = require('os');
const WebSocket = require('ws');
const { spawn } = require('child_process');

const hostname = '0.0.0.0'; // Listen on all network interfaces
const port = 8000;           // Default port for HTTP
const audioPort = 8001;      // WebSocket port for audio stream

// Function to serve static files
function serveStaticFile(res, filePath, contentType) {
  fs.readFile(filePath, (err, data) => {
    if (err) {
      res.statusCode = 500;
      res.end(`Error reading file: ${err}`);
      return;
    }
    res.statusCode = 200;
    res.setHeader('Content-Type', contentType);
    res.end(data);
  });
}

// Create the HTTP server
const server = http.createServer((req, res) => {
  console.log(`Request for ${req.url}`);

  // Serve the dashboard page
  if (req.url === '/' || req.url === '/index.html') {
    serveStaticFile(res, path.join(__dirname, 'index.html'), 'text/html');
  }
  // Serve other static files (CSS, JS, images)
  else if (req.url.match(/\.(css|js|png|jpg|gif)$/)) {
    const extname = path.extname(req.url);
    let contentType = 'text/plain';
    switch (extname) {
      case '.css': contentType = 'text/css'; break;
      case '.js': contentType = 'application/javascript'; break;
      case '.png': contentType = 'image/png'; break;
      case '.jpg': contentType = 'image/jpeg'; break;
      case '.gif': contentType = 'image/gif'; break;
    }
    serveStaticFile(res, path.join(__dirname, req.url), contentType);
  }
  else {
    res.statusCode = 404;
    res.end('Not Found');
  }
});

// Start the HTTP server
server.listen(port, hostname, () => {
  console.log(`HTTP Server running at http://${hostname}:${port}/`);
});

// -----------------------------
// WebSocket server for audio streaming
// -----------------------------
const wss = new WebSocket.Server({ port: audioPort }, () => {
  console.log(`Audio WebSocket server running at ws://${hostname}:${audioPort}/`);
});

// Spawn arecord to capture audio from Loopback,0,0
const arecord = spawn('arecord', ['-D', 'hw:Loopback,0,0', '-f', 'S16_LE', '-r', '44100', '-c', '2']); 
// 16-bit PCM, 44100Hz, stereo

// Broadcast audio chunks to all connected WebSocket clients
arecord.stdout.on('data', (chunk) => {
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(chunk);
    }
  });
});

// Handle errors from arecord
arecord.stderr.on('data', (data) => {
  console.error(`arecord error: ${data}`);
});

arecord.on('close', (code) => {
  console.log(`arecord process exited with code ${code}`);
});

// Optional: handle WebSocket client connections
wss.on('connection', (ws) => {
  console.log('New client connected for audio stream');
  ws.on('close', () => console.log('Client disconnected'));
});
