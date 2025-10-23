const http = require('http');
const fs = require('fs');
const path = require('path');
const os = require('os');

const hostname = '0.0.0.0'; // Listen on all network interfaces
const port = 8000; // Default port for HTTP

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

// Create the server
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
      case '.css':
        contentType = 'text/css';
        break;
      case '.js':
        contentType = 'application/javascript';
        break;
      case '.png':
        contentType = 'image/png';
        break;
      case '.jpg':
        contentType = 'image/jpeg';
        break;
      case '.gif':
        contentType = 'image/gif';
        break;
    }

    serveStaticFile(res, path.join(__dirname, req.url), contentType);
  }
  // Handle 404 for other routes
  else {
    res.statusCode = 404;
    res.end('Not Found');
  }
});

// Start the server
server.listen(port, hostname, () => {
  console.log(`Server running at http://${hostname}:${port}/`);
});
