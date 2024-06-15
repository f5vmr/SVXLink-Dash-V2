<br?php
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
<h1 id="svxlink" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Audio Configurator</h1>
<h3 style = "color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">AMixer settings</h3>
<!-- HTML Form to adjust ALSA settings -->

    
<h3 style = "color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Headphone - TX</h3>
    <label for="headphone">Set for 65 (0-100):</label>
    <input type="number" id="headphone" name="headphone" min="0" max="100" required>
</br>
<h3 style = "color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Microphone - Not Used</h3>

    <label for="mic">(0-100): Set to 0</label>
    <input type="number" id="mic" name="mic" min="0" max="100" required>
</br>
<h3 style = "color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Audio Capture - RX</h3>

    <label for="capture">(0-100) Set for 25:</label>
    <input type="number" id="capture" name="capture" min="0" max="100" required>
</br>
<h3 style = "color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Auto Gain</h3>

    <label for="autogain">Set to Off for optimum control</label>
    <select id="autogain" name="autogain" required>
        <option value="off">Off</option>
        <option value="on">On</option>
    </select>
</br>   
    <button type="submit">Apply Settings</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['headphone'])) {
        $headphone = intval($_POST['headphone']);
        if ($headphone >= 0 && $headphone <= 100) {
            exec("sudo amixer set 'Headphone' " . escapeshellarg($headphone) . "%");
        }
    }

    if (isset($_POST['mic'])) {
        $mic = intval($_POST['mic']);
        if ($mic >= 0 && $mic <= 100) {
            exec("sudo amixer set 'Mic' " . escapeshellarg($mic) . "%");
        }
    }

    if (isset($_POST['capture'])) {
        $capture = intval($_POST['capture']);
        if ($capture >= 0 && $capture <= 100) {
            exec("sudo amixer set 'Capture' " . escapeshellarg($capture) . "%");
        }
    }

    if (isset($_POST['autogain'])) {
        $autogain = $_POST['autogain'] === 'on' ? 'on' : 'off';
        exec("sudo amixer set 'Auto Gain Control' " . escapeshellarg($autogain));
    }
}
?>


