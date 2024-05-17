#!/bin/bash
# Recording test audio for level measurement
#
# remove old files
count=`ls -1 /var/www/html/audio/*.wav 2>/dev/null | wc -l`
if [ $count != 0 ]
then
rm /var/www/html/audio/*.wav
fi
arecord -D hw:Loopback,1,4 -r 48000 -f S16_LE -c1 -d 15 /var/www/html/audio/audio-$(date +%Y-%m-%d-%H-%M -%S).wav
sleep 2