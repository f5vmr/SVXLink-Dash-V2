<?php
//Access to the Webserver for editing the config files
define("PHP_AUTH_USER", "svxlink");
define("PHP_AUTH_PW", "password");

// header lines for information
define("HEADER_CAT","FM-Repeater");
define("HEADER_QTH","null");
define("HEADER_QRG","null");
define("HEADER_SYSOP","");
define("FMNETWORK_EXTRA","");
define("EL_NODE_NR","null");
define("FULLACCESS_OUTSIDE", 0);
define("ADD_BUTTONS", 1);
///Variables for the webpages
//
// Button keys define: description button, DTMF command or command, color of button
//
// DTMF keys
// syntax: 'KEY number,'Description','DTMF code','color button'.
//
define("KEY1", array(' D1 ','*D1#','green'));
define("KEY2", array(' D2 ','*D2#','orange'));
define("KEY3", array(' D3 ','*D3#','orange'));
define("KEY4", array(' D4 ','*D4#','orange'));
define("KEY5", array(' D5', '*D5#','purple'));
define("KEY6", array(' D6 ','*D6#','purple'));
define("KEY7", array(' D7 ','*D7#','purple'));
define("KEY8", array(' D8 ','*D8#','blue'));
define("KEY9", array(' D9 ','*D9#','blue'));
define("KEY10", array(' D10 ','*D10#','red'));
// additional DTMF keys
define("KEY11", array(' D11 ','*D11#','green'));
define("KEY12", array(' D12 ','*D12#','orange'));
define("KEY13", array(' D13 ','*D13#','orange'));
define("KEY14", array(' D14 ','*D14#','orange'));
define("KEY15", array(' D15 ','*D15#','purple'));
define("KEY16", array(' D16 ','*D16#','purple'));
define("KEY17", array(' D17 ','*D17#','orange'));
define("KEY18", array(' D18 ','*D18#','blue'));
define("KEY19", array(' D19 ','*D19#','blue'));
define("KEY20", array(' D20 ','*D20#','red'));
define("SVXCONFPATH", "/etc/svxlink/");
define("SVXCONFIG", "svxlink.conf");
define("SVXLOGPATH", "/var/log/");
define("SVXLOGPREFIX","svxlink.log");
define("CALLSIGN","null");
define("LOGICS","");
define("REPORT_CTCSS","null");
define("DTMF_CTRL_PTY","null");
define("API","null");
define("FMNET","null");
define("TG_URI","");
define("NODE_INFO_FILE","/etc/svxlink/node_info.json");
define("RF_MODULE","null");
define("QTH","");
define("FREQ","");
define("LOCATION","null");
define("CW_AMP","inCwAmp");
define("CW_PITCH","inCwPitch");
define("CW_CPM","inCwCpm");
define("LINKS","null");
define("AUTH_KEY","password");
define("FMNETWORK","inFmnetwork");
define("DEFAULT_LANG","inReflectorDefaultLang");
define("RGR_DELAY","inRgrDelay");
define("IDLE_INTERVAL_TIME","inIdleIntervalTime");
define("FX_GAIN_NORMAL","inFxGainNormal");
define("FX_GAIN_LOW","inFxGainLow");
define("AUDIO_CHANNEL","inRxAudioChannel");
define("SQL_DELAY","inSqlDelay");
define("HID_DEVICE","inRxHidDevice");

$svxConfigFile = '/etc/svxlink/svxlink.conf';
if (fopen($svxConfigFile,'r'))
   { $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
     $refApi = $svxconfig['ReflectorLogic']['API'];
     $fmnetwork = $svxconfig['ReflectorLogic']['HOSTS'];
     $qth = $svxconfig['LocationInfo']['QTH'];
     $freq = $svxconfig['Rx1']['FREQ'];
    $EL_node = $svxconfig['LocationInfo']['LOCATION'];
    }
else { $callsign="NOCALL";
   $fmnetwork="no registered";
    }
