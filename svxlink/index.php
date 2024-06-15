<?php
if (session_status() === PHP_SESSION_NONE) {       
    session_start();
}



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
<body style = "background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style = "border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style = "padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="svxlink" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">SVXLink Configurator</h1>


<?php

          
        include_once "../include/functions.php";
        $directory="/etc/svxlink/";
        $svxConfigFile = 'svxlink.conf';
        file_backup($directory,$svxConfigFile);
        $config = $directory.$svxConfigFile;
        $lines = file($config, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        //if (fopen($svxConfigFile,'r'))
        //      {
        //        $lines = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        //        };
        //divide up the Paragraphs
        $logics = explode(",",$lines['GLOBAL']['LOGICS']);
        foreach ($logics as $key) {
          //echo "<tr><td style=\"background:#ffffed;\"><span style=\"color:#b5651d;font-weight: bold;\">".$key."</span></td></tr>";
         if ($key == "SimplexLogic") $isSimplex = true;
         if ($key == "RepeaterLogic") $isRepeater = true;
          }
        

        include_once('../include/parse_svxconf.php');
        
        $system_type = $_SESSION['system_type'];
        if (isset($_POST['btnSave']))
            {
        
        $retval = null;
        $screen = null;
              
	$lines['GLOBAL']['LOGICS'] = $_POST['inGlobalLogics'];
        //$lines['GLOBAL']['RF_MODULE'] = $_POST['inGlobalRf'];
        $lines['GLOBAL']['CFG_DIR'] = $_POST['inGlobalCfgDir'];
        $lines['GLOBAL']['TIME_FORMAT'] = $_POST['inTimeFormat'];
        $lines['GLOBAL']['CARD_SAMPLE_RATE'] = $_POST['inCardSampleRate'];
        $lines['GLOBAL']['CARD_CHANNELS'] = $_POST['inCardChannels'];
        $lines['GLOBAL']['LOCATION_INFO'] = $_POST['inLocationInfo'];
        $lines['GLOBAL']['LINKS'] = $_POST['inLinks'];
        $lines['GLOBAL']['CW_AMP'] = $_POST['inCwAmp'];
        $lines['GLOBAL']['CW_PITCH'] = $_POST['inCwPitch'];
        $lines['GLOBAL']['CW_CPM'] = $_POST['inCwCpm'];
        if ($key == "ReflectorLogic") {
	$lines['ReflectorLogic']['HOSTS'] = $_POST['inReflectorServer'];
        $lines['ReflectorLogic']['HOST_PORT'] = $_POST['inReflectorPort'];
        $lines['ReflectorLogic']['FMNET'] = $_POST['inFmnetwork'];
	$lines['ReflectorLogic']['API'] = $_POST['inrefApi'];
        $lines['ReflectorLogic']['DEFAULT_TG'] = $_POST['inDefaultTg'];
        $lines['ReflectorLogic']['TG_SELECT_TIMEOUT'] = $_POST['inTgSelectTimeout'];
        $lines['ReflectorLogic']['MONITOR_TGS'] = $_POST['inMonitorTgs'];
        $lines['ReflectorLogic']['AUTH_KEY'] = $_POST['password'];
        $lines['ReflectorLogic']['CALLSIGN'] = $_POST['inRefCallsign'];
        $lines['ReflectorLogic']['DEFAULT_LANG'] = $_POST['inReflectorDefaultLang'];
        $lines['ReflectorLogic']['NODE_INFO_FILE'] = $_POST['inNodeInfoFile'];
        }

	//$lines['ReflectorLogic']['TG_URI'] = $_POST['inReflectorTgUri'];

        if ($system_type == "IS_SIMPLEX"){
	$lines['SimplexLogic']['DEFAULT_LANG'] = $_POST['inSimplexDefaultLang'];
        $lines['SimplexLogic']['CALLSIGN'] = $_POST['inSimplexCallsign'];
        $lines['SimplexLogic']['MODULES'] = $_POST['inSimplexModules'];
        $lines['SimplexLogic']['SHORT_IDENT_INTERVAL'] = $_POST['inSimShortInterval'];
        $lines['SimplexLogic']['LONG_IDENT_INTERVAL'] = $_POST['inSimLongInterval'];
        $lines['SimplexLogic']['RGR_SOUND_DELAY'] = $_POST['inRgrDelay'];
        $lines['SimplexLogic']['RGR_SOUND_ALWAYS'] = $_POST['inRgr'];
        $lines['SimplexLogic']['FX_GAIN_NORMAL'] = $_POST['inFxGainNormal'];
        $lines['SimplexLogic']['FX_GAIN_LOW'] = $_POST['inFxGainLow'];
        $lines['SimplexLogic']['ONLINE_CMD'] = $_POST['inOnLineCmd'];
        $lines['SimplexLogic']['ONLINE'] = $_POST['inOnLine'];    
        }
        if ($system_type == "IS_DUPLEX") {
        $lines['RepeaterLogic']['DEFAULT_LANG'] = $_POST['inRepeaterDefaultLang'];
        $lines['RepeaterLogic']['CALLSIGN'] = $_POST['inRepeaterCallsign'];
        $lines['RepeaterLogic']['MODULES'] = $_POST['inRepeaterModules'];
        $lines['RepeaterLogic']['SHORT_IDENT_INTERVAL'] = $_POST['inRepShortInterval'];
        $lines['RepeaterLogic']['LONG_IDENT_INTERVAL'] = $_POST['inRepLongInterval'];
        $lines['RepeaterLogic']['IDLE_TIMEOUT'] = $_POST['inIdleTime'];
        $lines['RepeaterLogic']['OPEN_ON_1750'] = $_POST['in1750'];
        $lines['RepeaterLogic']['OPEN_ON_CTCSS'] = $_POST['inCTCSS'];
        $lines['RepeaterLogic']['OPEN_SQL_FLANK'] = $_POST['inSqlFlank'];
        $lines['RepeaterLogic']['RGR_SOUND_DELAY'] = $_POST['inRgrDelay'];
        $lines['RepeaterLogic']['IDLE_SOUND_INTERVAL'] = $_POST['inIdleIntervalTime'];
        $lines['RepeaterLogic']['ONLINE_CMD'] = $_POST['inOnLineCommand'];
        $lines['RepeaterLogic']['ONLINE'] = $_POST['inOnline'];
        $lines['RepeaterLogic']['FX_GAIN_NORMAL'] = $_POST['inFxGainNormal'];
        $lines['RepeaterLogic']['FX_GAIN_LOW'] = $_POST['inFxGainLow'];
        }
        if ($key == "ReflectorLogic") {
        $lines['LinkToReflector']['CONNECT_LOGICS'] = $_POST['inConnLogic'];
        }
        $lines['Macros']['0'] = $_POST['inMD0'];
        $lines['Macros']['1'] = $_POST['inMD1'];
	$lines['Macros']['2'] = $_POST['inMD2'];
	$lines['Macros']['3'] = $_POST['inMD3'];
	$lines['Macros']['4'] = $_POST['inMD4'];
	$lines['Macros']['5'] = $_POST['inMD5'];
	$lines['Macros']['6'] = $_POST['inMD6'];
	$lines['Macros']['7'] = $_POST['inMD7'];
	$lines['Macros']['8'] = $_POST['inMD8'];
	$lines['Macros']['9'] = $_POST['inMD9'];
        $lines['Rx1']['TYPE'] = $_POST['inRxType'];
        $lines['Rx1']['AUDIO_DEV'] = $_POST['inRxAudioDev'];
        $lines['Rx1']['AUDIO_CHANNEL'] = $_POST['inRxAudioChan'];
        $lines['Rx1']['SQL_DET'] = $_POST['inSql_Det'];
        $lines['Rx1']['SQL_START_DELAY'] = $_POST['inSqlStartDelay'];
        $lines['Rx1']['SQL_DELAY'] = $_POST['inSqlDelay'];
        $lines['Rx1']['SQL_HANG_TIME'] = $_POST['inSqlHangTime'];
        $lines['Rx1']['SQL_GPIOD_CHIP'] = $_POST['inSqlChip'];
        $lines['Rx1']['SQL_GPIOD_LINE'] = $_POST['inSqlLine'];
        $lines['Rx1']['HID_DEVICE'] = $_POST['inRxHIDDevice'];
        $lines['Rx1']['HID_SQL_PIN'] = $_POST['inRxHIDPin'];
        $lines['Rx1']['SQL_TAIL_ELIM'] = $_POST['inSqlTail'];
        $lines['Rx1']['1750_MUTING'] = $_POST['in1750Mute'];
        $lines['Rx1']['DTMF_MUTING'] = $_POST['inDTMFMute'];
        $lines['Rx1']['FREQ'] = $_POST['inRxFreq'];
        $lines['Rx1']['MODULATION'] = $POST['inMod'];
	$lines['Rx1']['PEAK_METER'] = $_POST['inRx1PeakMeter'];
        $lines['Tx1']['TYPE'] = $_POST['inTxType'];
        $lines['Tx1']['AUDIO_DEV'] = $_POST['inTxAudioDev'];
        $lines['Tx1']['AUDIO_CHANNEL'] = $_POST['inTxAudioChannel'];
        $lines['Tx1']['PTT_TYPE'] = $_POST['inPttType'];
        $lines['Tx1']['PTT_PORT'] = $_POST['inPttPort'];
        $lines['Tx1']['HID_DEVICE'] = $_POST['inTxHidDevice'];
        $lines['Tx1']['PTT_PIN'] = $_POST['inPttPin'];
        $lines['Tx1']['HID_PTT_PIN'] = $_POST['inHidPttPin'];
        $lines['Tx1']['PTT_HANGTIME'] = $_POST['inPttHangtime'];
        $lines['Tx1']['PTT_GPIOD_CHIP'] = $_POST['inPttGPIOChip'];
        $lines['Tx1']['PTT_GPIOD_LINE'] = $_POST['inPttGPIOLine'];
        $lines['Tx1']['TIMEOUT'] = $_POST['inTxTimeout'];
        $lines['MultiTx']['TRANSMITTERS'] = $_POST['inMultiTx'];
        $lines['TxStream']['AUDIO_DEV'] = $_POST['inTxStreamAudioDev'];
        $lines['NetRx']['HOST'] = $_POST['inNetRxHost'];
        $lines['NetRx']['PORT'] = $_POST['inNetRxPort'];
        $lines['NetRx']['AUTH_KEY'] = $_POST['inNetRxAuthKey'];
        $lines['NetTx']['HOST'] = $_POST['inNetTxHost'];
        $lines['NetTx']['PORT'] = $_POST['inNetTxPort'];
        $lines['NetTx']['AUTH_KEY'] = $_POST['inNetTxAuthKey'];
        $lines['LocationInfo']['APRS_SERVER_LIST'] = $_POST['inAprsServerList'];
        $lines['LocationInfo']['STATUS_SERVER_LIST'] = $_POST['inStatusServerList'];
        $lines['LocationInfo']['LON_POSITION'] = $_POST['inLon'];
        $lines['LocationInfo']['LAT_POSITION'] = $_POST['inLat'];
        $lines['LocationInfo']['CALLSIGN'] = $_POST['inLocInfoCallsign'];
        $lines['LocationInfo']['FREQUENCY'] = $_POST['inLocInfFreq'];
        $lines['LocationInfo']['TXPOWER'] = $_POST['inTxPower'];
        $lines['LocationInfo']['ANTENNA_GAIN'] = $_POST['inAntennaGain'];
        $lines['LocationInfo']['ANTENNA_HEIGHT'] = $_POST['inAntennaHeight'];
        $lines['LocationInfo']['ANTENNA_DIR'] = $_POST['inAntennaDir'];
        $lines['LocationInfo']['PATH'] = $_POST['inPath'];
        $lines['LocationInfo']['BEACON_INTERVAL'] = $_POST['inBeaconInterval'];
        $lines['LocationInfo']['COMMENT'] = $_POST['inComment'];      


        $ini = build_ini_string($lines);
  
//        file_put_contents("/var/www/html/test.ini",$ini,FILE_USE_INCLUDE_PATH);
        file_put_contents("/var/www/html/svxlink/svxlink.conf", $ini ,FILE_USE_INCLUDE_PATH);

	///file manipulation section

	$retval = null;
        $screen = null;
	//archive the current config
	//move generated file to current config
        exec('sudo chown root:svxlink /var/www/html/svxlink/svxlink.conf', $screen, $retval);
	exec('sudo -S mv /var/www/html/svxlink/svxlink.conf /etc/svxlink/svxlink.conf', $screen, $retval);
        //	exec('sudo cp /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.d/SomeLogic.conf', $screen, $retval);
        //Service SVXlink restart
        exec('sudo systemctl restart svxlink 2>&1',$screen,$retval);



        // debug
        //      echo '<pre>';
        //      print_r($ini);

        //end of debug

        }


        //if (fopen($svxConfigFile,'r'))
        //      {

        //        $lines = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        //};

        //$svxConfigFile = '/etc/svxlink/svxlink.conf';
        //$svxConfigFile = '/var/www/html/svxlink.conf';    






        //if (fopen($svxConfigFile,'r'))
          //    { 

        //	$lines = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
        
        $inGlobalLogics = $lines['GLOBAL']['LOGICS'];
        //$inGlobalRf = $lines['GLOBAL']['RF_MODULE'];
        $inGlobalCfgDir = $lines['GLOBAL']['CFG_DIR'];
        $inTimeFormat = $lines['GLOBAL']['TIME_FORMAT'];
        $inCardSampleRate = $lines['GLOBAL']['CARD_SAMPLE_RATE'];
        $inCardChannels = $lines['GLOBAL']['CARD_CHANNELS'];
        $inLocationInfo = $lines['GLOBAL']['LOCATION_INFO'];
        $inLinks = $lines['GLOBAL']['LINKS'];
        $inCwAmp = $lines['GLOBAL']['CW_AMP'];
        $inCwPitch = $lines['GLOBAL']['CW_PITCH'];
        $inCwCpm = $lines['GLOBAL']['CW_CPM'];
        $inFmnetwork = $lines['ReflectorLogic']['FMNET'];
	$inReflectorServer = $lines['ReflectorLogic']['HOSTS'];
	$inReflectorPort = $lines['ReflectorLogic']['HOST_PORT'];
        $inrefApi = $lines['ReflectorLogic']['API'];
	$inDefaultTg = $lines['ReflectorLogic']['DEFAULT_TG'];
	$inTgSelectTimeout = $lines['ReflectorLogic']['TG_SELECT_TIMEOUT'];
        $inMonitorTgs = $lines['ReflectorLogic']['MONITOR_TGS'];
	$inPassword = $lines['ReflectorLogic']['AUTH_KEY'];
        $inRefCallsign = $lines['ReflectorLogic']['CALLSIGN'];
        $inReflectorDefaultLang = $lines['ReflectorLogic']['DEFAULT_LANG'];
        $inConnLogic = $lines['LinkToReflector']['CONNECT_LOGICS'];
        $inRxType = $lines['Rx1']['TYPE'];
        $inRxAudioDev = $lines['Rx1']['AUDIO_DEV'];
        $inRxAudioChannel = $lines['Rx1']['AUDIO_CHANNEL'];
        $inSqlDet = $lines['Rx1']['SQL_DET'];
        $inSqlStartDelay = $lines['Rx1']['SQL_START_DELAY'];
        $inSqlDelay = $lines['Rx1']['SQL_DELAY'];
        $inSqlHangTime = $lines['Rx1']['SQL_HANG_TIME'];
        $inSqlChip = $lines['Rx1']['SQL_GPIOD_CHIP'];
        $inSqlLine = $lines['Rx1']['SQL_GPIOD_LINE'];
        $inRxHidDevice = $lines['Rx1']['HID_DEVICE'];
        $inRxHidPin = $lines['Rx1']['HID_PIN'];
        $in1750Mute = $lines['Rx1']['1750_MUTING'];
        $inDTMFMute = $lines['Rx1']['DTMF_MUTING'];
        $inRxFreq = $lines['Rx1']['FREQ'];
        $inMod = $lines['Rx1']['MOD'];
        $inRx1PeakMeter = $lines['Rx1']['PEAK_METER'];
        $inTxType = $lines['Tx1']['TYPE'];
        $inTxAudioDev = $lines['Tx1']['AUDIO_DEV'];
        $inTxAudioChannel = $lines['Tx1']['AUDIO_CHANNEL'];
        $inTxHidDevice = $lines['Tx1']['HID_DEVICE'];
        $inPttType = $lines['Tx1']['PTT_TYPE'];
        $inPttPort = $lines['Tx1']['PTT_PORT'];
        $inPttPin = $lines['Tx1']['PTT_PIN'];
        $inHidPttPin = $lines['Tx1']['HID_PTT_PIN'];
        $inPttHangTime = $lines['Tx1']['PTT_HANG_TIME'];
        $inPttGPIOChip = $lines['Tx1']['PTT_GPIO_CHIP'];
        $inPttGPIOLine = $lines['Tx1']['PTT_GPIO_LINE'];
        $inTxTimeout = $lines['Tx1']['TIMEOUT'];

        //$inReflectorTgUri = $lines['ReflectorLogic']['TG_URI'];

 
        
	$inMD0 =$lines['Macros']['0'];
	$inMD1 =$lines['Macros']['1'];
	$inMD2 =$lines['Macros']['2'];
	$inMD3 =$lines['Macros']['3'];
	$inMD4 =$lines['Macros']['4'];
	$inMD5 =$lines['Macros']['5'];
	$inMD6 =$lines['Macros']['6'];
	$inMD7 =$lines['Macros']['7'];
	$inMD8 =$lines['Macros']['8'];
	$inMD9 =$lines['Macros']['9'];

        $inRxType = $lines['Rx1']['TYPE'];
        $inRxAudioDev = $lines['Rx1']['AUDIO_DEV'];
        $inRxAudioChannel = $lines['Rx1']['AUDIO_CHANNEL'];
        $inSqlDet = $lines['Rx1']['SQL_DET'];
        $inSqlStartDelay = $lines['Rx1']['SQL_START_DELAY'];
        $inSqlDelay = $lines['Rx1']['SQL_DELAY'];
        $inSqlHangTime = $lines['Rx1']['SQL_HANG_TIME'];
        $inSqlChip = $lines['Rx1']['SQL_GPIOD_CHIP'];
        $inSqlLine = $lines['Rx1']['SQL_GPIOD_LINE'];
        $inRxHidDevice = $lines['Rx1']['HID_DEVICE'];
        $inRxHidPin = $lines['Rx1']['HID_PIN'];
        $in1750Mute = $lines['Rx1']['1750_MUTING'];
        $inDTMFMute = $lines['Rx1']['DTMF_MUTING'];
        $inRxFreq = $lines['Rx1']['FREQ'];
        $inMod = $lines['Rx1']['MOD'];
        $inRx1PeakMeter = $lines['Rx1']['PEAK_METER'];
        $inTxType = $lines['Tx1']['TYPE'];
        $inTxAudioDev = $lines['Tx1']['AUDIO_DEV'];
        $inTxAudioChannel = $lines['Tx1']['AUDIO_CHANNEL'];
        $inTxHidDevice = $lines['Tx1']['HID_DEVICE'];
        $inPttType = $lines['Tx1']['PTT_TYPE'];
        $inPttPort = $lines['Tx1']['PTT_PORT'];
        $inPttPin = $lines['Tx1']['PTT_PIN'];
        $inHidPttPin = $lines['Tx1']['HID_PTT_PIN'];
        $inPttHangTime = $lines['Tx1']['PTT_HANG_TIME'];
        $inPttGPIOChip = $lines['Tx1']['PTT_GPIO_CHIP'];
        $inPttGPIOLine = $lines['Tx1']['PTT_GPIO_LINE'];
        $inTxTimeout = $lines['Tx1']['TIMEOUT'];
        $inAprsServerList = $lines['LocationInfo']['APRS_SERVER_LIST'];
        $inServerStatusList = $lines['LocationInfo']['SERVER_STATUS_LIST'];
        $inLon = $lines['LocationInfo']['LON_POSITION'];
        $inLat = $lines['LocationInfo']['LAT_POSITION'];
        $inCallsign = $lines['LocationInfo']['CALLSIGN'];
        $inFrequency = $lines['LocationInfo']['FREQUENCY'];
        $inTxPower = $lines['LocationInfo']['TX_POWER'];
        $inAntennaGain  = $lines['LocationInfo']['ANTENNA_GAIN'];
        $inAntennaHeight = $lines['LocationInfo']['ANTENNA_HEIGHT'];
        $inAntennaDir = $lines['LocationInfo']['ANTENNA_DIR'];
        $inPath = $lines['LocationInfo']['PATH'];
        $inBeaconInterval = $lines['LocationInfo']['BEACON_INTERVAL'];
        $inComment = $lines['LocationInfo']['COMMENT'];
        
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
        <th width = "380px">Global Information </th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        <table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        
        <tr style = "border: none;">
        <td style = "border: none;">Logics</td>
        <td style = "border: none;"><input type="text" name="inGlobalLogics" style = "width:98%" value="<?php echo $inGlobalLogics;?>"></td>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Config Directory</td>
        <td style = "border: none;"><input type="text" name="inGlobalCfgDir" style = "width:98%" value="<?php echo $inGlobalCfgDir;?>"></td>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Time Format</td>
        <td style = "border: none;"><input type="text" name="inTimeFormat" style = "width:98%" value="<?php echo $inTimeFormat;?>"></td>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Card Channels</td>
        <td style = "border: none;"><input type="text" name="inCardChannels" style = "width:98%" value="<?php echo $inCardChannels;?>"></td>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Card Sample Rate</td>
        <td style = "border: none;"><input type="text" name="inCardSampleRate" style = "width:98%" value="<?php echo $inCardChannels;?>"></td>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Location Info</td>
        <td style = "border: none;"><input type="text" name="inLocationInfo" style = "width:98%" value="<?php echo $inLocationInfo;?>"></td>
        </tr>
        <tr style = "border: none;">
        <td style = "border: none;">Links</td>
        <td style = "border: none;"> <input type="text" name="inLinks" style = "width:98%" value="<?php echo $inLinks;?>"></td>
        </tr>
        </table>

        </td>

<td>
        <button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>

</tr>
</table>

        <table>
        <tr>
        <th width = "380px">Reflector Information</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<td>

<table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">FM Network</td>
        <td style = "border: none;"><input type="text" name="inFmNetwork" style = "width:98%" value="<?php echo $inFmnetwork;?>">
        </td></tr>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Reflector Host</td>
        <td style = "border: none;"><input type="text" name="inReflectorServer" style = "width:98%" value="<?php echo $inReflectorServer;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Reflector Api if used</td>
        <td style = "border: none;"><input type="text" name="inrefApi" style = "width:98%" value="<?php echo $inrefApi;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Reflector Port</td>
        <td style = "border: none;"><input type="text" name="inReflectorPort" style = "width:98%" value="<?php echo $inReflectorPort;?>">
        </td></tr><tr style = "border: none;"> 
        <td style = "border: none;">Callsign</td>
        <td style = "border: none;"><input type="text" name="inRefCallsign" style = "width:98%" value="<?php echo $inRefCallsign;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Authorised Key</td>
        <td style = "border: none;"><input type="password" name="inReflectorAuthKey" style = "width:98%" value="<?php echo $inPassword;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Default TG</td>
        <td style = "border: none;"><input type="text" name="inDefaultTg" style = "width:98%" value="<?php echo $inDefaultTg;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Monitor TGs</td>
        <td style = "border: none;"><input type="text" name="inMonitorTgs" style = "width:98%" value="<?php echo $inMonitorTgs;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">TG select Timeout</td>
        <td style = "border: none;"><input type="text" name="inTgSelectTimeout" style = "width:98%" value="<?php echo $inTgSelectTimeout;?>">
        </td></tr>
        
  <!--      <tr style = "border: none;"> 
        <td style = "border: none;">Reflector TgUri</td>
        <td style = "border: none;"><input type="text" name="inReflectorTgUri" style = "width:98%" value="<?php echo $inReflectorTgUri;?>">
        </td></tr>-->
</table>


</td>
<td> 
	<button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br> 
        <br> & <br><br> ReLoad</button>
</td>
</tr>
</table>


<?php 
if ($system_type=="IS_SIMPLEX"){ include "simplex.php" ;};
if ($system_type=="IS_DUPLEX") { include "repeater.php";};



?>
        <table>
        <tr>
        <th width = "380px">Reflector Links Information</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        
<table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">LinkToReflector</td>
        <td style = "border: none;"><input type="text" name="inConnLogic" style = "width:98%" value="<?php echo $inConnLogic;?>">
        </td></tr>
        </tr>
        </table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>
<table>
        <tr>
        <th width = "380px">Macros Information</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        
<table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D1</td>
        <td style = "border: none;"><input type="text" name="inMD1" style = "width:98%" value="<?php echo $inMD1;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D2</td>
        <td style = "border: none;"><input type="text" name="inMD2" style = "width:98%" value="<?php echo $inMD2;?>">
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D3</td>
        <td style = "border: none;"><input type="text" name="inMD3" style = "width:98%" value="<?php echo $inMD3;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D4</td>
        <td style = "border: none;"><input type="text" name="inMD4" style = "width:98%" value="<?php echo $inMD4;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D5</td>
        <td style = "border: none;"><input type="text" name="inMD5" style = "width:98%" value="<?php echo $inMD5;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D6</td>
        <td style = "border: none;"><input type="text" name="inMD6" style = "width:98%" value="<?php echo $inMD6;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D7</td>
        <td style = "border: none;"><input type="text" name="inMD7" style = "width:98%" value="<?php echo $inMD7;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D8</td>
        <td style = "border: none;"><input type="text" name="inMD8" style = "width:98%" value="<?php echo $inMD8;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D9</td>
        <td style = "border: none;"><input type="text" name="inMD9" style = "width:98%" value="<?php echo $inMD9;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Macro D0</td>
        <td style = "border: none;"><input type="text" name="inMD0" style = "width:98%" value="<?php echo $inMD0;?>">
        </td></tr>
</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>
</tr>
</table>



<table>
        <tr>
        <th width = "380px">Rx1 Information</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        
<table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Rx Type</td>
        <td style = "border: none;"><input type="text" name="inRxType" style = "width:98%" value="<?php echo $inRxType;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Audio Dev</td>
        <td style = "border: none;"><input type="text" name="inRxAudioDev" style = "width:98%" value="<?php echo $inRxAudioDev;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Audio Channel</td>
        <td style = "border: none;"><input type="text" name="inRxAudioChannel" style = "width:98%" value="<?php echo $inRxAudioChannel;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Squelch Detect</td>
        <td style = "border: none;"><input type="text" name="inSql_Det" style = "width:98%" value="<?php echo $inSqlDet;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Squelch Start Delay</td>
        <td style = "border: none;"><input type="text" name="inSqlStartDelay" style = "width:98%" value="<?php echo $inSqlStartDelay;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Squelch Delay</td>
        <td style = "border: none;"><input type="text" name="inSqlDelay   " style = "width:98%" value="<?php echo $inSqlDelay;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Squelch Hangtime</td>
        <td style = "border: none;"><input type="text" name="inSqlHangTime" style = "width:98%" value="<?php echo $inSqlHangTime;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Squelch GPIO Chip</td>
        <td style = "border: none;"><input type="text" name="inSqlChip" style = "width:98%" value="<?php echo $inSqlChip;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Squelch GPIO Line</td>
        <td style = "border: none;"><input type="text" name="inSqlLine" style = "width:98%" value="<?php echo $inSqlLine;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">HID Device</td>
        <td style = "border: none;"><input type="text" name="inRxHidDevice" style = "width:98%" value="<?php echo $inRxHidDevice;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">HID Squelch Pin</td>
        <td style = "border: none;"><input type="text" name="inRxHidPin" style = "width:98%" value="<?php echo $inRxHidPin;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">1750_Muting</td>
        <td style = "border: none;"><input type="text" name="in1750Mute" style = "width:98%" value="<?php echo $in1750Mute;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">DTMF_Muting</td>
        <td style = "border: none;"><input type="text" name="inDTMFMute" style = "width:98%" value="<?php echo $inDTMFMute;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Rx Frequency</td>
        <td style = "border: none;"><input type="text" name="inRxFreq" style = "width:98%" value="<?php echo $inRxFreq;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Modulation</td>
        <td style = "border: none;"><input type="text" name="inMod" style = "width:98%" value="<?php echo $inMod;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Peak Meter</td>
        <td style = "border: none;"><input type="text" name="inRx1PeakMeter" style = "width:98%" value="<?php echo $inRx1PeakMeter;?>">
        </td></tr>


</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>
</tr>
</table>
<table>
        <tr>
        <th width = "380px">Tx1 Information</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        
<table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Tx Type</td>
        <td style = "border: none;"><input type="text" name="inTxType" style = "width:98%" value="<?php echo $inTxType;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Audio Dev</td>
        <td style = "border: none;"><input type="text" name="inTxAudioDev" style = "width:98%" value="<?php echo $inTxAudioDev;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Audio Channel</td>
        <td style = "border: none;"><input type="text" name="inTxAudioChannel" style = "width:98%" value="<?php echo $inTxAudioChannel;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">HID Device</td>
        <td style = "border: none;"><input type="text" name="inTxHidDevice" style = "width:98%" value="<?php echo $inTxHidDevice;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">PTT Type</td>
        <td style = "border: none;"><input type="text" name="inPttType" style = "width:98%" value="<?php echo $inPttType;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">PTT Port</td>
        <td style = "border: none;"><input type="text" name="inPttPort" style = "width:98%" value="<?php echo $inPttPort;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">PTT Pin</td>
        <td style = "border: none;"><input type="text" name="inPttPin" style = "width:98%" value="<?php echo $inPttPin;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">HID PTT Pin</td>
        <td style = "border: none;"><input type="text" name="inHidPttPin" style = "width:98%" value="<?php echo $inHidPttPin;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">PTT Hangtime</td>
        <td style = "border: none;"><input type="text" name="inPttHangtime" style = "width:98%" value="<?php echo $inPttHangtime;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">PTT GPIOD Chip</td>
        <td style = "border: none;"><input type="text" name="inPttGpioChip" style = "width:98%" value="<?php echo $inPttGpioChip;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">PTT GPIOD Line</td>
        <td style = "border: none;"><input type="text" name="inPttGpioLine" style = "width:98%" value="<?php echo $inPttGpioLine;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Timeout</td>
        <td style = "border: none;"><input type="text" name="inTxTimeout" style = "width:98%" value="<?php echo $inTxTimeout;?>">
        </td></tr>
</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>
</tr>
</table>
<table>
        <tr>
        <th width = "380px">Location Information</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        
<table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">APRS Server</td>
        <td style = "border: none;"><input type="text" name="inAprsServerList" style = "width:98%" value="<?php echo $inAprsServerList;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">ECHOLINK Server</td>
        <td style = "border: none;"><input type="text" name="inServerStatusList" style = "width:98%" value="<?php echo $inServerStatusList;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Longitude - Deg.Hrs.Min</td>
        <td style = "border: none;"><input type="text" name="inLon" style = "width:98%" value="<?php echo $inLon;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Latitude - Deg.Hrs.Min</td>
        <td style = "border: none;"><input type="text" name="inLat" style = "width:98%" value="<?php echo $inLat;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Call Sign</td>
        <td style = "border: none;"><input type="text" name="inCallsign" style = "width:98%" value="<?php echo $inCallsign;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Frequency</td>
        <td style = "border: none;"><input type="text" name="inFrequency" style = "width:98%" value="<?php echo $inFrequency;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Power</td>
        <td style = "border: none;"><input type="text" name="inTxPower" style = "width:98%" value="<?php echo $inTxPower;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Antenna Gain</td>
        <td style = "border: none;"><input type="text" name="inAntennaGain" style = "width:98%" value="<?php echo $inAntennaGain;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Antenna Height</td>
        <td style = "border: none;"><input type="text" name="inAntennaHeight" style = "width:98%" value="<?php echo $inAntennaHeight;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Antenna Direction</td>
        <td style = "border: none;"><input type="text" name="inAntennaDir" style = "width:98%" value="<?php echo $inAntennaDir;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Path</td>
        <td style = "border: none;"><input type="text" name="inPath" style = "width:98%" value="<?php echo $inPath;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Beacon Interval</td>
        <td style = "border: none;"><input type="text" name="inBeaconInterval" style = "width:98%" value="<?php echo $inBeaconInterval;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Comment</td>
        <td style = "border: none;"><input type="text" name="inComment" style = "width:98%" value="<?php echo $inComment;?>">
        </td></tr>
</table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>
</tr>
</table>
</form>

<p style = "margin: 0 auto;"></p>
<p style = "margin-bottom:-2px;"></p>

</body>
</html>
