<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once "config.php";
include_once "tools.php";
include_once "functions.php";
require_once "ConfigHandler.php";
$config = ConfigHandler::getInstance();

// Set session variables
$_SESSION['callsign'] = $config->getCallsign();
$_SESSION['fmnetwork'] = $config->getFmNetwork();

// Read main svxlink config for logic modules
$mainConfigFile = '/etc/svxlink/svxlink.conf';
if (file_exists($mainConfigFile)) {
    $svxconfig = parse_ini_file($mainConfigFile, true, INI_SCANNER_RAW);
}
?>

