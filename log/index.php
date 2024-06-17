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
<h1 id="web-audio-peak-meters" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Log viewer</h1>

<?php
// Possible log file names
$logFiles = ['/var/log/svxlink.log', '/var/log/svxlink'];

// Initialize log content variable
$logContent = '';

// Iterate over possible log files and read the first one that exists
foreach ($logFiles as $logFile) {
    if (file_exists($logFile)) {
        $logContent = file_get_contents($logFile);
        break;
    }
}

// Display log content or an error message
//if ($logContent !== '') {
//    echo nl2br($logContent); // nl2br() converts newlines to <br> for HTML
//} else {
//    echo "Log file not found.";
//}
?>

<?php 

//if ($_SESSION['auth'] == 'AUTHORISED'){
//  echo '<iframe height="100%" id="editIframe" src="' . $edit_file . '" width="620px" height="495px" title="EDIT"></iframe>';
//
//  echo '</td>';
// }   else {
//      echo '<h1 id="power" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">You are not yet authorised.</h1>';
//      echo '</td>';    
//  }
  ?>
  <?php
//$retval = null;
//$conns = null;
////exec('nmcli  -t -f NAME  con show',$conns,$retval);
//
//// find the gateway
//$ipgw = null;
//
//$screen[0] = "Welcome to Svxlink log viewer tool.";
//$screen[1] = "";
//$screen[2] = "Click on the button to get the last 30 lines of the log.";
//$screen[3] = "";

//tbc - load the data from ini RF.

//if (isset($_POST['btnLog']))
//    {
//
//        $retval = null;
//        $screen = null;
//        //$sAconn = $_POST['sAconn'];
//        //$password = $_POST['password'];
//        //exec('nmcli dev wifi rescan');
//       $command = "tail -n 30 /var/log/svxlink.log";
//       exec($command,$screen,$retval);
//}

?>



<p style = "margin: 0 auto;"></p>
<p style = "margin-bottom:-2px;"></p>

</body>
</html>
