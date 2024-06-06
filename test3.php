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
<h1 id="svxlink" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Node Info Configurator</h1>

<?php
$filename = '/etc/svxlink/node_info.json';
$backup_dir = '/var/www/html/backups/';

// Ensure backup directory exists
if (!file_exists($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

function get_form_data($key, $default = '') {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the file content again
    $json_content = file_get_contents($filename);
    $data = json_decode($json_content, true);

    // Update the data with posted values
    $data['nodeLocation'] = get_form_data('nodeLocation');
    $data['hidden'] = isset($_POST['hidden']) ? true : false;
    $data['sysop'] = get_form_data('sysop');

    $data['qth'][0]['name'] = get_form_data('qth_name');
    $data['qth'][0]['pos']['lat'] = get_form_data('qth_lat');
    $data['qth'][0]['pos']['long'] = get_form_data('qth_long');
    $data['qth'][0]['pos']['loc'] = get_form_data('qth_loc');
    $data['qth'][0]['rx']['K']['name'] = get_form_data('rx_name');
    $data['qth'][0]['rx']['K']['freq'] = filter_input(INPUT_POST, 'rx_freq', FILTER_VALIDATE_FLOAT);
    $data['qth'][0]['rx']['K']['sqlType'] = get_form_data('rx_sqlType');
    $data['qth'][0]['tx']['K']['name'] = get_form_data('tx_name');
    $data['qth'][0]['tx']['K']['freq'] = filter_input(INPUT_POST, 'tx_freq', FILTER_VALIDATE_FLOAT);
    $data['qth'][0]['tx']['K']['pwr'] = get_form_data('tx_pwr');

    // Encode the updated array back to JSON
    $new_json_content = json_encode($data, JSON_PRETTY_PRINT);

    // Create a backup
    $backup_filename = $backup_dir . 'node_info_backup_' . date('YmdHis') . '.json';
    if (copy($filename, $backup_filename)) {
        if (file_put_contents($filename, $new_json_content) === false) {
            $message = 'Error saving file';
        } else {
            $message = 'File saved successfully';
        }
    } else {
        $message = 'Error creating backup file';
    }
} else {
    // Read the file content
    $json_content = file_get_contents($filename);
    $data = json_decode($json_content, true);
}
?>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
<table>
    <tr>
        <th width="55%">Edit Node Info</th>
        <th width="45%">Action</th>
    </tr>
    <tr>
        <td>Node Location:</td>
        <td><input type="text" name="nodeLocation" style="width:98%" value="<?php echo htmlspecialchars($data['nodeLocation']); ?>"></td>
    </tr>
    <tr>
        <td>Hidden:</td>
        <td><input type="checkbox" class="form-check-input" name="hidden" <?php echo $data['hidden'] ? 'checked' : ''; ?>></td>
    </tr>
    <tr>
        <td>Sysop:</td>
        <td><input type="text" class="form-control" name="sysop" value="<?php echo htmlspecialchars($data['sysop']); ?>"></td>
    </tr>
    <tr>
        <th width="55%">QTH Information</th>
        <th width="45%">Action</th>
    </tr>
    <tr>
        <td>Name:</td>
        <td><input type="text" class="form-control" name="qth_name" value="<?php echo htmlspecialchars($data['qth'][0]['name']); ?>"></td>
    </tr>
    <tr>
        <td>Latitude:</td>
        <td><input type="text" class="form-control" name="qth_lat" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['lat']); ?>"></td>
    </tr>
    <tr>
        <td>Longitude:</td>
        <td><input type="text" class="form-control" name="qth_long" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['long']); ?>"></td>
    </tr>
    <tr>
        <td>Locator:</td>
        <td><input type="text" class="form-control" name="qth_loc" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['loc']); ?>"></td>
    </tr>
    <tr>
        <th width="55%">Receiver Information</th>
        <th width="45%">Action</th>
    </tr>
    <tr>
        <td>Name:</td>
        <td><input type="text" class="form-control" name="rx_name" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['name']); ?>"></td>
    </tr>
    <tr>
        <td>Frequency:</td>
        <td><input type="text" class="form-control" name="rx_freq" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['freq']); ?>"></td>
    </tr>
    <tr>
        <td>SQL Type:</td>
        <td><input type="text" class="form-control" name="rx_sqlType" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['sqlType']); ?>"></td>
    </tr>
    <tr>
        <th width="55%">Transmitter Information</th>
        <th width="45%">Action</th>
    </tr>
    <tr>
        <td>Name:</td>
        <td><input type="text" class="form-control" name="tx_name" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['name']); ?>"></td>
    </tr>
    <tr>
        <td>Frequency:</td>
        <td><input type="text" class="form-control" name="tx_freq" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['freq']); ?>"></td>
    </tr>
    <tr>
        <td>Power:</td>
        <td><input type="text" class="form-control" name="tx_pwr" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['pwr']); ?>"></td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right;">
            <button type="submit" class="btn btn-primary">Save</button>
        </td>
    </tr>
</table>
</form>


