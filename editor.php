<?php
session_start();
$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
include_once "include/config.php";
include_once "include/config.inc.php";
include_once "include/tools.php";
include_once "include/functions.php";
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
<head>
    <meta name="robots" content="index" />
    <meta name="robots" content="follow" />
    <meta name="language" content="English" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="SVXLink" />
    <meta name="Author" content="G4NAB, SP2ONG, SP0DZ" />
    <meta name="Description" content="Dashboard for SVXLink by G4NAB, SP2ONG, SP0DZ" />
    <meta name="KeyWords" content="SVXLink,G4NAB, SP2ONG, SP0DZ" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
<link rel="shortcut icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/png">    

<?php echo ("<title>" . $callsign ." ". $fmnetwork . " Dashboard</title>"); ?>

<?php include_once "include/browserdetect.php"; ?>
    <script type="text/javascript" src="scripts/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" src="scripts/pcm-player.min.js"></script>
    <script type="text/javascript">
      $.ajaxSetup({ cache: false });
    </script>
    <link href="css/featherlight.css" type="text/css" rel="stylesheet" />
    <script src="scripts/featherlight.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="">

</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style="box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:0px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div class="container"> 
<div class="header">
<div class="parent">
    <div class="img" style="padding-left:30px"><img src="images/svxlink.ico" /></div>
    <div class="text"style="padding-right:230px">
<center><p style="margin-top:5px;margin-bottom:0px;">
<span style="font-size: 32px;letter-spacing:4px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $callsign; ?></span>
<p style="margin-top:0px;margin-bottom:0px;">
<span style="font-size: 18px;letter-spacing:4px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $fmnetwork; ?></span>
</p></center>
</div></div>
</div>


<?php

include_once "include/top_menu.php"; 
?>

<div class="content"><center>
<div style="margin-top:0px;">
</div></center>
</div>
<?php
//if (isProcessRunning('node')) {
//echo '&nbsp;&nbsp;<button class="button link" onclick="playAudioToggle(8080, this)"><b>&nbsp;&nbsp;&nbsp;<img src=images/speaker.png alt="" style="vertical-align:middle">&nbsp;&nbsp;RX Monitor&nbsp;&nbsp;&nbsp;</b></button><br><br>';
//}
?>
<?php
if (MENUBUTTON=="TOP") {
include_once "include/buttons.php";

}

?>
<?php

if($_SESSION['auth'] == 'AUTHORISED') {

if($_GET['id']=="svxlink") {
    //checkAuth();
    //shell_exec("cd ");
    file_backup("svxlink.conf");
    $edit_file = file_name("svxlink.conf"); 
    //"/etc/svxlink/svxlink.conf";
}
elseif($_GET['id']=="gpio") {
    //shell_exec("cd ");
    file_backup("gpio.conf");
    $edit_file = file_name("gpio.conf");
    //$edit_file = "/etc/svxlink/gpio.conf";
}
elseif($_GET['id']=="echolink"){
    //shell_exec("cd ");
    file_backup("svxlink.d/ModuleEchoLink.conf");
    $edit_file = file_name("svxlink.d/ModuleEchoLink.conf");
    //$edit_file = "/etc/svxlink/svxlink.d/ModuleEchoLink.conf";
}
elseif($_GET['id']=="metarinfo"){
    //shell_exec("cd ");
    file_backup("svxlink.d/ModuleMetarInfo.conf");
    $edit_file = file_name("svxlink.d/ModuleMetarInfo.conf");
    //$edit_file = "/etc/svxlink/svxlink.d/ModuleMetarInfo.conf";
}
elseif($_GET['id']=="nodeInfo"){
    //$edit_file = "/etc/svxlink/node_info.json";
    file_backup("node_info.json");
    $edit_file = file_name("node_info.json");
}

// check if form has been submitted
//$filename = by choice;


}
?>
<?php
    echo '<table style="margin-bottom:0px;border:0; border-collapse:collapse; cellspacing:0; cellpadding:0; background-color:#f1f1f1;"><tr style="border:none;background-color:#f1f1f1;">';
    echo '<td width="200px" valign="top" class="hide" style="height:auto;border:0;background-color:#f1f1f1;">';
    echo '<div class="nav" style="margin-bottom:1px;margin-top:1px;">'."\n";

    echo '<script type="text/javascript">'."\n";
    echo 'function reloadStatusInfo(){'."\n";
    echo '  $("#statusInfo").load("include/status.php",function(){ setTimeout(reloadStatusInfo,3000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadStatusInfo,3000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="statusInfo" style="margin-bottom:30px;">'."\n";
    include "include/status.php";
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</td>'."\n";
    echo '<td valign="top"  style="height:595px; width=620px; text-align: center; border:none;  background-color:#f1f1f1;">';	
//Validate User Password
//if($_SERVER['REQUEST_METHOD'] == 'POST') {
//
//    // Check posted credentials
//    checkAuth();
//    
//    // Credentials valid, show content
//    echo "Authenticated!";
//  
//  } else {
//  
//    // Show login form
//    echo '<form method="post">
//             <input name="username">
//             <input name="password">
//             <input type="submit">
//          </form>';
//  
//  }
//
    echo '<iframe height="100%" id="editIframe" src="/edit?file=' . $edit_file . '"  width="620px" height="495px" title="EDIT" </iframe>';

    echo '</td>';
?>
</tr></table>
<?php
//
    echo '<div class="content2">'."\n";
    echo '<script type="text/javascript">'."\n";
    echo 'function reloadSysInfo(){'."\n";
    echo '  $("#sysInfo").load("include/system.php",function(){ setTimeout(reloadSysInfo,15000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadSysInfo,15000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="sysInfo">'."\n";
    include "include/system.php";
    echo '</div>'."\n";
    echo '</div>'."\n";
?>
<?php
if (MENUBUTTON=="BOTTOM") {
include_once "include/buttons.php"; }
?>
<center><span title="Dashboard" style="font: 7pt arial, sans-serif;">SvxLink Dashboard ©  G4NAB, SP2ONG, SP0DZ <?php $cdate=date("Y"); if ($cdate > "2021") {$cdate="2021-".date("Y");} echo $cdate; ?>
</div>
</fieldset>
<br>
</body>
</html>
