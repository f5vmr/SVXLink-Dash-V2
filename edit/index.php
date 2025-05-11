<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once "../include/config.php";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link href="../css/css.php" type="text/css" rel="stylesheet" />
<!---
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
-->
</head>
<body style = "background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<!---
<script src="web-audio-peak-meter.js"></script>
<center>
<fieldset style = "border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style = "padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
-->
<center>
<h1 id="conf-editor" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Configurator Editor (Expert)
<?php

$file = $_GET['file']; 
if ($file == "log") {
  $file = SVXLOGPATH . SVXLOGPREFIX;
  $log = 1;
} else {
//  $file = SVXCONFPATH . $file; 
  $log = 0;
}
echo "File: " . $file . "</h1>";

if($_SESSION['auth'] === 'AUTHORISED') {
include_once "../include/functions.php";
include_once "../include/config.php";

// Get filename from query parameter
//$file = SVXCONFPATH . $_GET['file']; 
// Read file contents
$content = file_get_contents($file);
//echo $content;
// Display in textarea
echo '<form method="post">';
echo '<textarea name="content" rows="35" cols="120">' . htmlspecialchars($content) . '</textarea><br>';

if (!$log) {
// Save button
  echo '<div style = "text-align:center">Make your changes here</div>';  
  echo '<input type="submit" name="save" value="Save" onclick="window.location.reload(true);">';  
  echo '&nbsp;&nbsp;';
//  echo '<button name="btnSave" type="submit" class="red">Save & ReLoad</button>';
  echo '<input type="submit" name="save_reload" value="Save & ReLoad" onclick="window.location.reload(true);">';  
  echo '&nbsp;&nbsp;';
  echo '<input type="submit" name="cancel" value="Cancel" onclick="window.location.reload(true);">';  
  echo '</form>';

  // Save on submit//
  if ((isset($_POST['save'])) || (isset($_POST['save_reload']))) {

    // Backup file
    $backup_filename = $file . "." . date("YmdHis");
    exec('sudo cp -p ' . $file . ' ' . $backup_filename);
    // file_backup($file);
    // Overwrite with post content 
    $content = str_replace("\r\n","\n",$_POST['content']); 
    file_put_contents($file, $content);
    //file_replace($file);
    if (isset($_POST['save_reload'])) {
      echo "restarting SVXLink ...";
      sleep(1);
      exec('sudo systemctl restart svxlink 2>&1', $screen, $retval);
      if ($retval === 0) {
        echo "SVXLink sucessfull restartet";
      } else {
        echo "SVXLink restart failure, check log";
      }
    }   
  }

}
//echo '<textarea name="content"></textarea>';
//$content = file_get_contents($file);
//displayEditor($content);

}
?>
<!---
</fieldset>
-->
</body>
</html>
