<?php
session_start();
include "config.php";         
include_once "tools.php";        
include_once "functions.php";

if ( (defined('SVXCONFIG')) && (defined('SVXCONFPATH')) ) {$svxConfigFile = SVXCONFPATH."/".SVXCONFIG ; }
else {$svxConfigFile = trim(substr(shell_exec("grep CFGFILE /etc/default/svxlink"), strrpos(shell_exec("grep CFGFILE /etc/default/svxlink"), "=")+1)); }
    if (fopen($svxConfigFile,'r'))
       {$svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
         $callsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
         // check if we are a repeater or a simplex system
         $check_logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
            foreach ($check_logics as $logic_key) {
            if ($check_logics[0]=="RepeaterLogic") {
              // if we work with CTCSS please set REPORT_CTCSS with correct value in svxlink.conf
              $ctcss = $svxconfig['RepeaterLogic']['REPORT_CTCSS'];
              $system_type="IS_DUPLEX"; // if repeater
              $dtmfctrl = $svxconfig['RepeaterLogic']['DTMF_CTRL_PTY']; }
            if ($check_logics[0] =="SimplexLogic") {
            // if we work with CTCSS please set REPORT_CTCSS with correct value in svxlink.conf
            $ctcss = $svxconfig['SimplexLogic']['REPORT_CTCSS'];
            $system_type = "IS_SIMPLEX"; // if simplex
            $dtmfctrl = $svxconfig['SimplexLogic']['DTMF_CTRL_PTY'];
        }
         }
         // additional variables need to define in svxlink.conf in stanza [ReflectorLogic]: API, FMNET, TG_URI
         // FMNET - Name of FM-Network
         // API - URI for access the status of SVXReflector you are connected
         // TG_URI ??? I don't know...
         $refApi = $svxconfig['ReflectorLogic']['API'];
         $fmnetwork =$svxconfig['ReflectorLogic']['HOSTS'];
         //$tgUri = $svxconfig['ReflectorLogic']['TG_URI'];
         $nodeInfoFile = $svxconfig['ReflectorLogic']['NODE_INFO_FILE'];
    $globalRf = $svxconfig['GLOBAL']['RF_MODULE'];
       }
else { $callsign="NOCALL";
       $fmnetwork="no registered";
       $refApi="";
        }
?>