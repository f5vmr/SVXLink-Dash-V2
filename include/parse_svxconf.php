<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config.php";         
include_once "tools.php";        
include_once "functions.php";

if ( (defined('SVXCONFIG')) && (defined('SVXCONFPATH')) ) {$svxConfigFile = SVXCONFPATH.SVXCONFIG ; }
else {$svxConfigFile = trim(substr(shell_exec("grep CFGFILE /etc/default/svxlink"), strrpos(shell_exec("grep CFGFILE /etc/default/svxlink"), "=")+1)); }
    if (fopen($svxConfigFile,'r'))
       $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
$callsign = $svxconfig['ReflectorLogic']['CALLSIGN'];

// Process logics and their configs
$check_logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
$logicConfigs = array();

foreach ($check_logics as $logic_key) {
    // Original system type and CTCSS detection
    if ($check_logics[0]=="RepeaterLogic") {
        $ctcss = $svxconfig['RepeaterLogic']['REPORT_CTCSS'];
        $system_type="IS_DUPLEX";
        $dtmfctrl = $svxconfig['RepeaterLogic']['DTMF_CTRL_PTY'];
    }
    if ($check_logics[0]=="SimplexLogic") {
        $ctcss = $svxconfig['SimplexLogic']['REPORT_CTCSS'];
        $system_type = "IS_SIMPLEX";
        $dtmfctrl = $svxconfig['SimplexLogic']['DTMF_CTRL_PTY'];
    }
    
    // Add logic config file mapping
    $configFile = MODULEPATH . "/$logic_key.conf";
    if (file_exists($configFile)) {
        $logicConfigs[$logic_key] = $configFile;
    }
}
$_SESSION['system_type'] = $system_type;

// additional variables need to define in svxlink.conf in stanza [ReflectorLogic]: API, FMNET, TG_URI
// FMNET - Name of FM-Network
// API - URI for access the status of SVXReflector you are connected
// TG_URI ??? I don't know...
// $refApi = $svxconfig['ReflectorLogic']['API'];
// $fmnetwork =$svxconfig['ReflectorLogic']['HOSTS'];
// //$tgUri = $svxconfig['ReflectorLogic']['TG_URI'];
// $nodeInfoFile = $svxconfig['ReflectorLogic']['NODE_INFO_FILE'];


