<?php
session_start();
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once "config.php";
include_once "tools.php";

$svxConfigFile = '/etc/svxlink/svxlink.conf';
if (fopen($svxConfigFile, 'r')) {
    $svxconfig = parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW);
    $callsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
    $fmnetwork = $svxconfig['ReflectorLogic']['HOSTS'];
    //$tgUri = $svxconfig['ReflectorLogic']['TG_URI'];
    $node_password = $svxconfig['ReflectorLogic']['AUTH_KEY'];
    $node_user = $callsign;}
else { $callsign="NOCALL"; 
       $fmnetwork="not registered";
	}

?>