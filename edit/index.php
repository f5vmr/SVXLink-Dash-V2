<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
  <head>
    <meta charset="UTF-8">
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
<style type="text/css">
body {
  height: 100%;
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
.button {
  border: none;
  color: #454545;
  padding: 16px 32px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
}
.buttonh {
  background-image: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);color:#454545;
  color: #454545;
}

.buttonh:hover {
  background-color: #4CAF50;
  color: #454545;
}
.green
{
  background-color: #448f47;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;

}

.blue
{
  background-image: linear-gradient(to bottom, #337ab7 0%, #265a88 100%);color:#454545;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 16px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
  height:80px;
  width:150px;
}

.red
{
  background-color: #b00;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
}
.orange
{
  background-color: DarkOrange;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
}
.purple
{
  background-color: #800080;
  border: none;
  color: white;
  font-weight: 600;
  font-size: 13px;
  padding: 4px 12px;
  text-decoration: none;
  margin: 4px 4px;
  cursor: pointer;
  border-radius: 4px;
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
<body style="background-color: #e1e1e1;font: 12pt arial, sans-serif;">
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 5px #999; background-color:#f1f1f1; width:550px;margin-top:5px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.5;white-space:normal;">

<!--h1 id="edit_info" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Edit Configuration '. $_GET['file']'</h1-->
<?php
if($_SESSION['auth'] === 'AUTHORISED') {
include_once "../include/functions.php";
include_once "../include/config.php";


echo '<h1 id="edit_info" style="color:#00aee8;font: 14pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Editing ' . $_GET['file'] . '</h1>';
include_once "../include/config.php";
// Get filename from query parameter
$file = $_GET['file']; 

// Read file contents

$content = file_get_contents($file);

// Display in textarea
echo '<form method="post">';
echo '<textarea name="content" rows="33" cols="75">' . htmlspecialchars($content) . '</textarea><br>';
echo '<div style="text-align:center">Make your changes here</div>';  

// Save button
echo '<input type="submit" name="save" value="Save" onclick="refreshIframe()">';  
echo '</form>';

// Save on submit//
if(isset($_POST['save'])) {

  // Backup file
  // exec('sudo cp ' . $file . ' ' . $file . '.bak');
  // file_backup($file);
  // Overwrite with post content 
  file_put_contents($file, $_POST['content']);
  //file_replace($file);
}
//echo '<textarea name="content"></textarea>';
//$content = file_get_contents($file);
//displayEditor($content);

} else {echo '<h1 id="edit_info" style="color:#00aee8;font: 14pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">You are not authorised to edit this file</h1>';
}
?>
</fieldset>
</body>
</html>
