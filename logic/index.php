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
<h1 id="svxlink" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Logic Configurator</h1>
<h3 style = "color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Use with Extreme Caution</h3>


<?php

          
        include_once "../include/functions.php";
        $directory="/usr/share/svxlink/events.d/local/";
        $svxConfigFile = 'Logic.tcl';
        $file = '/usr/share/svxlink/events.d/local/Logic.tcl';
        $owner = 'svxlink';
        $group = 'svxlink';



// Command to change ownership using sudo
$command = "sudo chown $owner:$group " . escapeshellarg($file);

// Execute the command
$output = [];
$return_var = 0;
exec($command, $output, $return_var);

// Check if the command executed successfully
if ($return_var === 0) {
 //   echo "Ownership of $file changed to $owner:$group successfully.";
} else {
//    echo "Failed to change ownership of $file. Error code: $return_var";
 //   echo "<pre>" . implode("\n", $output) . "</pre>";
}
        file_backup($directory,$svxConfigFile);
        $config = $directory.$svxConfigFile;
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
//
if (file_exists($config)) {
        $svxconfig = custom_parse_ini_file($config);
    } else {
        die("File not found: $config");
    }
    
    // Debugging: Output parsed data
    //var_dump($svxconfig);
    
    // Access and manipulate configuration
  // Display form with checkboxes and editable values
echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
echo '<table>';
echo '<tr><th>Command</th><th>Active</th><th>Value</th></tr>';

foreach ($svxconfig as $section => $entries) {
        echo "<tr><td colspan='3'><h2 id=\"svxlink\" style=\"color:#00aee8;font: 14pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;\">$section</h2></td></tr>\n";

    foreach ($entries as $key => $data) {
        // Determine checkbox state based on 'active' flag
        $checked = $data['active'] ? 'checked' : '';
        
        // Display the checkbox, key (command), and value (editable input)
        echo "<tr>";
        echo "<td style='width: 15%'>$key</td>";
        echo "<td style='width: 10%'><input type='checkbox' name='active[$section][$key]' value='1' $checked></td>";
        echo "<td style='width: 75%'><input type='text' name='value[$section][$key]' style='width: 98%' value='{$data['value']}'></td>";
        echo "</tr>\n";
    }
}

echo '</table>';
echo '<button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br> & <br> ReLoad</button>';
echo '</form>'; 
if (isset($_POST['btnSave'])) {
        
        save_svxconfig($config, $_POST);
        $screen=null;
        $retval=null;
        exec('sudo systemctl restart svxlink 2>&1',$screen,$retval);
    }

 
