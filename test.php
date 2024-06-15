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
<h1 id="svxlink" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">SVXLink Configurator</h1>
<body>

<h2>Edit Configuration</h2>
<form method="post">
    <table>
        <thead>
            <tr>
                <th width="75%">Content</th>
                <th width="25%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php display_config($config_lines); ?>
        </tbody>
    </table>
    <br>
    <button type="submit">Save Changes</button>
</form>

</body>
</html>
<?php
// Include the functions
include 'include.config.php';
include 'include/functions.php';

// Path to the configuration file
$file_path = '/etc/svxlink/';
$file_name = 'svxlink.conf';

// Parse the configuration file
$config = parse_config($file_path, $file_name);

// Display the configuration
display_config($config);
// Handle toggle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST['lines'] as $line_number => $line_content) {
      // Update the corresponding line in your configuration data or database
      $stmt = $pdo->prepare("UPDATE config_lines SET content = :content WHERE line_number = :line_number");
      $stmt->execute([
          ':content' => $line_content,
          ':line_number' => $line_number
      ]);
  }
  // Redirect to avoid resubmission on page refresh
  header('Location: ' . $_SERVER['PHP_SELF']);
  exit;
}

// Edit the configuration (Example: uncomment line 10)
//$line_number = 10;
//$new_content = ltrim($config[$line_number]['content'], '#');
//edit_config($config, $line_number, $new_content, false);

// Save the edited configuration
save_config($config, $file_path, $file_name);

// Display the updated configuration
display_config($config);

      
