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
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="svxlink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">SVXLink Configurator</h1>


<?php

          
        include_once "include/functions.php";


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


        include_once('include/parse_svxconf.php');
        if (isset($_POST['btnSave']))
            {
        $retval = null;
        $screen = null;
              
	$svxconfig['GLOBAL']['LOGICS'] = $_POST['inGlobalLogics'];
        $svxconfig['GLOBAL']['RF_MODULE'] = $_POST['inGlobalRf'];
        $svxconfig['GLOBAL']['CFG_DIR'] = $_POST['inGlobalCfgDir'];
        $svxconfig['GLOBAL']['TIME_FORMAT'] = $_POST['inTimeFormat'];
        $svxconfig['GLOBAL']['CARD_SAMPLE_RATE'] = $_POST['inCardSampleRate'];
        $svxconfig['GLOBAL']['CARD_CHANNELS'] = $_POST['inCardChannels'];
        $svxconfig['GLOBAL']['LOCATION_INFO'] = $_POST['inLocationInfo'];
        $svxconfig['GLOBAL']['LINKS'] = $_POST['inLinks'];
        $svxconfig['GLOBAL']['CW_AMP'] = $_POST['inCwAmp'];
        $svxconfig['GLOBAL']['CW_PITCH'] = $_POST['inCwPitch'];
        $svxconfig['GLOBAL']['CW_CPM'] = $_POST['inCwCpm'];
	$svxconfig['ReflectorLogic']['HOSTS'] = $_POST['inReflectorServer'];
        $svxconfig['ReflectorLogic']['HOST_PORT'] = $_POST['inReflectorPort'];
        $svxconfig['ReflectorLogic']['FMNET'] = $_POST['inReflectorFmnet'];
	$svxconfig['ReflectorLogic']['API'] = $_POST['inReflectorApi'];
        $svxconfig['ReflectorLogic']['DEFAULT_TG'] = $_POST['inDefaultTg'];
        $svxconfig['ReflectorLogic']['TG_SELECT_TIMEOUT'] = $_POST['inTgSelectTimeout'];
        $svxconfig['ReflectorLogic']['MONITOR_TGS'] = $_POST['inMonitorTgs'];
        $svxconfig['ReflectorLogic']['AUTH_KEY'] = $_POST['password'];
        $svxconfig['ReflectorLogic']['CALLSIGN'] = $_POST['inCallsign'];
        $svxconfig['ReflectorLogic']['DEFAULT_LANG'] = $_POST['inReflectorDefaultLang'];
        $svxconfig['ReflectorLogic']['NODE_INFO_FILE'] = $_POST['inReflectorNodeInfoFile'];
        

	//$svxconfig['ReflectorLogic']['TG_URI'] = $_POST['inReflectorTgUri'];

        if ($system_type=="IS_SIMPLEX"){
	$svxconfig['SimplexLogic']['DEFAULT_LANG'] = $_POST['inSimplexDefaultLang'];
        $svxconfig['SimplexLogic']['CALLSIGN'] = $_POST['inSimplexCallsign'];
        $svxconfig['SimplexLogic']['MODULES'] = $_POST['inSimplexModules'];
        $svxconfig['SimplexLogic']['SHORT_IDENT_INTERVAL'] = $_POST['inSimShortInterval'];
        $svxconfig['SimplexLogic']['LONG_IDENT_INTERVAL'] = $_POST['inSimLongInterval'];
        $svxconfig['SimplexLogic']['RGR_SOUND_DELAY'] = $_POST['inRgrDelay'];
        $svxconfig['SimplexLogic']['RGR_SOUND_ALWAYS'] = $_POST['inRgr'];
        $svxconfig['SimplexLogic']['FX_GAIN_NORMAL'] = $_POST['inFxGainNormal'];
        $svxconfig['SimplexLogic']['FX_GAIN_LOW'] = $_POST['inFxGainLow'];
        $svxconfig['SimplexLogic']['ONLINE_CMD'] = $_POST['inOnLineCmd'];
        $svxconfig['SimplexLogic']['ONLINE'] = $_POST['inOnLine'];    
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
        $svxconfig['RepeaterLogic']['RGR_SOUND_DELAY'] = $_POST['inRgrDelay'];
        $svxconfig['RepeaterLogic']['IDLE_SOUND_INTERVAL'] = $_POST['inIdleIntervalTime'];
        $svxconfig['RepeaterLogic']['ONLINE_CMD'] = $_POST['inOnLineCommand'];
        $svxconfig['RepeaterLogic']['ONLINE'] = $_POST['inOnline'];
        $svxconfig['RepeaterLogic']['FX_GAIN_NORMAL'] = $_POST['inFxGainNormal'];
        $svxconfig['RepeaterLogic']['FX_GAIN_LOW'] = $_POST['inFxGainLow'];
        };
        $svxconfig['LinkToReflector']['CONNECT_LOGICS'] = $_POST['inConnLogic'];
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
        $svxconfig['Rx1']['TYPE'] = $_POST['inRxType'];
        $svxconfig['Rx1']['AUDIO_DEV'] = $_POST['inRxAudioDev'];
        $svxconfig['Rx1']['AUDIO_CHANNEL'] = $_POST['inRxAudioChan'];
        $svxconfig['Rx1']['SQL_DET'] = $_POST['inSql_Det'];
        $svxconfig['Rx1']['SQL_START_DELAY'] = $_POST['inSqlStartDelay'];
        $svxconfig['Rx1']['SQL_DELAY'] = $_POST['inSqlDelay'];
        $svxconfig['Rx1']['SQL_HANG_TIME'] = $_POST['inSqlHangTime'];
        $svxconfig['Rx1']['SQL_GPIOD_CHIP'] = $_POST['inSqlChip'];
        $svxconfig['Rx1']['SQL_GPIOD_LINE'] = $_POST['inSqlLine'];
        $svxconfig['Rx1']['HID_DEVICE'] = $_POST['inRxHIDDevice'];
        $svxconfig['Rx1']['HID_SQL_PIN'] = $_POST['inRxHIDPin'];
        $svxconfig['Rx1']['SQL_TAIL_ELIM'] = $_POST['inSqlTail'];
        $svxconfig['Rx1']['1750_MUTING'] = $_POST['in1750Mute'];
        $svxconfig['Rx1']['DTMF_MUTING'] = $_POST['inDTMFMute'];
        $svxconfig['Rx1']['FQ'] = $_POST['inRxFreq'];
        $svxconfig['Rx1']['MODULATION'] = $POST['inMod'];
	$svxconfig['Rx1']['PEAK_METER'] = $_POST['inRx1PeakMeter'];
        $svxconfig['Tx1']['TYPE'] = $_POST['inTxType'];
        $svxconfig['Tx1']['AUDIO_DEV'] = $_POST['inTxAudioDev'];
        $svxconfig['Tx1']['AUDIO_CHANNEL'] = $_POST['inTxAudioChannel'];
        $svxconfig['Tx1']['PTT_TYPE'] = $_POST['inPttType'];
        $svxconfig['Tx1']['PTT_PORT'] = $_POST['inPttPort'];
        $svxconfig['Tx1']['HID_DEVICE'] = $_POST['inTxHidDevice'];
        $svxconfig['Tx1']['PTT_PIN'] = $_POST['inPttPin'];
        $svxconfig['Tx1']['HID_PTT_PIN'] = $_POST['inHidPttPin'];
        $svxconfig['Tx1']['PTT_HANGTIME'] = $_POST['inPttHangtime'];
        $svxconfig['Tx1']['PTT_GPIOD_CHIP'] = $_POST['inPttGPIOChip'];
        $svxconfig['Tx1']['PTT_GPIOD_LINE'] = $_POST['inPttGPIOLine'];
        $svxconfig['Tx1']['TIMEOUT'] = $_POST['inTxTimeout'];
        $svxconfig['MultiTx']['TRANSMITTERS'] = $_POST['inMultiTx'];
        $svxconfig['TxStream']['AUDIO_DEV'] = $_POST['inTxStreamAudioDev'];
        $svxconfig['NetRx']['HOST'] = $_POST['inNetRxHost'];
        $svxconfig['NetRx']['PORT'] = $_POST['inNetRxPort'];
        $svxconfig['NetRx']['AUTH_KEY'] = $_POST['inNetRxAuthKey'];
        $svxconfig['NetTx']['HOST'] = $_POST['inNetTxHost'];
        $svxconfig['NetTx']['PORT'] = $_POST['inNetTxPort'];
        $svxconfig['NetTx']['AUTH_KEY'] = $_POST['inNetTxAuthKey'];
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
        exec('sudo systemctl restart svxlink 2>&1',$screen,$retval);



        // debug
              echo '<pre>';
              print_r($ini);

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
        $inCardSampleRate = $svxconfig['GLOBAL']['CARD_SAMPLE_RATE'];
        $inCardChannels = $svxconfig['GLOBAL']['CARD_CHANNELS'];
        $inLocationInfo = $svxconfig['GLOBAL']['LOCATION_INFO'];
        $inLinks = $svxconfig['GLOBAL']['LINKS'];
        $inCwAmp = $svxconfig['GLOBAL']['CW_AMP'];
        $inCwPitch = $svxconfig['GLOBAL']['CW_PITCH'];
        $inCwCpm = $svxconfig['GLOBAL']['CW_CPM'];
	$inReflectorServer = $svxconfig['ReflectorLogic']['HOSTS'];
	$inReflectorPort = $svxconfig['ReflectorLogic']['HOST_PORT'];
        $inReflectorFmnet = $svxconfig['ReflectorLogic']['FMNET'];
        $inReflectorApi = $svxconfig['ReflectorLogic']['API'];
	$inDefaultTg = $svxconfig['ReflectorLogic']['DEFAULT_TG'];
	$inTgSelectTimeout = $svxconfig['ReflectorLogic']['TG_SELECT_TIMEOUT'];
        $inMonitorTgs = $svxconfig['ReflectorLogic']['MONITOR_TGS'];
	$inPassword = $svxconfig['ReflectorLogic']['AUTH_KEY'];
        $inCallsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
        $inReflectorDefaultLang = $svxconfig['ReflectorLogic']['DEFAULT_LANG'];
	$inReflectorNodeInfoFile = $svxconfig['ReflectorLogic']['NODE_INFO_FILE'];
        $inConnLogic = $svxconfig['ReflectorLogic']['CONNECT_LOGICS'];
        $inRxType = $svxconfig['Rx1']['TYPE'];
        $inRxAudioDev = $svxconfig['Rx1']['AUDIO_DEV'];
        $inRxAudioChannel = $svxconfig['Rx1']['AUDIO_CHANNEL'];
        $inSqlDet = $svxconfig['Rx1']['SQL_DET'];
        $inSqlStartDelay = $svxconfig['Rx1']['SQL_START_DELAY'];
        $inSqlDelay = $svxconfig['Rx1']['SQL_DELAY'];
        $inSqlHangTime = $svxconfig['Rx1']['SQL_HANG_TIME'];
        $inSqlGPIOChip = $svxconfig['Rx1']['SQL_GPIO_CHIP'];
        $inSqlGPIOLine = $svxconfig['Rx1']['SQL_GPIO_LINE'];
        $inRxHIDDevice = $svxconfig['Rx1']['HID_DEVICE'];
        $inRxHIDPin = $svxconfig['Rx1']['HID_PIN'];
        $in1750Mute = $svxconfig['Rx1']['1750_MUTING'];
        $inDTMFMute = $svxconfig['Rx1']['DTMF_MUTING'];
        $inRxFreq = $svxconfig['Rx1']['FREQ'];
        $inMod = $svxconfig['Rx1']['MOD'];
        $inRx1PeakMeter = $svxconfig['Rx1']['PEAK_METER'];
        $inTxType = $svxconfig['Tx1']['TYPE'];
        $inTxAudioDev = $svxconfig['Tx1']['AUDIO_DEV'];
        $inTxAudioChannel = $svxconfig['Tx1']['AUDIO_CHANNEL'];
        $inTxHidDevice = $svxconfig['Tx1']['HID_DEVICE'];
        $inPttType = $svxconfig['Tx1']['PTT_TYPE'];
        $inPttPort = $svxconfig['Tx1']['PTT_PORT'];
        $inPttPin = $svxconfig['Tx1']['PTT_PIN'];
        $inHidPttPin = $svxconfig['Tx1']['HID_PTT_PIN'];
        $inPttHangTime = $svxconfig['Tx1']['PTT_HANG_TIME'];
        $inPttGPIOChip = $svxconfig['Tx1']['PTT_GPIO_CHIP'];
        $inPttGPIOLine = $svxconfig['Tx1']['PTT_GPIO_LINE'];
        $inTxTimeout = $svxconfig['Tx1']['TIMEOUT'];





        //$inReflectorTgUri = $svxconfig['ReflectorLogic']['TG_URI'];

        if ($system_type=="IS_SIMPLEX"){ 
	$inSimplexDefaultLang = $svxconfig['SimplexLogic']['DEFAULT_LANG'];
        $inSimplexCallsign = $svxconfig['SimplexLogic']['CALLSIGN'];
	$inSimplexModules = $svxconfig['SimplexLogic']['MODULES'];
        $inSimShortInterval = $svxconfig['SimplexLogic']['SHORT_IDENT_INTERVAL'];
        $inSimLongInterval = $svxconfig['SimplexLogic']['LONG_IDENT_INTERVAL'];
        $inRgrDelay = $svxconfig['SimplexLogic']['RGR_SOUND_DELAY'];
        $inRgr = $svxconfig['SimplexLogic']['RGR_SOUND_ALWAYS'];
        $inFxGainNormal = $svxconfig['SimplexLogic']['FX_GAIN_NORMAL'];
        $inFxGainLow = $svxconfig['SimplexLogic']['FX_GAIN_LOW'];
        $inOnLineCmd = $svxconfig['SimplexLogic']['ONLINE_CMD'];
        $inOnLine = $svxconfig['SimplexLogic']['ONLINE'];
        };
        if ($system_type=="IS_DUPLEX"){
        $inRepeaterDefaultLang = $svxconfig['RepeaterLogic']['DEFAULT_LANG'];
        $inRepeaterCallsign = $svxconfig['RepeaterLogic']['CALLSIGN'];
        $inRepeaterModules = $svxconfig['RepeaterLogic']['MODULES'];
        $inRepShortInterval = $svxconfig['RepeaterLogic']['SHORT_IDENT_INTERVAL'];
        $inRepLongInterval = $svxconfig['RepeaterLogic']['LONG_IDENT_INTERVAL'];
        $inIdleTime = $svxconfig['RepeaterLogic']['IDLE_TIMEOUT'];
        $in1750 = $svxconfig['RepeaterLogic']['OPEN_ON_1750'];
        $inCTCSS = $svxconfig['RepeaterLogic']['OPEN_ON_CTCSS'];
        $inSqlFlank = $svxconfig['RepeaterLogic']['OPEN_SQL_FLANK'];
        $inIdleIntervalTime = $svxconfig['RepeaterLogic']['IDLE_SOUND_INTERVAL'];
        $inOnLineCommand = $svxconfig['RepeaterLogic']['ONLINE_CMD'];
        $inOnLine = $svxconfig['RepeaterLogic']['ONLINE'];
        $inFxGainNormal = $svxconfig['RepeaterLogic']['FX_GAIN_NORMAL'];
        $inFxGainLow = $svxconfig['RepeaterLogic']['FX_GAIN_LOW'];
        
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
        <th width = "380px">Global Information</th>
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
        <td style="border: none;">Card Sample Rate</td>
        <td style="border: none;"><input type="text" name="inCardSampleRate" style="width:98%" value="<?php echo $inCardChannels;?>"></td>
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
        <th width = "380px">Reflector Information</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<td>

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
        <td style="border: none;">Authorised Key</td>
        <td style="border: none;"><input type="password" name="inReflectorAuthKey" style="width:98%" value="<?php echo $inPassword;?>">
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
        <td style="border: none;">TG select Timeout</td>
        <td style="border: none;"><input type="text" name="inTgSelectTimeout" style="width:98%" value="<?php echo $inTgSelectTimeout;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Reflector Host</td>
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
  <!--      <tr style="border: none;"> 
        <td style="border: none;">Reflector TgUri</td>
        <td style="border: none;"><input type="text" name="inReflectorTgUri" style="width:98%" value="<?php echo $inReflectorTgUri;?>">
        </td></tr>-->
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



?>

<table>
        <tr>
        <th width = "380px">Macros Information</th>
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
        <th width = "380px">Rx1 Information</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        
<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Rx Type</td>
        <td style="border: none;"><input type="text" name="inRxType" style="width:98%" value="<?php echo $inRxType;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Audio Dev</td>
        <td style="border: none;"><input type="text" name="inRxAudioDev" style="width:98%" value="<?php echo $inRxAudioDev;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Audio Channel</td>
        <td style="border: none;"><input type="text" name="inRxAudioChannel" style="width:98%" value="<?php echo $inRxAudioChannel;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Sql Detect</td>
        <td style="border: none;"><input type="text" name="inSql_Det" style="width:98%" value="<?php echo $inSqlDet;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Sql Start Delay</td>
        <td style="border: none;"><input type="text" name="inSqlStartDelay" style="width:98%" value="<?php echo $inSqlStartDelay;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Sql_Delay</td>
        <td style="border: none;"><input type="text" name="inSqlDelay   " style="width:98%" value="<?php echo $inSqlDelay;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Sql_Hangtime</td>
        <td style="border: none;"><input type="text" name="inSqlHangTime" style="width:98%" value="<?php echo $inSqlHangTime;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Sql_GPIOD_Chip</td>
        <td style="border: none;"><input type="text" name="inSqlChip" style="width:98%" value="<?php echo $inSqlChip;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Sql_GPIOD_Line</td>
        <td style="border: none;"><input type="text" name="inSqlLine" style="width:98%" value="<?php echo $inSqlLine;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">HID_Device</td>
        <td style="border: none;"><input type="text" name="inRxHIDDevice" style="width:98%" value="<?php echo $inRxHIDDevice;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">HID_Sql_Pin</td>
        <td style="border: none;"><input type="text" name="inRxHIDPin" style="width:98%" value="<?php echo $inRxHIDPin;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">1750_Muting</td>
        <td style="border: none;"><input type="text" name="in1750Mute" style="width:98%" value="<?php echo $in1750Mute;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">DTMF_Muting</td>
        <td style="border: none;"><input type="text" name="inDTMFMute" style="width:98%" value="<?php echo $inDTMFMute;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Rx Frequency</td>
        <td style="border: none;"><input type="text" name="inRxFreq" style="width:98%" value="<?php echo $inRxFreq;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Modulation</td>
        <td style="border: none;"><input type="text" name="inMod" style="width:98%" value="<?php echo $inMod;?>">
        </td></tr>
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
<table>
        <tr>
        <th width = "380px">Tx1 Information</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        
<table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Tx Type</td>
        <td style="border: none;"><input type="text" name="inTxType" style="width:98%" value="<?php echo $inTxType;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Audio Dev</td>
        <td style="border: none;"><input type="text" name="inTxAudioDev" style="width:98%" value="<?php echo $inTxAudioDev;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Audio Channel</td>
        <td style="border: none;"><input type="text" name="inTxAudioChannel" style="width:98%" value="<?php echo $inTxAudioChannel;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">HID Device</td>
        <td style="border: none;"><input type="text" name="inTxHidDevice" style="width:98%" value="<?php echo $inTxHidDevice;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">PTT Type</td>
        <td style="border: none;"><input type="text" name="inPttType" style="width:98%" value="<?php echo $inPttType;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">PTT Port</td>
        <td style="border: none;"><input type="text" name="inPttPort" style="width:98%" value="<?php echo $inPttPort;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">PTT Pin</td>
        <td style="border: none;"><input type="text" name="inPttPin" style="width:98%" value="<?php echo $inPttPin;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">HID PTT Pin</td>
        <td style="border: none;"><input type="text" name="inHidPttPin" style="width:98%" value="<?php echo $inHidPttPin;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">PTT Hangtime</td>
        <td style="border: none;"><input type="text" name="inPttHangtime" style="width:98%" value="<?php echo $inPttHangtime;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">PTT GPIOD Chip</td>
        <td style="border: none;"><input type="text" name="inPttGpioChip" style="width:98%" value="<?php echo $inPttGpioChip;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">PTT GPIOD Line</td>
        <td style="border: none;"><input type="text" name="inPttGpioLine" style="width:98%" value="<?php echo $inPttGpioLine;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Timeout</td>
        <td style="border: none;"><input type="text" name="inTxTimeout" style="width:98%" value="<?php echo $inTxTimeout;?>">
        </td></tr>
</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
