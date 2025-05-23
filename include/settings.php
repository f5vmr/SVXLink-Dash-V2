<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once "config.php";
include_once "tools.php";
$dbversionFile = DL3EL . "/dbversion";
$dbversion = shell_exec('cat ' . $dbversionFile);

?>
