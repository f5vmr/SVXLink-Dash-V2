# SVXLink-Dashboard-V2
SVXLink Node dashboard repository inspired by pi star dashboard
Originally constructed by SP2ONG and SP0DZ, but suffered from out of date code in PHP and Javascript.
Brought up to date by Chris Jackson G4NAB with new code. Thanks to Craig Gardiner Z21LX for assistance in the area of permissions used in the web server.

This installation requires that svxlink has been compiled on Debian 11 (raspberry pi OS bullseye), and php version 8.0 or greater, and Apache 2. Debian 12 will require PHP 8.2. After that follow the instructions below.

For the moment it is still slightly incomplete, a work in progress.


No installation script required, simply cd to /var/www and remove any existing html folder.
<p>
 sudo git clone https://github.com/f5vmr/SVXLink-Dash-V2 html</p>
<p> cd html</p>
<p>However there are some changes required to both the filing system and the web management as follows:</p>
<p>sudo visudo and add the following lines to the bottom of the current file.</p>
<p>svxlink ALL=NOPASSWD: /usr/sbin/service</p>
<p>svxlink ALL=NOPASSWD: /usr/bin/cp</p>
<p>svxlink ALL=NOPASSWD: /usr/bin/chown</p>
<p>svxlink ALL=NOPASSWD: /usr/bin/chmod</p>
<p>svxlink ALL=NOPASSWD: /usr/bin/systemctl</p>
<p>svxlink ALL=NOPASSWD: /usr/bin/reboot</p>
<p>svxlink ALL=NOPASSWD: /usr/bin/shutdown</p>

<p>Save the file. No further action need be taken as sudo will automatically take these changes into account.</p>
<p>Next sudo nano /etc/apache2/envvars file and make the following changes</p>
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
<p>For a RepeaterLogic enabled setup,while we are in the terminal, sudo nano /etc/svxlink/svxlink.conf and locate in [RepeaterLogic] the line DTMF_CTRL_PTY, and edit the file information to read /tmp/dtmf_svx and save the file and sudo systemctl restart svxlink .</p>

<p>Now go to a browser and type in the URL of your svxlink node. Hopefully you will see a fully functional dashboard.</p>

<img width="617" alt="Screenshot 2024-02-05 at 17 36 24" src="https://github.com/f5vmr/SVXLink-Dash-V2/assets/8429684/4eabb239-af89-4ad4-8a14-d232888fbb62">




<p>The Editing function function now works, but due to the obvious public access this is now username and password protected. The sysop only will be able to edit the following file in the html folder. Navigate to the 'include' directory and sudo nano config.inc.php. Scroll to the bottom of the file where you will see some define commands. Replace "svxlink" with your chosen username within the quotation marks, and replace "password" with your chosen "password".</p>

<img width="617" alt="Screenshot 2024-02-05 at 17 28 44" src="https://github.com/f5vmr/SVXLink-Dash-V2/assets/8429684/09c9c182-3309-4719-895c-1db4810bc125">

<p>You may edit your configuration files. Copies are saved in the /etc/svxlink/ with a date/time suffix, if you do make a mistake.
</p>
<p>With Username and Password in place, you will have access to the 'Log', 'Power' and 'Edit' menus, allowing you as the sysop to expose the dashboard to public view, without someone corrupting your node. As soon as you click on the blue menus, the authorisation is rescinded. Naturally as this is a web page, all the changes take place within your local browser. Without authorisation, the 'Log', 'Power' and Edit menus are blocked, so no one can turn off your repeater, or mess with the logic configuration.</p>

<img width="617" alt="Screenshot 2024-02-05 at 17 29 14" src="https://github.com/f5vmr/SVXLink-Dash-V2/assets/8429684/53a42480-99b6-4869-b937-cecad62034a4">








<p>You will see no changes take place until you select the 'Power' menu and restart svxlink. You do not need a reboot.</p>

<p>On the DTMF menu you will see a dtmf Keypad arrangement. This needs more work and editing of that page. Whatch for further development.</p>
<p>Below the footer menu you will see a dtmf control facility. The D buttons are not yet programmed, and will need to correspond with the Macros in svxlink.conf so you will need to self-edit the dtmf.php file in your dashboard configuration and the macros in svxlink.conf.</p>
<p>I will eventually examine the possibility of making improvements and additions in the future.</p>


<p>The svxlink dashboard has some ideas created by G4NAB, SP2ONG, SP0DZ
and upgraded by G4NAB</p>
