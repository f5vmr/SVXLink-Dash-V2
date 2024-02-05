<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
<style type="text/css">
body {
  background-color: #eee;
  font-size: 18px;
  font-family: Arial;
  font-weight: 300;
  margin: 2em auto;
  max-width: 40em;
  line-height: 1.5;
  color: #444;
  padding: 0 0.5em;
}
h1, h2, h3 {
  line-height: 1.2;
}
a {
  color: #607d8b;
}
.highlighter-rouge {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: .2em;
  font-size: .8em;
  overflow-x: auto;
  padding: .2em .4em;
}
pre {
  margin: 0;
  padding: .6em;
  overflow-x: auto;
}

#player {
    position:relative;
    width:205px;
    overflow: hidden;
    direction: ltl;
}

textarea {
    background-color: #111;
    border: 1px solid #000;
    color: #ffffff;
    padding: 1px;
    font-family: courier new;
    font-size:10px;
}




</style>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="svxlink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">SVXLink Configurator</h1>


<?php

include_once "include/functions.php";

/*
$svxConfigFile = '/etc/svxlink/svxlink.conf';
if (fopen($svxConfigFile,'r'))
      {
        $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        };
//divide up the Paragraphs
$logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
foreach ($logics as $key) {
  //echo "<tr><td style=\"background:#ffffed;\"><span style=\"color:#b5651d;font-weight: bold;\">".$key."</span></td></tr>";
 if ($key == "SimplexLogic") $isSimplex = true;
 if ($key == "RepeaterLogic") $isRepeater = true;
  }
*/
include_once('parse_svxconf.php');
if (isset($_POST['btnSave']))
    {
        $retval = null;
        $screen = null;
              
	$svxconfig['GLOBAL']['LOGICS'] = $_POST['inGlobalLogics'];
        $svxconfig['GLOBAL']['RF_MODULE'] = $_POST['inGlobalRf'];
        $svxconfig['GLOBAL']['CFG_DIR'] = $_POST['inGlobalCfgDir'];
        $svxconfig['GLOBAL']['TIME_FORMAT'] = $_POST['inTimeFormat'];
        $svxconfig['GLOBAL']['CARD_CHANNELS'] = $_POST['inCardChannels'];
        $svxconfig['GLOBAL']['LOCATION_INFO'] = $_POST['inLocationInfo'];
        $svxconfig['GLOBAL']['LINKS'] = $_POST['inLinks'];
	$svxconfig['ReflectorLogic']['HOST_PORT'] = $_POST['inReflectorPort'];
	$svxconfig['ReflectorLogic']['API'] = $_POST['inReflectorApi'];
        $svxconfig['ReflectorLogic']['HOSTS'] = $_POST['inReflectorServer'];
        $svxconfig['ReflectorLogic']['DEFAULT_TG'] = $_POST['inDefaultTg'];
        $svxconfig['ReflectorLogic']['MONITOR_TGS'] = $_POST['inMonitorTgs'];
        $svxconfig['ReflectorLogic']['AUTH_KEY'] = $_POST['inPassword'];
        $svxconfig['ReflectorLogic']['HOSTS'] = $_POST['inFmNetwork'];
        $svxconfig['ReflectorLogic']['CALLSIGN'] = $_POST['inCallsign'];
	//$svxconfig['ReflectorLogic']['TG_URI'] = $_POST['inReflectorTgUri'];

        if ($system_type=="IS_SIMPLEX"){
	$svxconfig['SimplexLogic']['DEFAULT_LANG'] = $_POST['inSimplexDefaultLang'];
        $svxconfig['SimplexLogic']['CALLSIGN'] = $_POST['inSimplexCallsign'];
        $svxconfig['SimplexLogic']['MODULES'] = $_POST['inSimplexModules'];
        $svxconfig['SimplexLogic']['SHORT_IDENT_INTERVAL'] = $_POST['inSimShortInterval'];
        $svxconfig['SimplexLogic']['LONG_IDENT_INTERVAL'] = $_POST['inSimLongInterval'];
        $svxconfig['SimplexLogic']['RGR_SOUND_DELAY'] = $_POST['inRgrDelay'];
        $svxconfig['SimplexLogic']['RGR_SOUND_ALWAYS'] = $_POST['inRgr'];
        };
        if ($system_type=="IS_DUPLEX") {
        $svxconfig['RepeaterLogic']['DEFAULT_LANG'] = $_POST['inRepeaterDefaultLang'];
        $svxconfig['RepeaterLogic']['CALLSIGN'] = $_POST['inRepeaterCallsign'];
        $svxconfig['RepeaterLogic']['MODULES'] = $_POST['inRepeaterModules'];
        $svxconfig['RepeaterLogic']['SHORT_IDENT_INTERVAL'] = $_POST['inRepShortInterval'];
        $svxconfig['RepeaterLogic']['LONG_IDENT_INTERVAL'] = $_POST['inRepLongInterval'];
        $svxconfig['RepeaterLogic']['IDLE_TIMEOUT'] = $_POST['inIdleTime'];
        $svxconfig['RepeaterLogic']['OPEN_ON_1750'] = $_POST['in1750'];
        $svxconfig['RepeaterLogic']['OPEN_ON_CTCSS'] = $_POST['inCTCSS'];
        $svxconfig['RepeaterLogic']['OPEN_SQL_FLANK'] = $_POST['inSqlFlank'];
        $svxconfig['RepeaterLogic']['IDLE_SOUND_INTERVAL'] = $_POST['inIdleIntervalTime'];
        $svxconfig['RepeaterLogic']['ONLINE_CMD'] = $_POST['inOnLineCommand'];
        $svxconfig['RepeaterLogic']['ONLINE'] = $_POST['inOnline'];
};
        $svxconfig['LinkToReflector']['CONNECT_LOGICS'] = $_PORT['inConnLogic'];
        $svxconfig['Macros']['0'] = $_POST['inMD0'];
        $svxconfig['Macros']['1'] = $_POST['inMD1'];
	$svxconfig['Macros']['2'] = $_POST['inMD2'];
	$svxconfig['Macros']['3'] = $_POST['inMD3'];
	$svxconfig['Macros']['4'] = $_POST['inMD4'];
	$svxconfig['Macros']['5'] = $_POST['inMD5'];
	$svxconfig['Macros']['6'] = $_POST['inMD6'];
	$svxconfig['Macros']['7'] = $_POST['inMD7'];
	$svxconfig['Macros']['8'] = $_POST['inMD8'];
	$svxconfig['Macros']['9'] = $_POST['inMD9'];
        $svxconfig['Rx1']['AUDIO_DEV'] = $_PORT['inRxAudioDev'];
        $svxconfig['Rx1']['AUDIO_CHANNEL'] = $_PORT['inAudioChan'];
        $svxconfig['Rx1']['SQL_DET'] = $_POST['inSql_Det'];
        $svxconfig['Rx1']['GPIO_SQL_PIN'] = $_POST['inGPIOPin'];
        $svxconfig['Rx1']['DTMF_MUTING'] = $_POST['inDTMFMute'];
        $svxconfig['Rx1']['FQ'] = $_POST['inRxFreq'];
        $svxconfig['Rx1']['MODULATION'] = $POST['inMod'];
	$svxconfig['Rx1']['PEAK_METER'] = $_POST['inRx1PeakMeter'];
        $svxconfig['Tx1']['TYPE'] = $_POST['inTxType'];
        $svxconfig['Tx1']['PTT_TYPE'] = $_POST['inPTTType'];
        $svxconfig['Tx1']['AUDIO_DEV'] = $_POST['inTxAudioDev'];
        $svxconfig['Tx1']['PTT_PORT'] = $_POST['inPTTPort'];
        $svxconfig['Tx1']['PTT_PIN'] = $_POST['inPTTPin'];
        $svxconfig['LocationInfo']['LON_POSITION'] = $_POST['inLon'];
        $svxconfig['LocationInfo']['LAT_POSITION'] = $_POST['inLat'];
        $svxconfig['LocationInfo']['CALLSIGN'] = $_POST['inLocInfoCallsign'];
        $svxconfig['LocationInfo']['FREQUENCY'] = $_POST['inLocInfFreq'];

        $ini = build_ini_string($svxconfig);

        //file_put_contents("/var/www/html/test.ini",$ini,FILE_USE_INCLUDE_PAT);
        file_put_contents("/var/www/html/svxlink/svxlink.conf", $ini ,FILE_USE_INCLUDE_PATH);

	///file manipulation section

	$retval = null;
        $screen = null;
	//archive the current config
	exec('sudo cp /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.conf.' .date("YmdThis") ,$screen,$retval);
	//move generated file to current config
	exec('sudo mv /var/www/html/svxlink/svxlink.conf /etc/svxlink/svxlink.conf', $screen, $retval);
//	exec('sudo cp /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.d/SomeLogic.conf', $screen, $retval);
        //Service SVXlink restart
        exec('sudo service svxlink restart 2>&1',$screen,$retval);



// debug
//      echo '<pre>';
//      print_r($ini);

//end of debug

}


