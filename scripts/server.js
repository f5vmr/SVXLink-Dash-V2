const http = require("http");
const fs = require("fs");
const path = require("path");
const { spawn } = require("child_process");

const hostname = "0.0.0.0";
const port = 8000;

// Serve static files
function serveStaticFile(res, filePath, contentType) {
  fs.readFile(filePath, (err, data) => {
    if (err) {
      res.statusCode = 500;
      res.end(`Error reading file: ${err}`);
      return;
    }
    res.statusCode = 200;
    res.setHeader("Content-Type", contentType);
    res.end(data);
  });
}

// Create the server
const server = http.createServer((req, res) => {
  if (req.url === "/" || req.url === "/index.html") {
    serveStaticFile(res, path.join(__dirname, "index.html"), "text/html");
  } 
  else if (req.url === "/audio") {
    console.log("Client connected for audio stream...");

    res.writeHead(200, {
      "Content-Type": "audio/wav",
      "Transfer-Encoding": "chunked",
      "Connection": "keep-alive"
    });

    // Start arecord from the loopback device
    const arecord = spawn("arecord", [
      "-D", "plughw:Loopback,0,0",
      "-f", "S16_LE",
      "-r", "44100",
      "-c", "1"
    ]);

    arecord.stdout.pipe(res);

    arecord.stderr.on("data", (data) => {
      console.error(`arecord error: ${data}`);
    });

    req.on("close", () => {
      console.log("Client disconnected, stopping arecord");
      arecord.kill("SIGTERM");
    });
  } 
  else if (req.url.match(/\.(css|js|png|jpg|gif)$/)) {
    const extname = path.extname(req.url);
    const mimeTypes = {
      ".css": "text/css",
      ".js": "application/javascript",
      ".png": "image/png",
      ".jpg": "image/jpeg",
      ".gif": "image/gif"
    };
    serveStaticFile(res, path.join(__dirname, req.url), mimeTypes[extname] || "text/plain");
  } 
  else {
    res.statusCode = 404;
    res.end("Not Found");
  }
});

server.listen(port, hostname, () => {
  console.log(`Server running at http://${hostname}:${port}/`);
});
