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

</head>
<body style = "background-color: #e1e1e1;font: 11pt arial, sans-serif;">

<center>
<h1 id="conf-editor" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Configurator Editor (Expert)
<?php
//if ((defined('DL3EL_NOAUTH')) && (strncmp(DL3EL_NOAUTH, "yes", 3) === 0)) {
if (((defined('DL3EL_NOAUTH')) && (DL3EL_NOAUTH === "no")) || ($_SESSION['auth'] === 'AUTHORISED')) {
  // ok, go ahead, set to authorized :-)
  $_SESSION['auth'] = "AUTHORISED";
} else {
    return;
}  

// Get filename from query parameter
$file = $_GET['file']; 
if ($file == "log") {
  $file = SVXLOGPATH . SVXLOGPREFIX;
  $log = 1;
  if (!filesize($file)) {
    $zipfile = SVXLOGPATH . SVXLOGPREFIX . ".1.gz";
    if (file_exists($zipfile)) {
// to getthis working, you have to add 
// svxlink ALL=NOPASSWD: /usr/bin/gunzip
// in the file www-data.sudoers in the dashboards root directory, copy that file to /etc/sudoers.d/svxlink and restart the apache
      exec('sudo gunzip ' . $zipfile,$output,$retval);
      if ($retval === 0) {
        echo "unzip sucessfull:";
        $file = SVXLOGPATH . SVXLOGPREFIX . ".1";
        echo $file;
      } else {
        echo "unzip failure";
      }
    } else {
        $file = SVXLOGPATH . SVXLOGPREFIX . ".1";
    }
  }
} else {
//  $file = SVXCONFPATH . $file; 
  $log = 0;
}
echo "File: " . $file . "</h1>";

echo '<script type="text/javascript">
        function reloadPage() {
            window.location.href = window.location.pathname + "?reloaded=true&file=' . $file .'";
        }
    </script>';

include_once "../include/functions.php";
include_once "../include/config.php";

// Read file contents
$content = file_get_contents($file);

// Display in textarea & edit
echo '<form method="post">';
echo '<textarea name="content" rows="35" cols="72">' . htmlspecialchars($content) . '</textarea><br>';

if (!$log) {
  echo '<div style = "text-align:center">Make your changes here</div>';  
// Save button
  echo '<input type="submit" name="save" value="Save only">';  
  echo '&nbsp;&nbsp;';
// Save &reload button
  echo '<input type="submit" name="save_reload" value="Save & ReLoad">';  
  echo '&nbsp;&nbsp;';
// cancel button
  echo '<input type="submit" name="cancel" value="Cancel">';  
  echo '</form>';

  // Save / Reload on submit//
  if ((isset($_POST['save'])) || (isset($_POST['save_reload']))) {

    // Backup file
    $backup_filename = $file . "." . date("YmdHis");
    exec('sudo cp -p ' . $file . ' ' . $backup_filename);
    // eliminate \r (when editing in a windows browser
    $content = str_replace("\r\n","\n",$_POST['content']); 
    file_put_contents($file, $content);

    echo "<script type='text/javascript'> reloadPage(); </script>";
    if (isset($_POST['save_reload'])) {
  // Reload on submit//
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
?>
</body>
</html>
