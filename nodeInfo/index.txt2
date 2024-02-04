<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
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
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">

<!--h1 id="edit_info" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Edit Configuration '. $_GET['file']'</h1-->
<?php echo '<h1 id="edit_info" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Edit Configuration ' . $_GET['file'] . '</h1>';?>


<?php
$password = "www-data";
$command = "echo '$password' | sudo -S chmod -R 777 /etc/svxlink/";
exec($command);
exec('sudo chown -R www-data:www-data /etc/svxlink/');

?>
<?php

$file=$_GET['file'];
exec('sudo cp ' . $file . ' ' .$file .'.bak');
$lines = file($file);
echo '<form method="post" enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
echo '<table width=60%>';

foreach ($lines as $line_num => $line) {
    echo '<tr><td contenteditable="true" style="text-align:left"><input type="text" style="width:100%" name="line[]" value="' . htmlspecialchars($line) . '"></td></tr>';
}
echo '</table>';
echo '<input type="submit" value="Click to Save Changes">';
echo '</form>';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = '';
    foreach ($_POST['line'] as $line) {
        $data .= $line . "\n";
    }
    
    $success = file_put_contents($file, $data);
    echo $file . "  " . $data;
    if ($success === false) {
        echo 'Error saving changes to file.';
    } else {
        chown ($file,'www-data');
        exec('sudo systemctl restart svxlink');
        echo 'Changes saved and service restarted.';
    }   
        //exec('sudo chown -R www-data:root /etc/svxlink/');
}
//echo "<meta http-equiv='refresh' content='0'>";
exit();
//Header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']));
//exit(); 
?>
</fieldset>
</body>
</html>