//if (fopen($svxConfigFile,'r'))
//      {

//        $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
//};

//$svxConfigFile = '/etc/svxlink/svxlink.conf';
//$svxConfigFile = '/var/www/html/svxlink.conf';    






//if (fopen($svxConfigFile,'r'))
  //    { 

//	$svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        
        $inGlobalLogics = $svxconfig['GLOBAL']['LOGICS'];
        $inGlobalRf = $svxconfig['GLOBAL']['RF_MODULE'];
        $inGlobalCfgDir = $svxconfig['GLOBAL']['CFG_DIR'];
        $inTimeFormat = $svxconfig['GLOBAL']['TIME_FORMAT'];
        $inCardChannels = $svxconfig['GLOBAL']['CARD_CHANNELS'];
        $inLocationInfo = $svxconfig['GLOBAL']['LOCATION_INFO'];
        $inLinks = $svxconfig['GLOBAL']['LINKS'];
	$inCallsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
	$inReflectorServer = $svxconfig['ReflectorLogic']['HOSTS'];
	$inReflectorApi = $svxconfig['ReflectorLogic']['API'];
	$inReflectorPort = $svxconfig['ReflectorLogic']['HOST_PORT'];
	$inDefaultTg = $svxconfig['ReflectorLogic']['DEFAULT_TG'];
	$inMonitorTgs = $svxconfig['ReflectorLogic']['MONITOR_TGS'];
	$inPassword = $svxconfig['ReflectorLogic']['AUTH_KEY'];
	$inFmNetwork = $svxconfig['ReflectorLogic']['HOSTS'];
	//$inReflectorTgUri = $svxconfig['ReflectorLogic']['TG_URI'];

        if ($system_type=="IS_SIMPLEX"){ 
	$inSimplexCallsign = $svxconfig['SimplexLogic']['CALLSIGN'];
	$inSimplexDefaultLang = $svxconfig['SimplexLogic']['DEFAULT_LANG'];
        $inSimplexModules = $svxconfig['SimplexLogic']['MODULES'];
        $inSimShortInterval = $svxconfig['SimplexLogic']['SHORT_IDENT_INTERVAL'];
        $inSimLongInterval = $svxconfig['SimplexLogic']['LONG_IDENT_INTERVAL'];
        $inRgrDelay = $svxconfig['SimplexLogic']['RGR_SOUND_DELAY'];
        $inRgr = $svxconfig['SimplexLogic']['RGR_SOUND_ALWAYS'];
};
        if ($system_type=="IS_DUPLEX"){
        $inRepeaterCallsign = $svxconfig['RepeaterLogic']['CALLSIGN'];
        $inRepeaterDefaultLang = $svxconfig['RepeaterLogic']['DEFAULT_LANG'];
        $inRepeaterModules = $svxconfig['RepeaterLogic']['MODULES'];
        $inRepShortInterval = $svxconfig['RepeaterLogic']['SHORT_IDENT_INTERVAL'];
        $inRepLongInterval = $svxconfig['RepeaterLogic']['LONG_IDENT_INTERVAL'];
        $inIdleTime = $svxconfig['RepeaterLogic']['IDLE_TIMEOUT'];
        $in1750 = $svxconfig['RepeaterLogic']['OPEN_ON_1750'];
        $inCTCSS = $svxconfig['RepeaterLogic']['OPEN_ON_CTCSS'];
        $inSqlFlank = $svxconfig['RepeaterLogic']['OPEN_SQL_FLANK'];
        $inIdleIntervalTime = $svxconfig['RepeaterLogic']['IDLE_SOUND_INTERVAL'];
        $inOnLineCommand = $svxconfig['RepeaterLogic']['ONLINE_CMD'];
        $inOnline = $svxconfig['RepeaterLogic']['ONLINE'];
        
        }

	$inMD0 =$svxconfig['Macros']['0'];
	$inMD1 =$svxconfig['Macros']['1'];
	$inMD2 =$svxconfig['Macros']['2'];
	$inMD3 =$svxconfig['Macros']['3'];
	$inMD4 =$svxconfig['Macros']['4'];
	$inMD5 =$svxconfig['Macros']['5'];
	$inMD6 =$svxconfig['Macros']['6'];
	$inMD7 =$svxconfig['Macros']['7'];
	$inMD8 =$svxconfig['Macros']['8'];
	$inMD9 =$svxconfig['Macros']['9'];

	$inRx1PeakMeter = $svxconfig['Rx1']['PEAK_METER'];

