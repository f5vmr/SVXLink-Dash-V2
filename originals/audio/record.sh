#!/bin/bash
# Remove old files
rm -f /var/www/html/audio/*.wav

# Record 15 seconds from rx_monitor
file="/var/www/html/audio/audio-$(date +%Y-%m-%d-%H-%M-%S).wav"
arecord -D rx_monitor -V mono -r 48000 -f S16_LE -c1 -d 15 "$file"

# Update live.wav symlink
ln -sf "$file" /var/www/html/audio/live.wav

sleep 2
