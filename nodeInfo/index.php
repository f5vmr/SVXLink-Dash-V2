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
            font-size: 12px;
            font-family: Arial;
            font-weight: 300;
            margin: 2em auto;
            max-width: 40em;
            line-height: 1.5;
            color: #444;
            padding: 0 0.5em;
        }
        h1, h2, h3 { line-height: 1.2; }
        a { color: #607d8b; }
        .highlighter-rouge {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: .2em;
            font-size: .8em;
            overflow-x: auto;
            padding: .2em .4em;
        }
        pre { margin: 0; padding: .6em; overflow-x: auto; }
        #player { position:relative; width:205px; overflow: hidden; direction: ltl; }
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
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;border-radius:10px;">
<div style="padding:0;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;border: 1px solid LightGrey;line-height:1.6;white-space:normal;">
<center>
<h1 style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Node Info Configurator</h1>

<?php
include_once "include/functions.php";
$filename = '/etc/svxlink/node_info.json';
$backup_dir = '/var/www/html/backups/';

if (!file_exists($backup_dir)) mkdir($backup_dir, 0777, true);

function get_form_data($key, $default = '') {
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
}

// Load JSON
$data = json_decode(file_get_contents($filename), true);

// Handle save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSave'])) {

    $data['nodeLocation'] = get_form_data('nodeLocation', $data['nodeLocation']);
    $data['hidden'] = isset($_POST['hidden']);
    $data['sysop'] = get_form_data('sysop', $data['sysop']);

    $data['qth'][0]['name'] = get_form_data('qth_name', $data['qth'][0]['name']);
    $data['qth'][0]['pos']['lat'] = get_form_data('qth_lat', $data['qth'][0]['pos']['lat']);
    $data['qth'][0]['pos']['long'] = get_form_data('qth_long', $data['qth'][0]['pos']['long']);
    $data['qth'][0]['pos']['loc'] = get_form_data('qth_loc', $data['qth'][0]['pos']['loc']);

    $data['qth'][0]['rx']['K']['name'] = get_form_data('rx_name', $data['qth'][0]['rx']['K']['name']);
    $data['qth'][0]['rx']['K']['freq'] = filter_input(INPUT_POST, 'rx_freq', FILTER_VALIDATE_FLOAT, ['options' => ['default' => $data['qth'][0]['rx']['K']['freq']]]);
    $data['qth'][0]['rx']['K']['sqlType'] = get_form_data('rx_sqlType', $data['qth'][0]['rx']['K']['sqlType']);

    $data['qth'][0]['tx']['K']['name'] = get_form_data('tx_name', $data['qth'][0]['tx']['K']['name']);
    $data['qth'][0]['tx']['K']['freq'] = filter_input(INPUT_POST, 'tx_freq', FILTER_VALIDATE_FLOAT, ['options' => ['default' => $data['qth'][0]['tx']['K']['freq']]]);
    $data['qth'][0]['tx']['K']['pwr'] = get_form_data('tx_pwr', $data['qth'][0]['tx']['K']['pwr']);

    $new_json_content = json_encode($data, JSON_PRETTY_PRINT);

    $backup_filename = $backup_dir . 'node_info_backup_' . date('YmdHis') . '.json';
    if (!copy($filename, $backup_filename)) {
    // echo "<p style='color:red;'>Error creating backup file!</p>";
} elseif (file_put_contents($filename, $new_json_content) === false) {
    // echo "<p style='color:red;'>Error saving node_info.json!</p>";
} else {
    // echo "<p style='color:green;'>File saved successfully.</p>";
    exec('sudo systemctl restart svxlink 2>&1', $output, $retval);
    // if ($retval === 0) echo "<p>SVXLink restarted successfully.</p>";
    // else echo "<p style='color:red;'>Failed to restart SVXLink:<br>" . implode('<br>', $output) . "</p>";
}

}
?>

<form method="post" action="">
<table>
<!-- Node Info -->
<tr><th width="380px">Edit Node Info</th><th width="100px">Action</th></tr>
<tr>
<td>
<table style="border:none;">
<tr><td>Node Location:</td><td><input type="text" name="nodeLocation" style="width:98%" value="<?php echo htmlspecialchars($data['nodeLocation']); ?>"></td></tr>
<tr><td>Hidden:</td><td><input type="checkbox" name="hidden" <?php echo $data['hidden'] ? 'checked' : ''; ?>></td></tr>
<tr><td>Sysop:</td><td><input type="text" name="sysop" style="width:98%" value="<?php echo htmlspecialchars($data['sysop']); ?>"></td></tr>
</table>
</td>
<td><button name="btnSave" type="submit" class="red" style="height:100px;width:105px;font-size:12px;">Save<br>&<br>ReLoad</button></td>
</tr>

<!-- QTH -->
<tr><th>QTH Information</th><th>Action</th></tr>
<tr>
<td>
<table style="border:none;">
<tr><td>Name:</td><td><input type="text" name="qth_name" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['name']); ?>"></td></tr>
<tr><td>Latitude:</td><td><input type="text" name="qth_lat" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['lat']); ?>"></td></tr>
<tr><td>Longitude:</td><td><input type="text" name="qth_long" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['long']); ?>"></td></tr>
<tr><td>Locator:</td><td><input type="text" name="qth_loc" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['loc']); ?>"></td></tr>
</table>
</td>
<td><button name="btnSave" type="submit" class="red" style="height:100px;width:105px;font-size:12px;">Save<br>&<br>ReLoad</button></td>
</tr>

<!-- Receiver -->
<tr><th>Receiver Information</th><th>Action</th></tr>
<tr>
<td>
<table style="border:none;">
<tr><td>Name:</td><td><input type="text" name="rx_name" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['name']); ?>"></td></tr>
<tr><td>Frequency:</td><td><input type="text" name="rx_freq" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['freq']); ?>"></td></tr>
<tr><td>SQL Type:</td><td><input type="text" name="rx_sqlType" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['sqlType']); ?>"></td></tr>
</table>
</td>
<td><button name="btnSave" type="submit" class="red" style="height:100px;width:105px;font-size:12px;">Save<br>&<br>ReLoad</button></td>
</tr>

<!-- Transmitter -->
<tr><th>Transmitter Information</th><th>Action</th></tr>
<tr>
<td>
<table style="border:none;">
<tr><td>Name:</td><td><input type="text" name="tx_name" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['name']); ?>"></td></tr>
<tr><td>Frequency:</td><td><input type="text" name="tx_freq" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['freq']); ?>"></td></tr>
<tr><td>Power:</td><td><input type="text" name="tx_pwr" style="width:98%" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['pwr']); ?>"></td></tr>
</table>
</td>
<td><button name="btnSave" type="submit" class="red" style="height:100px;width:105px;font-size:12px;">Save<br>&<br>ReLoad</button></td>
</tr>

</table>
</form>
</div>
</fieldset>
</center>
</body>
</html>
