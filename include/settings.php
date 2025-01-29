<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once "config.php";
include_once "tools.php";
include_once "functions.php";

$svxConfigFile = '/etc/svxlink/svxlink.d/ReflectorLogic.conf';
if (file_exists($svxConfigFile)) {
    $svxconfig = parse_ini_file($svxConfigFile, true, INI_SCANNER_RAW);
    $_SESSION['callsign'] = $svxconfig['ReflectorLogic']['CALLSIGN'];
    $_SESSION['fmnetwork'] = $svxconfig['ReflectorLogic']['HOSTS'];
    $_SESSION['node_password'] = $svxconfig['ReflectorLogic']['AUTH_KEY'];
    $_SESSION['node_user'] = $_SESSION['callsign'];
    
    $callsign = $_SESSION['callsign'];
    $fmnetwork = $_SESSION['fmnetwork'];
    $node_password = $_SESSION['node_password'];
    $node_user = $_SESSION['node_user'];
} else {
    $_SESSION['callsign'] = "NOCALL";
    $_SESSION['fmnetwork'] = "not registered";
    $callsign = $_SESSION['callsign'];
    $fmnetwork = $_SESSION['fmnetwork'];
}
?>