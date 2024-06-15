<?php
if (session_status() === PHP_SESSION_NONE) {       
    session_start();
}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <script>
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        // Perform AJAX request to execute sudo alsactl store
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'save_volume_settings.php', true);
        xhr.send();
    }
});
</script>

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
<h1 id="svxlink" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Alsamixer Configurator</h1>
<h3 style = "color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Hit escape to save the settings</h3>


<?php
// Command to execute with sudo
$command = 'sudo alsamixer';

// Execute the command using exec
exec($command, $output, $return_var);

// Check if the command executed successfully
if ($return_var === 0) {
    echo "Command executed successfully.";
    // Output any additional output from the command
    if (!empty($output)) {
        echo "<pre>" . implode("\n", $output) . "</pre>";
    }
} else {
    echo "Failed to execute command. Error code: $return_var";
    // Output any error messages from the command
    if (!empty($output)) {
        echo "<pre>" . implode("\n", $output) . "</pre>";
    }
}
?>
