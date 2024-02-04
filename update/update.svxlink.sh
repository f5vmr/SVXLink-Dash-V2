echo "###-START-###"
echo "Fm branch"
echo "--- prepare by apt ---"

apt install -y mc g++ cmake make libsigc++-2.0-dev libgsm1-dev libpopt-dev tcl tcl-dev 
apt install -y libgcrypt20-dev libspeex-dev libasound2-dev libopus-dev librtlsdr-dev 
apt install -y doxygen groff alsa-utils vorbis-tools curl libcurl4-openssl-dev libvorbis-dev
apt install -y git bc curl rtl-sdr libcurl4-openssl-dev cmake libjsoncpp-dev
apt install -y libgpiod2 libgpiod-dev

echo "--- user & group manipulation ---"
# this steps can be skipped
addgroup gpio
useradd -rG audio,daemon,dialout,gpio svxlink

echo "--- svxlink tag download ---"
tagname=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .tag_name)
#name=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .name)
#published=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .published_at)
#body=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .body)
#zipball=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .zipball_url)

cd /opt
rm -R src
mkdir src
cd src
#wget $zipball
#unzip *
#mv sm0svx-svxlink* svxlink
echo "--- svxlink compilation ---"
#cd /opt


#mkdir src
#cd src
git clone http://github.com/sm0svx/svxlink.git
mkdir svxlink/src/build
cd svxlink/src/build
sudo cmake -DUSE_QT=OFF -DCMAKE_INSTALL_PREFIX=/usr -DSYSCONF_INSTALL_DIR=/etc \ -DLOCAL_STATE_DIR=/var -DWITH_SYSTEMD=ON  ..

sudo make
sudo make install
sudo ldconfig

echo $tagname > /opt/version.svxlink 
#cd /opt
#rm -R /opt/src
sudo systemctl svxlink restart

echo "###-FINISH-####"
