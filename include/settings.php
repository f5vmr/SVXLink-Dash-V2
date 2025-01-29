<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once "config.php";
include_once "tools.php";
include_once "functions.php";

// Read ReflectorLogic config for callsign and network info
$svxConfigFile = '/etc/svxlink/svxlink.d/ReflectorLogic.conf';
if (file_exists($svxConfigFile)) {
    $reflectorConfig = parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW);
    $callsign = $reflectorConfig['ReflectorLogic']['CALLSIGN'];
    $fmnetwork = $reflectorConfig['ReflectorLogic']['HOSTS'];
    $node_password = $reflectorConfig['ReflectorLogic']['AUTH_KEY'];
    $node_user = $callsign;
} else {
    $callsign = "NOCALL";
    $fmnetwork = "not registered";
}

// Read main svxlink config for logic modules
$mainConfigFile = '/etc/svxlink/svxlink.conf';
if (file_exists($mainConfigFile)) {
    $svxconfig = parse_ini_file($mainConfigFile, true, INI_SCANNER_RAW);
}
?>

