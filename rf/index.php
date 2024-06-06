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
<script src="web-audio-peak-meter.js"></script>
<center>
<fieldset style = "border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style = "padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="web-audio-peak-meters" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">RF Module Configurator</h1>


<?php 



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
//$ipgw = null;

$RfConfFile = '/opt/sa818/sa818.json';

if (fopen($RfConfFile,'r'))
{
        $filedata = file_get_contents($RfConfFile);
        $RfData = json_decode($filedata,true);
       // print_r($RfData);
};


$screen[0] = "Welcome to SA818 RF MODULE configuration tool.";
$screen[1] = "Please use buttons for actions.";
$screen[2] = "Actions are limited to section data only.";
$screen[3] = "";




if (isset($_POST['btnDetect']))
    {

        $retval = null;
        $screen_top = null;
	$screen = null;
	$screen_small = null;
	
        $port = $_POST['port'];

	$command_top = "ls -1 /dev/ttyS* /dev/ttyUSB* 2>&1";
	exec($command_top,$screen_top,$retval);
	
	//print_r($screen_top); 
	//print_r("<br>");
	$retval = null;
	
	$i = 0;
	foreach ($screen_top as $port_test)
	{ 
		$screen[$i] = "Detection for:" .$port_test; 
		$command = "sa818 --port \"" .$port_test. "\" version 2>&1";
        	exec($command,$screen_small,$retval);
		
		//print_r($screen_small);
		if (!$retval)
		{
			$port = $port_test;
			$screen[$i] = $screen[$i] . " BINGO !"; 
		}
		$i = $i+1;
	};

}



if (isset($_POST['btnVersion']))
    {
        $retval = null;
        $screen = null;
        $port = $_POST['port'];
        $command = "sa818 --port \"" .$port. "\" version 2>&1";
	if (!$retval) exec($command,$screen,$retval);
	//if ($retval) echo("NOK");
	if (!$retval) {
		$RfData['port']=$port;
		$jsonRfData = json_encode($RfData);
        	file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/sa818/sa818.json /opt/sa818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo mv /var/www/html/rf/sa818.json /opt/sa818/sa818.json', $screen, $retval);
	}
}



if (isset($_POST['btnRadio']))
    {

        $retval = null;
        $screen = null;
        $port = $_POST['port'];
	$freq = $_POST['freq'];
	$offset = $_POST['offset'];
	$squelch = $_POST['squelch'];
	$ctcss = $_POST['ctcss'];
	$tail = $_POST['tail'];

        $command = "sa818 --port \"" .$port. "\" radio --frequency \"" .$freq. "\" --offset \"" .$offset. "\" --squelch \"" .$squelch. "\" --ctcss \"" .$ctcss. "\" --close-tail \"" .$tail. "\" 2>&1";
        if (!$retval) exec($command,$screen,$retval);

	if (!$retval) {
                $RfData['port']=$port;$RfData['freq']=$freq;$RfData['offset']=$offset;$RfData['squelch']=$squelch;$RfData['ctcss']=$ctcss;$RfData['tail']=$tail;
                $jsonRfData = json_encode($RfData);
                file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/sa818/sa818.json /opt/sa818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo mv /var/www/html/rf/sa818.json /opt/sa818/sa818.json', $screen, $retval);
        }
}

if (isset($_POST['btnFilters']))
    {

	$retval = null;
        $screen = null;
	$port = $_POST['port'];
        $fEmph = $_POST['fEmph'];
        $fLow = $_POST['fLow'];
        $fHigh = $_POST['fHigh'];

        $command = "sa818 --port \"" .$port. "\" filters  --emphasis \"" .$fEmph. "\" --lowpass \"" .$fLow. "\" --highpass \"" .$fHigh. "\" 2>&1";
        if (!$retval) exec($command,$screen,$retval);
	        if (!$retval) {
                $RfData['port']=$port;$RfData['fEmph']=$fEmph; $RfData['fLow']=$fLow;$RfData['fHigh']=$fHigh;
                $jsonRfData = json_encode($RfData);
                file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/sa818/sa818.json /opt/sa818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo mv /var/www/html/rf/sa818.json /opt/sa818/sa818.json', $screen, $retval);
        }

}




