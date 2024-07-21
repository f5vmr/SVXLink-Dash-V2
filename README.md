# SVXLink-Dashboard-V2
<h1>SVXLink Node dashboard repository inspired by a pi-star dashboard</h1>
<h2>Originally constructed by SP2ONG and SP0DZ, but suffered from out of date code in PHP and Javascript.

Brought up to date by Chris Jackson G4NAB with new code. The DTMF section has yet to be modified to work.</h2>

<b>This installation requires that svxlink has been compiled on Debian 12 with PHP 8.2 installed. </b>

<p>If it has been installed with svxlinkbuilder then very little needs to be changed.</p>

<p>If you are installing it manually, then you will need to read the instructions thoroughly:</p>

<p>No installation script is required, simply open a terminal with ssh or putty and cd to /var/www and REMOVE any existing html folder.</p>
<p>If you are upgrading an existing earlier SVXLink-Dash-V2 installation, then you may be still better simply removing the existing html folder. You will lose nothing by it.</p>
<p>So in /var/www/ run the following command line. </p>
<p><b>sudo git clone https://github.com/f5vmr/SVXLink-Dash-V2 html</b></p>
<p><b> cd html</b></p>

<p>If you are installing it for the first time, then you need to follow the next few instructions, otherwise skip to the next section Setting Up The Dashboard.</p>
<p>Next <b>sudo nano /etc/apache2/envvars</b> file and make the following changes</p>
<p>export APACHE_RUN_USER=www-data</p>
<p>export APACHE_RUN_GROUP=www-data</p>
<p>to</p>

<p>export APACHE_RUN_USER=svxlink</p>
<p>export APACHE_RUN_GROUP=svxlink</p>
<p>and save the file</p>
<p>Next locate the file /usr/lib/systemd/system/apache2.service</p>
<p>It may be elsewhere in your system.</p>
<p>You may edit it in situ or copy it to /etc/systemd/system/</p>
<p>Locate 'PrivateTmp=true' and change this to 'PrivateTmp=false' and save the file.</p>
<p>sudo systemctl daemon-reload && sudo systemctl restart apache2 to restart the webserver.</p>
<h2>Setting up the Dashboard</h2>
<p>While still in the <b>/var/www/html</b> folder run the following command:</p>
<p><b>sudo ./upgrade.sh</b></p>
<p>This file allows you, the Dashboard Owner, to run certain commands when authorised. It also adds some maintenance provisions, removing old backup files so that the system remains unburdened of old data.</p>
<p>Finally go to the browser of your choice and enter the ip address of your raspberry pi.</p>
<p>You will be presented with the dashboard of your device. You will need to log in under your username and password, set up during the upgrade.sh process.</p>

<p>The dashboard is now ready to use. However it is recommended that you thoroughly read the man page for svxlink.conf. <i>man svxlink.conf on google </i>will find a copy, although you will find one inside your device through the terminal</p>
<img width="945" alt="Screenshot 2024-06-16 at 16 15 01" src="https://github.com/f5vmr/SVXLink-Dash-V2/assets/8429684/ee286988-5035-4f01-8173-f3f294e77e65">


<p>Your edited files will be saved in /var/www/html/backups with a date/time.</p> 

<p>Only With Username and Password in place, will you have access to the 'Log', 'Power' and 'Edit' menus, allowing you as the sysop to expose the dashboard to public view, without someone corrupting your node. As soon as you click on the blue menus, the authorisation is rescinded. Naturally as this is a web page, all the changes take place within your local browser and not on-line. Without authorisation, the 'Log', 'Power' and Edit menus are blocked, so no one can turn off your repeater, or mess with the logic configuration.</p>
<p>1. Svxlink Configurator - This will only operate with an existing svxlink.conf, you cannot add lines to it with the configurator. If you need to add lines or sections, then you will have to ssh into the device.</p>
<p>In all of the .conf files are lines that are inactive or commented out with '#'. However in the configurator, you will see check-boxes that if ticked, are active lines. If they are unchecked they are inactive and therefore would appear in the file with # as the first character.</p>
<p>2. Amixer configurator removes the need to resort to sudo alsamixer, it can all be done from the dashboard.</p>
<p>3. EchoLink Configurator. Set this up before adding ModuleEchoLink into the SimplexLogic or RepeaterLogic in the Svxlink Editor.</p>
<p>4. Metar Configurator. This is fairly easy to modify, but again look for the man page for ModuleMetarInfo.</p>
<p>5. NodeInfo Configurator. This is the file required for the proper operation of the SvxReflector if it is associated with your device. because it is a .json file, different editing techniques are required.</p>
<p> In each case, the 'save' button action restarts the svxlink service, so there is no need to restart svxlink manually.</p>
<p>However if at any time there appears to be a 'stall' in the operation of your node or repeater, then the POWER menu can be used to restart the service, restart the raspberry completely, or even shutdown the device.</p>
<p>In the case of the amixer configurator, the changes will be immediately applied and viewed without restarting svxlink</p>
<p>Below the footer menu you will see a dtmf control facility. The D buttons are not yet programmed, and will need to correspond with the Macros in svxlink.conf so you will need to self-edit the macros in svxlink.conf.</p>
<p>I will eventually examine the possibility of making improvements and additions in the future.</p>


<p>The svxlink dashboard has some ideas created by G4NAB, SP2ONG, SP0DZ
and upgraded by G4NAB</p>
<h2>Addendum</h2>
<p>Additional Talk Groups can be added to the Svxlink Configurator.</p>
<p>Airports can be added and removed as required in the MetarInfo Configurator.</p
<p>The Audio dashboard seems not to work for the moment.</p>
<p>Module EchoLink can be added throught the dashboard, in the EchoLink configurator first of all, then add ModuleEchoLink to the MODULES= line in the [SimplexLogic] or [RepeaterLogic] section of the Svxlink Configurator.</p>
<p>Amixer can be adjusted using the dashboard, and is more efficient than alsamixer in the terminal.</p>