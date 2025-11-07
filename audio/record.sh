#!/bin/bash
# Recording test audio for level measurement (RX only)
# Always overwrite live.wav

# Remove old live file if it exists
LIVE_FILE="/var/www/html/audio/live.wav"
if [ -f "$LIVE_FILE" ]; then
    rm "$LIVE_FILE"
fi

# Record from RX loopback
arecord -D rx_monitor -V mono -r 48000 -f S16_LE -c1 -d 15 "$LIVE_FILE"

# Optional short pause after recording
sleep 2