if (isset($_POST['btnVol']))
    {
	
	 $retval = null;
        $screen = null;
        $port = $_POST['port'];
        $volume = $_POST['volume'];

        $command = "sa818 --port \"" .$port. "\" volume  --level \"" .$volume. "\" 2>&1";
        if (!$retval) exec($command,$screen,$retval);
                if (!$retval) {
                $RfData['volume']=$volume;
                $jsonRfData = json_encode($RfData);
                file_put_contents("/var/www/html/rf/sa818.json", $jsonRfData ,FILE_USE_INCLUDE_PATH);
                //archive the current config
                exec('sudo cp /opt/sa818/sa818.json /opt/sa818/sa818.json.' .date("YmdThis") ,$screen,$retval);
                //move generated file to current config
                exec('sudo mv /var/www/html/rf/sa818.json /opt/sa818/sa818.json', $screen, $retval);
        }

}


//load json

$port = $RfData['port']; 
$freq = $RfData['freq'];$offset=$RfData['offset'];$ctcss=$RfData['ctcss'];$tail=$RfData['tail'];$squelch=$RfData['squelch'];
$fEmph = $RfData['fEmph'];$fLow=$RfData['fLow'];$fHigh=$RfData['fHigh'];
$volume = $RfData['volume'];


// default section
// port
if ($port === "" || is_null($port)) $port = "/dev/ttyS1";

//radio
if ($freq === "" || is_null($freq)) $freq = "433.5375";
if ($offset === "" || is_null($offset)) $offset = "0.0";
if ($ctcss === "" || is_null($ctcss)) $ctcss = "77.0";
if ($tail === "" || is_null($tail)) $tail = "yes";
if ($squelch === "" || is_null($squelch)) $squelch = "5";

//filter
if ($fEmph === "" || is_null($fEmph)) $fEmph = "no";
if ($fLow === "" || is_null($fLow)) $fLow = "yes";
if ($fHigh === "" || is_null($fHigh)) $fHigh = "yes";

//
if ($volume === "" || is_null($volume)) $volume = "8";


?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<DIV style = "height:77px">
<table>
	<tr>
	<th>Screen</th> 
	</tr>
<tr>
<td>
	<textarea name="scan" rows="4" cols="80"><?php 
			echo implode("\n",$screen); ?></textarea>

</td>



</tr>  
</table> 
</DIV>

<table>
        <tr>
        <th width = "380px">Port</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<td> 
   <button name="btnDetect" type="submit" class="red"style = "height:30px; width:105px; font-size:12px;">Detect</button> 
	Port: <input type "text" name="port" style = "width: 150px" value="<?php echo $port;?>"
</TD>
<td>
<button name="btnVersion" type="submit" class="red" style = "height:30px; width:105px; font-size:12px;">Get Version</button>
</TD>
</tr>
</table>

<table>
        <tr>
        <th width = "380px">Radio</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
   	Freq: <input type "text" name="freq" style = "width: 180px" value="<?php echo $freq;?>">
	Shift: <input type "text" name="offset" style = "width: 50px" value="<?php echo $offset;?>"> <br>
   	Ctcss: <input type "text" name="ctcss" style = "width: 50px" value="<?php echo $ctcss;?>">
	Squelch: <input type "text" name="squelch" style = "width: 50px" value="<?php echo $squelch;?>">
	Tail: <input type "text" name="tail" style = "width: 50px" value="<?php echo $tail;?>">
</TD>
<td>
<button name="btnRadio" type="submit" class="red" style = "height:30px; width:105px; font-size:12px;">Set Radio</button>
</TD>
</tr>
</table>

<table>
        <tr>
        <th width = "380px">Enable Filters</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
   Emphasis: <input type "text" name="fEmph" style = "width: 50px" value="<?php echo $fEmph;?>">
   Low: <input type "text" name="fLow" style = "width: 50px" value="<?php echo $fLow;?>">
   High: <input type "text" name="fHigh" style = "width: 50px" value="<?php echo $fHigh;?>">

</TD>
<td>
<button name="btnFilters" type="submit" class="red" style = "height:30px; width:105px; font-size:12px;">Set Filters</button>
</TD>
</tr>
</table>

<table>
        <tr>
        <th width = "380px">Volume</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
   Volume: <input type "text" name="volume" style = "width: 50px" value="<?php echo $volume;?>">
</TD>
<td>
<button name="btnVol" type="submit" class="red" style = "height:30px; width:105px; font-size:12px;">Set Vol</button>
</TD>
</tr>
</table>

</form>

<p style = "margin: 0 auto;"></p>
<p style = "margin-bottom:-2px;"></p>

</body>
</html>
