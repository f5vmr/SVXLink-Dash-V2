<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link href="../css/css.php" type="text/css" rel="stylesheet" />
</head>
<body style = "background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style = "border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style = "padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="power" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Power</h1>

<?php


if (((defined('DL3EL_NOAUTH')) && (DL3EL_NOAUTH === "YES")) || ($_SESSION['auth'] === 'AUTHORISED')) {

include_once  '../include/config.php';
include_once  '../include/functions.php';


// load the connlist
$retval = null;
$conns = null;
// find the gateway


if (isset($_POST['btnPower']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('sudo nmcli dev wifi rescan');
        $command = "sudo shutdown -h now 2>&1";
        exec($command,$screen,$retval);
}

//if (isset($_POST['btnLcd']))
//    {
//
//        $retval = null;
//        $screen = null;
//        //$sAconn = $_POST['sAconn'];
//        //$password = $_POST['password'];
//        //exec('sudo nmcli dev wifi rescan');
//        $command = "sudo systemctl restart oled2svx  2>&1";
//        exec($command,$screen,$retval);
//}

if (isset($_POST['btnSvxlink']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('sudo nmcli dev wifi rescan');
        $command = "sudo systemctl restart svxlink 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnSvxlinkoff']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('sudo nmcli dev wifi rescan');
        $command = "sudo systemctl stop svxlink 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnRestart']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('sudo nmcli dev wifi rescan');
        $command = "sudo shutdown -r now 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnrstshari']))
    {
// wichttig: damit das funktioniert, muss mit visudo folgendes eingetragen werden
// svxlink        ALL=(ALL) NOPASSWD: /usr/sbin/alsactl

        $retval = null;
        $screen = null;
        $command = "/home/svxlink/dl3el/shari-arestore.sh  2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btnDashUpdate']))
    {
        $file = DL3EL_BASE .'git_pull.sh';
        $log = DL3EL_BASE .'git_pull.log';
        $gitdir = substr(DL3EL_BASE,0,strlen(DL3EL_BASE)-1);
        $owner = 'svxlink';
        $group = 'svxlink';

        $command = "sudo chown $owner:$group " . escapeshellarg($file) . " >" . $log . " 2>&1";
        $output = [];
        $return_var = 0;
        exec($command, $output, $return_var);
        $retval = null;
        $screen = null;
        $command = "sudo chmod g+x " . $file . " >>" . $log . " 2>&1";
        exec($command,$output,$retval);
        $command = $file . " " . $gitdir . " >>" . $log . " 2>&1";
        exec($command,$output,$retval);
        $content = file_get_contents($log);
        exec("find " . DL3EL_BASE . "* ! -exec sudo chown $owner:$group {} +");
        // Display in textarea           
        echo '<textarea name="content" rows="35" cols="72">' . htmlspecialchars($content) . '</textarea><br>';

}

if (isset($_POST['btnrstc710']))
    {
        $retval = null;
        $screen = null;
        $command = "/home/svxlink/dl3el/c710-arestore.sh  2>&1";
        exec($command,$screen,$retval);
}

} else {
  echo '<h1 id="power" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">You are not authorised to make changes here.</h1>';
 
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
	
	<button name="btnSvxlinkoff" type="submit" class="red" style = "height:30px; width:400px; font-size:12px;">Stop SVXlink Service</button>
	<br>
	<button name="btnSvxlink" type="submit" class="red" style = "height:30px; width:400px; font-size:12px;">Restart SVXlink Service</button>
	<br>
	<!--button name="btnLcd" type="submit" class="red" style = "height:30px; width:400px; font-size:12px;">Restart Lcd Service</button>
	<BR-->
	<button name="btnRestart" type="submit" class="red" style = "height:30px; width:400px; font-size:12px;">Restart Device</button>
        <br>
	<button name="btnPower" type="submit" class="red" style = "height:30px; width:400px; font-size:12px;">Power OFF</button>
<?php
   if (defined('DL3EL_RADIO') && (strncmp(DL3EL_VERSION, "develop", 7) === 0)) {
      $svxRadio = DL3EL_RADIO;
      if ($svxRadio == "Shari") {
        echo '<br><br><br>';
        echo '<button name="btnrstshari" type="submit" class="green" style = "height:30px; width:400px; font-size:12px;">Reset Sound Shari</button>';
      }    
      if ($svxRadio == "C710") {
        echo '<br><br><br>';
        echo '<button name="btnrstc710" type="submit" class="green" style = "height:30px; width:400px; font-size:12px;">Reset Sound C710</button>';
      }    
   }   
  if (((defined('DL3EL_BASE')) && (file_exists(DL3EL_BASE.'git_pull.sh'))) && ((defined('DL3EL_GIT_UPDATE')) && (DL3EL_GIT_UPDATE === "yes"))) {
      echo '<br><br><br>';
      echo '<button name="btnDashUpdate" type="submit" class="green" style = "height:30px; width:400px; font-size:12px;">Dashboard Update (GitHub)</button>';
  }

?>   
</form>

<p style = "margin: 0 auto;"></p>
<p style = "margin-bottom:-2px;"></p>

</body>
</html>
