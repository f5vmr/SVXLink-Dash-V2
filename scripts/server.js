const WebSocket = require('ws');
const wsPort = 8001;

const wss = new WebSocket.Server({ port: wsPort });

console.log(`WebSocket server running on port ${wsPort}`);

// Track clients that are actively listening
wss.on('connection', (ws) => {
    console.log('Dashboard connected');

    // Default: not listening
    ws.isListening = false;

    // Handle messages from client
    ws.on('message', (msg) => {
        try {
            const data = JSON.parse(msg);
            if (data.type === 'startListening') {
                ws.isListening = true;
            } else if (data.type === 'stopListening') {
                ws.isListening = false;
            }
        } catch (e) {
            console.error('Invalid message', e);
        }
        broadcastListenerCount();
    });

    ws.on('close', () => {
        console.log('Dashboard disconnected');
        broadcastListenerCount();
    });

    // Send updated listener count to all clients
    function broadcastListenerCount() {
        const count = [...wss.clients].filter(c => c.isListening).length;
        const msg = JSON.stringify({ type: 'listenerCount', count });
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) client.send(msg);
        });
    }

    // Optionally send initial count
    broadcastListenerCount();

    // Audio streaming logic (same as before)
    const audioHandler = (chunk) => {
        if (ws.readyState === WebSocket.OPEN && ws.isListening) {
            ws.send(chunk);
        }
    };
    // Replace `record.stdout` with your actual audio source
    record.stdout.on('data', audioHandler);

    ws.on('close', () => {
        record.stdout.off('data', audioHandler);
    });
});
