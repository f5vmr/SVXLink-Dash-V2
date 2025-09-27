<?php
// ---------- Helper Functions ----------

// Check if wlan0 exists
function isWifiAvailable() {
    $output = shell_exec("iw dev");
    return strpos($output, 'wlan0') !== false;
}

// Check if wlan0 is connected to Wi-Fi
function isWifiConnected() {
    $output = shell_exec("nmcli -t -f DEVICE,STATE device | grep 'wlan0:connected'");
    return !empty($output);
}

// Assign static IP to wlan0 for hotspot mode
function assignHotspotIP() {
    exec('sudo -n ip link set wlan0 down');
    exec('sudo -n ip addr flush dev wlan0');
    exec('sudo -n ip addr add 192.168.50.1/24 dev wlan0');
    exec('sudo -n ip link set wlan0 up');
}

// Start hotspot
function startHotspot() {
    assignHotspotIP();
    exec('sudo -n systemctl start dnsmasq');
    exec('sudo -n systemctl start hostapd');
}

// Stop hotspot
function stopHotspot() {
    exec('sudo -n systemctl stop hostapd');
    exec('sudo -n systemctl stop dnsmasq');
}

// ---------- Main Logic ----------
$screen = [];

exec('sudo -n nmcli radio wifi on 2>&1');

if (!isWifiAvailable()) {
    $screen[] = "No Wi-Fi device detected.";
    $screen[] = "Starting hotspot...";
    startHotspot();
} elseif (!isWifiConnected()) {
    $screen[] = "Not connected to any Wi-Fi.";
    $screen[] = "Starting hotspot...";
    startHotspot();
} else {
    $screen[] = "Connected to Wi-Fi.";
    stopHotspot();
}

// ---------- Button Actions ----------
if (isset($_POST['btnScan'])) {
    $retval = null; $screen = null;
    exec('sudo -n nmcli dev wifi rescan');
    exec('sudo -n nmcli dev wifi list 2>&1', $screen, $retval);
}

if (isset($_POST['btnConnList'])) {
    $retval = null; $screen = null;
    exec('sudo -n nmcli con show --order type 2>&1', $screen, $retval);
}

if (isset($_POST['btnSwitch'])) {
    $retval = null; $screen = null;
    $ssid = $_POST['ssid'];
    $command = "sudo -n nmcli dev wifi connect \"" . $ssid . "\" 2>&1";
    exec($command, $screen, $retval);
}

if (isset($_POST['btnDelete'])) {
    $retval = null; $screen = null;
    $ssid = $_POST['ssid'];
    $command = "sudo -n nmcli con delete \"" . $ssid . "\" 2>&1";
    exec($command, $screen, $retval);
}

if (isset($_POST['btnAdd'])) {
    $retval = null; $screen = null;
    $ssid = $_POST['ssid'];
    $password = $_POST['password'];
    $command = "sudo -n nmcli dev wifi connect \"" . $ssid . "\" password \"" . $password . "\" 2>&1";
    exec($command, $screen, $retval);
}

if (isset($_POST['btnWifiStatus'])) {
    $retval = null; $screen = null;
    exec('sudo -n nmcli radio 2>&1', $screen, $retval);
}

if (isset($_POST['btnWifiOn'])) {
    $retval = null; $screen = null;
    exec('sudo -n nmcli radio wifi on 2>&1', $screen, $retval);
    exec('sudo -n nmcli radio wifi 2>&1', $screen, $retval);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
</head>
<body style="background-color:#e1e1e1;font:11pt arial,sans-serif;">
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999;
background-color:#f1f1f1;width:555px;margin:15px 5px;font-size:13px;
border-radius:10px;">
    <div style="padding:0;width:550px;background:linear-gradient(to bottom,#e9e9e9 50%,#bcbaba 100%);
    border-radius:10px;border:1px solid LightGrey;margin:4px auto;line-height:1.6;white-space:normal;">
        <h1 style="color:#00aee8;font:18pt arial,sans-serif;font-weight:bold;
        text-shadow:0.25px 0.25px gray;">WiFi Configurator</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div style="height:150px">
                <table>
                    <tr><th>Screen</th></tr>
                    <tr><td>
                        <textarea rows="10" cols="80"><?php echo implode("\n", (array)$screen); ?></textarea>
                    </td></tr>
                </table>
            </div>

            <table>
                <tr>
                    <th width="110px">Action</th>
                    <th width="380px">Input</th>
                    <th width="110px">Action</th>
                </tr>
                <tr>
                    <td>
                        <button name="btnScan" type="submit">Air Scan</button><br>
                        <button name="btnConnList" type="submit">Conn List</button><br>
                        <button name="btnWifiStatus" type="submit">WiFi Status</button>
                    </td>
                    <td>
                        SSID: <input type="text" name="ssid" value="<?php echo htmlspecialchars($_POST['ssid'] ?? ''); ?>"><br>
                        Password: <input type="password" name="password" value="<?php echo htmlspecialchars($_POST['password'] ?? ''); ?>"><br>
                        <button name="btnAdd" type="submit">Add & Connect</button>
                    </td>
                    <td>
                        <button name="btnSwitch" type="submit">Switch SSID</button><br>
                        <button name="btnDelete" type="submit">Delete SSID</button><br>
                        <button name="btnWifiOn" type="submit">WiFi On</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</fieldset>
</body>
</html>
