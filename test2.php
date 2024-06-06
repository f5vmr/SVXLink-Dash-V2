<td?php
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
            <th width="380 px">Edit Node Info</th>
            <th width="100 px">Action</th>
            <?php if (isset($message)): ?>
                <div class="alert alert-info"><?php echo $message; ?></div>
            <?php endif; ?>
            </tr>
    <table style="border-collapse: collapse; border: none;">
            <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
            </tr>
            
<!--        <form action="index.php" method="post">
            <div class="form-group"> -->
                <tr style="border: none;">
                <td style = "border: none;" label for="nodeLocation">Node Location:</label>
                </td>
                <td style = "border: none;">
                <input type="text" name="nodeLocation" style ="width:98%" value="<?php echo htmlspecialchars($data['nodeLocation']); ?>">
            </td></tr>
                <tr style="border: none;">
                <td style = "border: none;" label for="hidden">Hidden:</td>
                <td style = "border: none;">
                <input type="checkbox" class="form-check-input"  <?php echo $data['hidden'] ? 'checked' : ''; ?>>
            </td></tr>
                <tr style="border: none;">
                <td style = "border: none;" label for="sysop">Sysop:</td>
                <td style = "border: none;">
                <input type="text" class="form-control" name="sysop" value="<?php echo htmlspecialchars($data['sysop']); ?>">
            </td></tr>
            <table>
        <tr>
        <th width = "380px">QTH Information </th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<TD>
        <Table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
            <tr style="border: none;">
            <td style = "border: none;" label for="qth_name">Name:</td>
            <td style = "border: none;">
            <input type="text" class="form-control" name="qth_name" value="<?php echo htmlspecialchars($data['qth'][0]['name']); ?>">
        </td></tr>
            <tr style="border: none;">
            <td style = "border: none;" label for="qth_lat">Latitude:</td>
            <td style = "border: none;">
            <input type="text" class="form-control" name="qth_lat" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['lat']); ?>">
            </td>
            </tr>
        <tr style="border: none;">
            <tr style="border: none;">
            <td style = "border: none;" label for="qth_long">Longitude:</td>
            <td style = "border: none;">
            <input type="text" class="form-control" name="qth_long" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['long']); ?>">
            </td></tr>
        <tr style="border: none;">
            <tr style="border: none;">
            <td style = "border: none;" label for="qth_loc">Locator:</td>
            <td style = "border: none;">
            <input type="text" class="form-control" name="qth_loc" value="<?php echo htmlspecialchars($data['qth'][0]['pos']['loc']); ?>">
            </td></tr>
        
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
</bodtable>
</html>
