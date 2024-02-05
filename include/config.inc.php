<?php
// header lines for information
define("HEADER_CAT","FM-Repeater");
define("HEADER_QTH","");
define("HEADER_QRG","");
define("HEADER_SYSOP","");
define("FMNETWORK_EXTRA","");
define("EL_NODE_NR","");
define("FULLACCESS_OUTSIDE", 0);
define("ADD_BUTTONS", 1);
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
define("CALLSIGN","");
define("LOGICS","");
define("REPORT_CTCSS","");
define("DTMF_CTRL_PTY","");
define("API","");
define("FMNET","");
define("TG_URI","");
define("NODE_INFO_FILE","");
define("RF_MODULE","");
define("PHP_AUTH_USER", "svxlink");
define("PHP_AUTH_PW", "password");
?>
