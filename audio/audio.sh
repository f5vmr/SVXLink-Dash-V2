#!/bin/bash
# Recording test audio for level measurement
reset
# remove old files
count=`ls -1 /var/www/html/audio/*.wav 2>/dev/null | wc -l`
if [ $count != 0 ]
then
rm /var/www/html/audio/*.wav
fi
echo ""
echo "Audio recording 10 seconds, to stop recording before 10 seconds use CTRL+C"
echo " "
arecord -D hw:Loopback,1,4 -V mono -r 48000 -f S16_LE -c1 -d 15 /var/www/html/audio/audio-$(date +%Y-%m-%d-%H -%M-%S).wav
echo ""
MYIP=$(hostname -I | awk '{print $1}')
echo " "
echo "You can now listen to your audio on the svxlink dashboard page:"
echo ""
echo "http://$MYIP/audio"
echo ""
echo ""