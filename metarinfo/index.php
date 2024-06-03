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
<h1 id="echolink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">EchoLink Configurator</h1>


<?php
include_once "include/functions.php";

$miConfigFile = '/etc/svxlink/svxlink.d/ModuleMetarInfo.conf';
if (fopen($miConfigFile,'r'))
      {
          $miconfig = parse_ini_file($miConfigFile,true,INI_SCANNER_RAW);
      };
  $module = str_split($miconfig['ModuleMetarInfo']);
  foreach ($module as $key) {
  if ($logics[0] == "[ModuleMetarInfo]") $isMetarInfo = true;
  }

if (isset($_POST['btnSave'])) {
  $retval = null;
  $screen = null;

  $miconfig['ModuleMetarInfo']['MUTE_LOGIC_LINKING'] = $_POST['inMiMuteLogicLinking'];
  $miconfig['ModuleMetarInfo']['SERVER'] = $_POST['inMiServer'];
  $miconfig['ModuleMetarInfo']['LINK'] = $_POST['inMiLink'];
  $miconfig['ModuleMetarInfo']['STARTDEFAULT'] = $_POST['inMiStartDefault'];
  $miconfig['ModuleMetarInfo']['LONGMESSAGES'] = $_POST['inMiLongMessages'];
  $miconfig['ModuleMetarInfo']['REMARKS'] = $_POST['inMiRemarks'];
  $miconfig['ModuleMetarInfo']['AIRPORTS'] = $_POST['inmiAirports'];
  $miconfig['ModuleMetarInfo']['MUTE_LOGIC_LINKING'] = $_POST['inElMuteLogicLinking'];

  $ini = build_ini_string($miconfig);

  //file_put_contents("/var/www/html/test.ini",$ini,FILE_USE_INCLUDE_PAT);
  file_put_contents("/var/www/html/MetarInfo/ModuleMetarInfo.conf", $ini, FILE_USE_INCLUDE_PATH);

  ///file manipulation section

  $retval = null;
  $screen = null;
  //archive the current config
  exec('sudo cp /etc/svxlink/svxlink.d/ModuleMetarInfo.conf /etc/svxlink/svxlink.d/ModuleMetarInfo.conf.' . date("YmdThis"), $screen, $retval);
  //move generated file to current config
  exec('sudo mv /var/www/html/echolink/ModuleMetarInfo.conf /etc/svxlink/svxlink.d/ModuleMetarInfo.conf', $screen, $retval);

  //Service SVXlink restart
  exec('sudo systemctl restart svxlink 2>&1', $screen, $retval);



  //debug
//      echo '<pre>';
  //     print_r($ini);
  //     echo '</pre>';
//end of debug

}


if (fopen($miConfigFile,'r'))
//      {

        $miconfig = parse_ini_file($miConfigFile,true,INI_SCANNER_RAW);
//};

//$svxConfigFile = '/etc/svxlink/svxlink.conf';
//$svxConfigFile = '/var/www/html/svxlink.conf';    






//if (fopen($elConfigFile,'r'))
  //    { 

//	$elconfig = parse_ini_file($elConfigFile,true,INI_SCANNER_RAW);
        
	      $inMiMuteLogicLinking = $miconfig['ModuleMetarInfo']['MUTE_LOGIC_LINKING'];
        $inMiServer = $miconfig['ModuleMetarInfo']['SERVER'];
        $inMiLink = $miconfig['ModuleMetarInfo']['LINK'];
        $inMiStartDefault = $miconfig['ModuleMetarInfo']['STARTDEFAULT'];
        $inMiLongMessages = $miconfig['ModuleMetarInfo']['LONGMESSAGES'];
        $inMiRemarks = $miconfig['ModuleMetarInfo']['REMARKS'];
        $inmiAirports = $miconfig['ModuleMetarInfo']['AIRPORTS'];

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
        <th width = "380px">MetarInfo Input</th>
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
        <td style="border: none;">Muting Logic Linking \(advised\)</td>
        <td style="border: none;"><input type="text" name="inMiMuteLogicLinking" style="width:98%" value="<?php echo $inMiMuteLogicLinking;?>"></td>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Metar Server</td>
        <td style="border: none;"><input type="text" name="inMiServer" style="width:98%" value="<?php echo $inMiServer;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Link Text</td>
        <td style="border: none;"><input type="text" name="inMiLink" style="width:98%" value="<?php echo $inMiLink;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Long Messages</td>
        <td style="border: none;"><input type="text" name="inMiLongMessages" style="width:98%" value="<?php echo $inMiLongMessages;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Default Airport</td>
        <td style="border: none;"><input type="text" name="inMiStartDefault" style="width:98%" value="<?php echo $inMiStartDefault;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Other Airports comma separated</td>
        <td style="border: none;"><input type="text" name="inMiAirports" style="width:98%" value="<?php echo $inmiAirports;?>">
        </td></tr>
        </Table>


</TD>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>

</tr>
</table>




</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
