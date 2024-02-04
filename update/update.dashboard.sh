echo "###-START-###"

echo "--- Dashboard Pi download URLs ---"
tagname=$(curl -sl https://api.github.com/repos/FM-POLAND/hs_dashboard_pi/releases/latest | jq -r .tag_name)
zipball=$(curl -sl https://api.github.com/repos/FM-POLAND/hs_dashboard_pi/releases/latest | jq -r .zipball_url)

cd /opt
rm src -R
mkdir src
cd src
echo "--- Dashboard Pi download --"
wget $zipball -O dashboard.zip
unzip dashboard.zip
mv FM-POLAND-hs_dashboard_pi* html

echo "--- Dashboard Pi - backup ---"
cp -R /var/www/html    /var/www/html.$(date +"%Y%m%dT%H%M%s")

echo "--- Dashboard Pi - migration ---"
echo "###-FINISH-####"

#rest of the script will be executed without the log


cp /var/www/html/update/screen.log /opt/screen.log

rm -R /var/www/html
mv /opt/src/html /var/www/html
cp /opt/screen.log /var/www/html/update/screen.log
chown svxlink -R /var/www/html

echo "--- Dashboard Pi version update & cleanup ---"
echo $tagname > /opt/version.dashboard 


#rm -R /opt/src
echo "--- SVXlink service restart"
sudo service svxlink restart

echo "###-FINISH-####"