//}
//    else { $callsign="NOCALL";}



//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//  if (empty($_POST["ssid"])) {
//     echo "Name is required";
//  } else {
//    $ssid = $_POST["ssid"]);
//  }
//}}


// load the connlist
$retval = null;
$conns = null;
// find the gateway
//tbc - load the data from ini RF.

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">


<table>
        <tr>
        <th width = "380px">Global Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        <Table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        
        <tr style="border: none;">
        <td style="border: none;">Logics</td>
        <td style="border: none;"><input type="text" name="inGlobalLogics" style="width:98%" value="<?php echo $inGlobalLogics;?>"></td>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">RF Module</td>
        <td style="border: none;"><input type="text" name="inGlobalRf" style="width:98%" value="<?php echo $inGlobalRf;?>"></td>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Config Directory</td>
        <td style="border: none;"><input type="text" name="inGlobalCfgDir" style="width:98%" value="<?php echo $inGlobalCfgDir;?>"></td>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Time Format</td>
        <td style="border: none;"><input type="text" name="inTimeFormat" style="width:98%" value="<?php echo $inTimeFormat;?>"></td>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Card Channels</td>
        <td style="border: none;"><input type="text" name="inCardChannels" style="width:98%" value="<?php echo $inCardChannels;?>"></td>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Location Info</td>
        <td style="border: none;"><input type="text" name="inLocationInfo" style="width:98%" value="<?php echo $inLocationInfo;?>"></td>
        </tr>
        <tr style="border: none;">
        <td style="border: none;">Links</td>
        <td style="border: none;"> <input type="text" name="inLinks" style="width:98%" value="<?php echo $inLinks;?>"></td>
        </tr>
        </table>

