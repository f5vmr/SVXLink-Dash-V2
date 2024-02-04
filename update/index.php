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
<h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Updater</h1>


<?php 
ini_set("allow_url_fopen", 1);

$isSimplex = false;
$isRepeater = false;
$svxConfigFile = '/etc/svxlink/svxlink.conf';
if (fopen($svxConfigFile,'r')) {$svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW); }
$logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
foreach ($logics as $key) {
  if ($key == "SimplexLogic") $isSimplex = true;
  if ($key == "RepeaterLogic") $isRepeater = true; 
};
//$tgUri = $svxconfig['ReflectorLogic']['TG_URI'];




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
//exec('nmcli  -t -f NAME  con show',$conns,$retval);

// find the gateway
$ipgw = null;
$screen = null;


$screen[0] = "Welcome to HotSpot Updater.";
$screen[1] = "";
$screen[2] = "Please use buttons for appriopriate acctions.";
$screen[3] = "";
$screen[4] = "";



if ($_SESSION['refresh']){
	$screen =null;
	$command = "tail -n 500 /var/www/html/update/screen.log |tac 2>&1";
        exec($command,$screen,$retval);

	$str = $screen[0];
	if ($str === "###-FINISH-####") {
		$_SESSION['refresh'] = False;
	}  else {
	//else {$_SESSION['refresh'] = False;}
	header("Refresh: 3");
	}
};



if (isset($_POST['btnChkOs']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "sudo nice -n 19 sh check.os.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);
	
	$_SESSION['refresh']=True; header("Refresh: 3");
	//sleep(1);
	//$command = "tail -n 500 /var/www/html/update/screen.log |tac 2>&1";
        //exec($command,$screen,$retval);       

	
}



if (isset($_POST['btnUpdateOs']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "sudo nice -n 19 sh update.os.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);

	$_SESSION['refresh']=True; header("Refresh: 3");



};



if (isset($_POST['btnChkSounds']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "sudo nice -n 19 sh check.sounds.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);

        $_SESSION['refresh']=True; header("Refresh: 3");
        //sleep(1);
        //$command = "tail -n 500 /var/www/html/update/screen.log |tac 2>&1";
        //exec($command,$screen,$retval);


}


if (isset($_POST['btnUpdateSounds']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "sudo nice -n 19 sh update.sounds.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);

        $_SESSION['refresh']=True; header("Refresh: 3");



};


if (isset($_POST['btnChkConfig']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "sudo nice -n 19 sh check.config.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);

        $_SESSION['refresh']=True; header("Refresh: 3");
        //sleep(1);
        //$command = "tail -n 500 /var/www/html/update/screen.log |tac 2>&1";
        //exec($command,$screen,$retval);


}


if (isset($_POST['btnUpdateConfig']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "sudo nice -n 19 sh update.config.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);

        $_SESSION['refresh']=True; header("Refresh: 3");



};


if (isset($_POST['btnChkDashboard']))
    {

        $retval = null;
        $screen = null;
        
	$command = "sudo nice -n 19 sh check.dashboard.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);
        
	$_SESSION['refresh']=True; header("Refresh: 3");
}


if (isset($_POST['btnUpdateDashboard']))
    {

        $retval = null;
        $screen = null;
        
	$command = "sudo cp /var/www/html/update/update.dashboard.sh /opt";
	exec($command,$screen,$retval);
	$command = "sudo nice -n 19 sh /opt/update.dashboard.sh > /var/www/html/update/screen.log 2>&1 &";
        exec($command,$screen,$retval);
        //exec('nmcli dev wifi rescan');
        //$command3 = "sudo wget ".$tgUri." >> /var/www/html/update/screen.log 2>&1";
        //exec($command3,$screen,$retval);
	//if ($retval) {
	//echo "*";
	//$command4 = "sudo mv /var/www/html/tgdb.txt /var/www/html/include/tgdb.php >> /var/www/html/update/screen.log 2>&1";
        //exec($command4,$screen,$retval);
	//}
        //$_SESSION['refresh']=True; header("Refresh: 3");
        $_SESSION['refresh']=True; header("Refresh: 3");

};

if (isset($_POST['btnChkSvxlink']))
    {

        $retval = null;
        $screen = null;
        $command = "sudo nice -n 19 sh check.svxlink.sh > /var/www/html/update/screen.log 2>&1 &"; 
        exec($command,$screen,$retval);
        $_SESSION['refresh']=True; header("Refresh: 3");
}





if (isset($_POST['btnUpdateSvxlink']))
    {

        $retval = null;
        $screen = null;
        $command = "sudo nice -n 19 sh update.svxlink.sh > /var/www/html/update/screen.log 2>&1 &";
        if ($isTetra){ $command = "sudo nice -n 19 sh update.svxlink.tetra.sh > /var/www/html/update/screen.log 2>&1 &";};   
        exec($command,$screen,$retval);

        $_SESSION['refresh']=True; header("Refresh: 3");

};

?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
<DIV style="height:340px">
<table>
	<tr>
	<th>Screen</th> 
	</tr>
<tr>
<Td>
	<textarea name="scan" rows="15" cols="80"><?php 
			echo implode("\n",$screen); ?></textarea>

</td>
</tr>  
</table> 
<table>
        <tr>
        <th>Check versions</th>
        </tr>
<tr>
<Td>
        <button name="btnChkOs" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">OS</button>
        <button name="btnChkSounds" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">Sounds</button>
	<button name="btnChkConfig" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">Config</button>
	<button name="btnChkSvxlink" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">SVXLink</button>
	<button name="btnChkDashboard" type="submit" class="red" style="height:30px; width:90px; font-size:12px;">Dashboard</button>

</td>
</tr>
</table>
<table>
        <tr>
        <th>Upgrade</th>
        </tr>
<tr>
<Td>
        
	<button name="btnUpdateOs" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">OS</button>
        <button name="btnUpdateSounds" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">Sounds</button>
        <button name="btnUpdateConfig" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">Config</button>
        <button name="btnUpdateSvxlink" type="submit" class="red" style="height:30px; width:80px; font-size:12px;">SVXLink</button>
	<button name="btnUpdateDashboard" type="submit" class="red" style="height:30px; width:90px; font-size:12px;">Dashboard</button>
</td>
</tr>
</table>

</DIV>
</form>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
