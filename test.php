<?php
session_start();
$svxConfigFile = "/etc/svxlink/svxlink.conf";
$svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
$callsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
// check if we are a repeater or a simplex system
$check_logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
foreach ($check_logics as $logic_key) {
    if ($check_logics[0]=="RepeaterLogic") {
        // if we work with CTCSS please set REPORT_CTCSS with correct value in svxlink.conf
        $ctcss = $svxconfig['RepeaterLogic']['REPORT_CTCSS'];
        $system_type="IS_DUPLEX"; // if repeater
        $dtmfctrl = $svxconfig['RepeaterLogic']['DTMF_CTRL_PTY']; 
    }
    if ($check_logics[0] =="SimplexLogic") {
        // if we work with CTCSS please set REPORT_CTCSS with correct value in svxlink.conf
        $ctcss = $svxconfig['SimplexLogic']['REPORT_CTCSS'];
        $system_type = "IS_SIMPLEX"; // if simplex
        $dtmfctrl = $svxconfig['SimplexLogic']['DTMF_CTRL_PTY'];
    }
    
}
$_SESSION['system_type'] = $system_type;
echo $check_logics[0];
echo $callsign;
echo $ctcss;
echo $system_type;
