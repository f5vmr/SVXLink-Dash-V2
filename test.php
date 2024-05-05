<?php
session_start();
$screen = null;
$retval = null;

    exec('sudo cp /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.conf.' .date("YmdThis") ,$screen,$retval);
	//move generated file to current config
    exec('sudo cp -f /etc/svxlink/svxlink.conf /var/www/html/svxlink/svxlink.conf', $screen, $retval);
	exec('sudo mv /var/www/html/svxlink/svxlink.conf /etc/svxlink/svxlink.conf', $screen, $retval);
        //	exec('sudo cp /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.d/SomeLogic.conf', $screen, $retval);
        //Service SVXlink restart
    exec('sudo systemctl restart svxlink 2>&1',$screen,$retval);

// debug
      
      
