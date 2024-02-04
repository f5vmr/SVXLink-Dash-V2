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
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">WiFi Configurator</h1>


<?php 



//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//  if (empty($_POST["ssid"])) {
//     echo "Name is required";
//  } else {
//    $ssid = $_POST["ssid"]);
//  }
//}}

$screen[0] = "Welcome to WIFI configuration tool.";
$screen[1] = "";
$screen[2] = "Please use buttons for actions.";
$screen[3] = "[Air Scan],[Conn List],[WiFi Status],[WiFi On] works without parameter.";
$screen[4] = "[Switch to SSID] or [Delete SSID] needs |SSID (network name)|.";
$screen[5] = "[Add Network & Connect] needs |SSID (network name)| & |Password| & wifi network.";
$screen[6] = "";


if (isset($_POST['btnScan']))
    {
        $retval = null;
	$screen = null;
	exec('nmcli dev wifi rescan');
	exec('nmcli dev wifi list 2>&1',$screen,$retval);
	//$screen[$screen.length]="\n";
	$screen[$screen.$length]="Keep in mind the non-standard WIFI antenna.";
}

if (isset($_POST['btnConnList']))
    {
        
	$retval = null;
	$screen = null;
	//exec('nmcli dev wifi rescan');
        exec('nmcli con show --order type 2>&1',$screen,$retval);
}

if (isset($_POST['btnSwitch']))
    {

        $retval = null;
        $screen = null;
        $ssid = $_POST['ssid'];
	//exec('nmcli dev wifi rescan');
        $command = "nmcli dev wifi connect \"" .$ssid. "\" 2>&1"; 
	exec($command,$screen,$retval);
}

if (isset($_POST['btnDelete']))
    {

        $retval = null;
        $screen = null;
        $ssid = $_POST['ssid'];
        //exec('nmcli dev wifi rescan');
        $command = "nmcli con delete \"" .$ssid. "\" 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnAdd']))
    {

        $retval = null;
        $screen = null;
        $ssid = $_POST['ssid'];
        $password = $_POST['password'];
	//exec('nmcli dev wifi rescan');
        $command = "nmcli dev wifi connect \"" .$ssid. "\" password  \"" . $password . "\"  2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnWifiStatus']))
    {

        $retval = null;
        $screen = null;
        //$ssid = $_POST['ssid'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = 'nmcli radio 2>&1';
        exec($command,$screen,$retval);
}


if (isset($_POST['btnWifiOn']))
    {

        $retval = null;
        $screen = null;
        //$ssid = $_POST['ssid'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = 'nmcli radio wifi on 2>&1';
        exec($command,$screen,$retval);
	$command = 'nmcli radio wifi 2>&1';
        exec($command,$screen,$retval);



}


?>
 <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<DIV style="height:150px">
<table>
	<tr>
	<th>Screen</th> 
	</tr>
<tr>
<Td>
	 
	<textarea name="scan" rows="10" cols="80"><?php 
			echo implode("\n",$screen); ?></textarea>
 </td>
</tr>  
</table> 
</DIV>

<table>
        <tr>
        <th width = "110px">Action</th>
        <th width = "380px">Input</th>
	<th width = "110px">Action</th>
        </tr>
<tr>
<Td>
        <button name="btnScan" type="submit" class="red" style="height:30px;width:105px;font-size:12px;">Air Scan</button>
 	<br>
	<button name="btnConnList" type="submit" class="red" style="height:30px; width:105px;font-size:12px;">Conn List</button>
	<BR>
	<button name="btnWifiStatus" type="submit" class="red"  style="height:30px; width:105px; font-size:12px;">WiFi Status</button>
</tD><TD>

SSID (network name): <input type="text" name="ssid" value="<?php echo $ssid;?>">
<BR>
Password: <input type="password" name="password" value="<?php echo $password;?>">
<BR>
<button name="btnAdd" type="submit" class="red" style="height:30px;font-size:12px;">Add Network & Connect</button>
</td>
<td>
        <button name="btnSwitch" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Switch to SSID</button>
        <br>
        <button name="btnDelete" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Delete SSID</button>
	<br>
	<button name="btnWifiOn" type="submit" class="red"  style="height:30px; width:105px; font-size:12px;">WiFi On</button>
</td>
</tr>
</table>

</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
