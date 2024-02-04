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
<script src="web-audio-peak-meter.js"></script>
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Network Configurator</h1>


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
exec('nmcli  -t -f NAME  con show',$conns,$retval);

// find the gateway
$ipgw = null;

$screen[0] = "Welcome to NETWORK configuration tool.";
$screen[1] = "";
$screen[2] = "Please use buttons for actions.";
$screen[3] = "[Ping GW],[Ping Google],[Ping Reflector] works without parameter.";
$screen[4] = "[Show Details] [Set Auto IP]  [conn UP]  [conn DOWN] works with |connection|.";
$screen[5] = "[Set Static IP] needs |IP|/|CIDR| & |GW| & |DNS|.";
$screen[6] = "Please use 24 for 255.255.255.0 in CIDR. ect.";
$screen[7] = "For |IP| & |GW| & |DNS| please use IP notation like 192.168.1.2 ect.";
$screen[8] = "";
$screen[9] = "";




if (isset($_POST['btnPingGw']))
    {
        $retval = null;
	$screen = null;
	$sAconn = $_POST['sAconn'];
	$ipgw = null;
	//$ipgw_str =implode("\n",$ipgw);
	exec("nmcli -g ipv4.gateway con show \"" .$sAconn. "\" 2>&1",$ipgw,$retval);
	$ipgw_str =implode("\n",$ipgw);
	exec("ping ". $ipgw_str ." -c 1 2>&1",$screen,$retval);
}

if (isset($_POST['btnPingGoogle']))
    {
        
	$retval = null;
	$screen = null;
	//exec('nmcli dev wifi rescan');
        exec('ping 8.8.8.8 -c 1 2>&1',$screen,$retval);
}


//tbc - load the data from ini RF.

if (isset($_POST['btnPingRef']))
    {

        $retval = null;
        $screen = null;
        //$ssid = $_POST['ssid'];
	//exec('nmcli dev wifi rescan');
        $command = 'nmap svxlink.pl -p 5295 2>&1'; 
	exec($command,$screen,$retval);
}


if (isset($_POST['btnAuto']))
    {

        $retval = null;
        $screen = null;
	$sAconn = $_POST['sAconn'];
        //$ssid = $_POST['ssid'];
        //$password = $_POST['password'];
	//exec('nmcli dev wifi rescan');
        //$command = "nmcli radio  2>&1";
	
	$command = "nmcli con mod \"" .$sAconn. "\" ipv4.method auto 2>&1";
        exec($command,$screen,$retval);
	$command = "nmcli -p -f ipv4,general con show \"" .$sAconn. "\" 2>&1";
        exec($command,$screen,$retval);



}


if (isset($_POST['btnStatic']))
    {

        $retval = false;
        $screen = null;
        $sAconn = $_POST['sAconn'];
	$myIp = $_POST['myIp'];
	$cidr = $_POST['cidr'];
	$gw = $_POST['gw'];
	$dns = $_POST['dns'];



	$command = "nmcli con mod \"" .$sAconn. "\" ipv4.addresses " .$myIp. "\/" .$cidr. " 2>&1";
        if (!$retval) exec($command,$screen,$retval);

	$command = "nmcli con mod \"" .$sAconn. "\" ipv4.gateway " .$gw. " 2>&1";
        if (!$retval) exec($command,$screen,$retval);

	$command = "nmcli con mod \"" .$sAconn. "\" ipv4.dns \"" .$dns. "\" 2>&1";
        if (!$retval) exec($command,$screen,$retval);

        $command = "nmcli con mod \"" .$sAconn."\" ipv4.method manual 2>&1";
        if (!$retval) exec($command,$screen,$retval);

        $command = "nmcli -p -f ipv4,general con show \"" .$sAconn. "\" 2>&1";
	if (!$retval) exec($command,$screen,$retval);

}



if (isset($_POST['btnDetails']))
    {

        $retval = null;
        $screen = null;
        $sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "nmcli -p -f ipv4,general con show \"" .$sAconn. "\" 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnUp']))
    {

        $retval = null;
        $screen = null;
        $sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "nmcli con up \"" .$sAconn. "\" 2>&1";
        exec($command,$screen,$retval);
}
if (isset($_POST['btnDown']))
    {

        $retval = null;
        $screen = null;
        $sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "nmcli con down \"" .$sAconn. "\" 2>&1";
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
        <th width = "100px">Action</th>
        <th width = "380px">Input</th>
	<th width = "100px">Action</th>
        </tr>
<tr>
<Td>
	<button name="btnDetails" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Show Details</button>        
	<BR>
	<button name="btnPingGw" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Ping GW</button>
 	<br>
	<button name="btnPingGoogle" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Ping Google</button>
	<br>
        <button name="btnPingRef" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Ping Reflector</button>

</tD><TD>
	connection: 
   <select name="sAconn">
	
<?php
  
foreach ($conns as $conn){
   echo "<option value=\"".$conn ."\">" .$conn."</option>";
}; 
?>

  </select>
	
<br><br>	
	IP: <input type="text" name="myIp" style="width: 150px;" value="<?php echo $myIp;?>">
        /<input type="text" name="cidr" style="width: 50px;" value="<?php echo $cidr;?>">
<BR>
        GW: <input type="text" name="gw" style="width: 120px;" value="<?php echo $gw;?>">
<BR> 
       DNS: <input type="text" name="dns" style="width: 120px;" value="<?php echo $dns;?>">
</td>
<td> 
	<button name="btnAuto" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Set Auto IP</button>
	<BR>
	<button name="btnUp" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">conn UP</button>
	<BR>
	<button name="btnDown" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">conn DOWN</button>
	<BR>
	<button name="btnStatic" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Set Static IP</$

</td>
</tr>
</table>

</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
