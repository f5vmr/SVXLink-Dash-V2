// server.js
const http = require('http');
const fs = require('fs');
const path = require('path');
const os = require('os');
const { spawn } = require('child_process');
const WebSocket = require('ws');

const hostname = '0.0.0.0';
const httpPort = 8000;
const wsPort = 8001;

// Serve static files (dashboard HTML, JS, etc.)
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

const server = http.createServer((req, res) => {
  console.log(`HTTP request: ${req.url}`);

  if (req.url === '/' || req.url === '/index.html') {
    serveStaticFile(res, path.join(__dirname, 'index.html'), 'text/html');
  } else if (req.url.match(/\.(css|js|png|jpg|gif)$/)) {
    const extname = path.extname(req.url);
    const contentTypes = {
      '.css': 'text/css',
      '.js': 'application/javascript',
      '.png': 'image/png',
      '.jpg': 'image/jpeg',
      '.gif': 'image/gif',
    };
    serveStaticFile(res, path.join(__dirname, req.url), contentTypes[extname] || 'text/plain');
  } else {
    res.statusCode = 404;
    res.end('Not Found');
  }
});

server.listen(httpPort, hostname, () => {
  console.log(`HTTP server running at http://${hostname}:${httpPort}/`);
});

// 🎧 AUDIO STREAM SECTION 🎧

// Spawn arecord to capture PCM audio from Loopback
const arecord = spawn('arecord', [
  '-D', 'hw:Loopback,0,0',
  '-f', 'S16_LE',
  '-r', '44100',
  '-c', '2'
]);

arecord.on('error', (err) => {
  console.error('arecord failed to start:', err);
});

arecord.stderr.on('data', (data) => {
  console.error(`arecord: ${data}`);
});

// WebSocket server for clients (browsers)
const wss = new WebSocket.Server({ port: wsPort }, () => {
  console.log(`WebSocket audio server listening on ws://${hostname}:${wsPort}/`);
});

wss.on('connection', (ws) => {
  console.log('Client connected to audio stream');
  ws.on('close', () => console.log('Client disconnected'));
});

// Pipe audio from arecord to connected clients
arecord.stdout.on('data', (data) => {
  wss.clients.forEach((client) => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(data);
    }
  });
});
