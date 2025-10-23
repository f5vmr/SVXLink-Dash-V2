const http = require('http');
const fs = require('fs');
const path = require('path');
const { spawn } = require('child_process');

const hostname = '0.0.0.0';
const port = 8000;
const audioPort = 8001; // separate port for streaming audio

// Serve static files (your dashboard)
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

// Web server for dashboard and assets
const webServer = http.createServer((req, res) => {
  console.log(`Request for ${req.url}`);

  if (req.url === '/' || req.url === '/index.html') {
    serveStaticFile(res, path.join(__dirname, 'index.html'), 'text/html');
  } else if (req.url.match(/\.(css|js|png|jpg|gif|ico)$/)) {
    const ext = path.extname(req.url);
    const contentTypes = {
      '.css': 'text/css',
      '.js': 'application/javascript',
      '.png': 'image/png',
      '.jpg': 'image/jpeg',
      '.gif': 'image/gif',
      '.ico': 'image/x-icon'
    };
    serveStaticFile(res, path.join(__dirname, req.url), contentTypes[ext] || 'text/plain');
  } else {
    res.statusCode = 404;
    res.end('Not Found');
  }
});

webServer.listen(port, hostname, () => {
  console.log(`Dashboard running at http://${hostname}:${port}/`);
});

// Audio streaming server
const audioServer = http.createServer((req, res) => {
  if (req.url === '/audio') {
    console.log('Client connected for audio stream');
    res.writeHead(200, {
      'Content-Type': 'audio/wav',
      'Transfer-Encoding': 'chunked',
      'Connection': 'keep-alive'
    });

    // Start arecord process
    const arecord = spawn('arecord', [
      '-D', 'plughw:Loopback,0,0',
      '-f', 'S16_LE',
      '-r', '44100',
      '-c', '1'
    ]);

    arecord.stdout.pipe(res);

    arecord.on('close', (code) => {
      console.log(`arecord closed with code ${code}`);
      res.end();
    });

    req.on('close', () => {
      console.log('Client disconnected, stopping arecord');
      arecord.kill('SIGTERM');
    });
  } else {
    res.writeHead(404);
    res.end('Not Found');
  }
});

audioServer.listen(audioPort, hostname, () => {
  console.log(`Audio stream available at http://${hostname}:${audioPort}/audio`);
});

