#!/bin/bash
IP=$(hostname -I)
IPF=$(echo $IP | sed 's/ /\";CW::play \"\/\";spellWord \"/g')
#echo "final: spellWord \""$IPF"\";"
#sudo echo "set ipaddr0 \""$IPF"\";" > /tmp/ipaddr0.tcl
sudo echo "spellWord \""$IPF"\";playSilence 100;CW::play \"SK\" ;" >/tmp/ipaddr1.tcl
