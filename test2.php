<?php
$filename = '/etc/svxlink/node_info.json';
$backup_dir = '/etc/svxlink/backups/';

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Node Info</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Edit Node Info</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form action="parse_nodeInfo.php" method="post">
            <div class="form-group">
                <label for="nodeLocation">Node Location:</label>
                <input type="text" class="form-control" name="nodeLocation" value="<?php echo htmlspecialchars($data['nodeLocation']); ?>">
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="hidden" <?php echo $data['hidden'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="hidden">Hidden</label>
            </div>
            <div class="form-group">
                <label for="sysop">Sysop:</label>
                <input type="text" class="form-control" name="sysop" value="<?php echo htmlspecialchars($data['sysop']); ?>">
            </div>
            <h3>QTH</h3>
            <div class="form-group">
                <label for="qth_name">Name:</label>
                <input type="text" class="form-control" name="qth_name" value="<?php echo htmlspecialchars($data['qth'][0]['name']); ?>">
            </div>
            <div class="form-group">
                <label for="qth_lat">Latitude:</label>
                <input type="text" class="form-control" name="qth_lat" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['lat']); ?>">
            </div>
            <div class="form-group">
                <label for="qth_long">Longitude:</label>
                <input type="text" class="form-control" name="qth_long" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['long']); ?>">
            </div>
            <div class="form-group">
                <label for="qth_loc">Locator:</label>
                <input type="text" class="form-control" name="qth_loc" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['loc']); ?>">
            </div>
            <h3>RX</h3>
            <div class="form-group">
                <label for="rx_name">Name:</label>
                <input type="text" class="form-control" name="rx_name" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['name']); ?>">
            </div>
            <div class="form-group">
                <label for="rx_freq">Frequency:</label>
                <input type="text" class="form-control" name="rx_freq" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['freq']); ?>">
            </div>
            <div class="form-group">
                <label for="rx_sqlType">SQL Type:</label>
                <input type="text" class="form-control" name="rx_sqlType" value="<?php echo htmlspecialchars($data['qth'][0]['rx']['K']['sqlType']); ?>">
            </div>
            <h3>TX</h3>
            <div class="form-group">
                <label for="tx_name">Name:</label>
                <input type="text" class="form-control" name="tx_name" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['name']); ?>">
            </div>
            <div class="form-group">
                <label for="tx_freq">Frequency:</label>
                <input type="text" class="form-control" name="tx_freq" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['freq']); ?>">
            </div>
            <div class="form-group">
                <label for="tx_pwr">Power:</label>
                <input type="text" class="form-control" name="tx_pwr" value="<?php echo htmlspecialchars($data['qth'][0]['tx']['K']['pwr']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</body>
</html>
