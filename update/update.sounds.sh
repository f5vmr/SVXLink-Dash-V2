echo "###-START-###"

echo "--- sounds download ---"
tagname=$(curl -sl https://api.github.com/repos/FM-POLAND/sounds/releases/latest | jq -r .tag_name)
#name=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .name)
#published=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .published_at)
#body=$(curl -sl https://api.github.com/repos/sm0svx/svxlink/releases/latest | jq -r .body)
zipball=$(curl -sl https://api.github.com/repos/FM-POLAND/sounds/releases/latest | jq -r .zipball_url)

cd /opt
rm src -R
mkdir src
cd src
echo "--- sounds download --"
wget $zipball
unzip *
mv FM-POLAND-sounds* sounds
echo "--- sounds backup ---"
cp -R /usr/share/svxlink/sounds /usr/share/svxlink/sounds.$(date +"%Y%m%dT%H%M%s")

echo "--- sounds migration ---"
rm -R /usr/share/svxlink/sounds
mv /opt/src/sounds/usr/share/svxlink/sounds /usr/share/svxlink/sounds
#rm -R html
#mv hs_dashboard_pi-main html
chown svxlink -R /usr/share/svxlink



echo "--- sounds version update & cleanup ---"
#cd /opt


echo $tagname > /opt/version.sounds 
rm -R /opt/src
echo "--- SVXlink service restart"
sudo service svxlink restart

echo "###-FINISH-####"