</TD>

<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>

</tr>
</table>
<!-- Section for Simplex -->
        <table>
        <tr>
        <th width = "380px">Reflector Input</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<TD>

<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">FM Network</td>
        <td style="border: none;"><input type="text" name="inFmNetwork" style="width:98%" value="<?php echo $inFmNetwork;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Callsign</td>
        <td style="border: none;"><input type="text" name="inCallsign" style="width:98%" value="<?php echo $inCallsign;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Password</td>
        <td style="border: none;"><input type="password" name="inPassword" style="width:98%" value="<?php echo $inPassword;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Default TG</td>
        <td style="border: none;"><input type="text" name="inDefaultTg" style="width:98%" value="<?php echo $inDefaultTg;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Monitor TGs</td>
        <td style="border: none;"><input type="text" name="inMonitorTgs" style="width:98%" value="<?php echo $inMonitorTgs;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector Server</td>
        <td style="border: none;"><input type="text" name="inReflectorServer" style="width:98%" value="<?php echo $inReflectorServer;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector Port</td>
        <td style="border: none;"><input type="text" name="inReflectorPort" style="width:98%" value="<?php echo $inReflectorPort;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector Api</td>
        <td style="border: none;"><input type="text" name="inReflectorApi" style="width:98%" value="<?php echo $inReflectorApi;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector TgUri</td>
        <td style="border: none;"><input type="text" name="inReflectorTgUri" style="width:98%" value="<?php echo $inReflectorTgUri;?>">
        </td></tr>
</table>

</td>
<td> 
	<button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <br> 
        <Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>


<?php 
if ($system_type=="IS_SIMPLEX"){ include "simplex.php" ;};
if ($system_type=="IS_DUPLEX") { include "repeater.php";};

;

?>

<table>
        <tr>
        <th width = "380px">Macros Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        
<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D1</td>
        <td style="border: none;"><input type="text" name="inMD1" style="width:98%" value="<?php echo $inMD1;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D2</td>
        <td style="border: none;"><input type="text" name="inMD2" style="width:98%" value="<?php echo $inMD2;?>">
        <tr style="border: none;"> 
        <td style="border: none;">Macro D3</td>
        <td style="border: none;"><input type="text" name="inMD3" style="width:98%" value="<?php echo $inMD3;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D4</td>
        <td style="border: none;"><input type="text" name="inMD4" style="width:98%" value="<?php echo $inMD4;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D5</td>
        <td style="border: none;"><input type="text" name="inMD5" style="width:98%" value="<?php echo $inMD5;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D6</td>
        <td style="border: none;"><input type="text" name="inMD6" style="width:98%" value="<?php echo $inMD6;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D7</td>
        <td style="border: none;"><input type="text" name="inMD7" style="width:98%" value="<?php echo $inMD7;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D8</td>
        <td style="border: none;"><input type="text" name="inMD8" style="width:98%" value="<?php echo $inMD8;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D9</td>
        <td style="border: none;"><input type="text" name="inMD9" style="width:98%" value="<?php echo $inMD9;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Macro D0</td>
        <td style="border: none;"><input type="text" name="inMD0" style="width:98%" value="<?php echo $inMD0;?>">
        </td></tr>
</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>



<table>
        <tr>
        <th width = "380px">Rx1 Input</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        
<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Peak Meter</td>
        <td style="border: none;"><input type="text" name="inRx1PeakMeter" style="width:98%" value="<?php echo $inRx1PeakMeter;?>">
        </td></tr>


</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <BR><Br> & <BR><BR> ReLoad</button>
</td>
</tr>
</table>




</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
