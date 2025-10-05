<?php
$screen = [];
$ssid = '';
$password = '';

// Function to check if Wi-Fi is available
function isWifiAvailable() {
    $output = shell_exec("nmcli device status");
    return strpos($output, 'wlan0') !== false;
}

// Function to check if connected to Wi-Fi
function isWifiConnected() {
    $output = shell_exec("nmcli -t -f DEVICE,STATE device | grep '^wlan0:connected'");
    return !empty(trim($output));
}

// Function to check if wired ethernet is connected
function isWiredConnected() {
    $output = shell_exec("nmcli -t -f DEVICE,STATE device | grep '^eth0:connected'");
    return !empty(trim($output));
}

// Always turn on Wi-Fi radio at the start
exec('sudo -n nmcli radio wifi on 2>&1');

// If no Wi-Fi device, or not connected, start hotspot
if (!isWifiAvailable()) {
    $screen[] = "No Wi-Fi device detected.";
    $screen[] = "Hotspot profile available for fallback.";
} elseif (!isWifiConnected() && !isWiredConnected()) {
    $screen[] = "Not connected to any Wi-Fi or Wired network.";
    $screen[] = "You may start the hotspot manually below.";
} else {
    $screen[] = "Connected to a Wi-Fi or Wired network.";
}

// Button actions
if (isset($_POST['btnScan'])) {
    exec('sudo -n nmcli dev wifi rescan 2>&1', $output);
    exec('sudo -n nmcli dev wifi list 2>&1', $screen);
}
if (isset($_POST['btnConnList'])) {
    exec('sudo -n nmcli con show --order type 2>&1', $screen);
}
if (isset($_POST['btnSwitch'])) {
    $ssid = $_POST['ssid'];
    exec("sudo -n nmcli dev wifi connect \"$ssid\" 2>&1", $screen);
}
if (isset($_POST['btnDelete'])) {
    $ssid = $_POST['ssid'];
    exec("sudo -n nmcli con delete \"$ssid\" 2>&1", $screen);
}
if (isset($_POST['btnAdd'])) {
    $ssid = $_POST['ssid'];
    $password = $_POST['password'];
    exec("sudo -n nmcli dev wifi connect \"$ssid\" password \"$password\" 2>&1", $screen);
}
if (isset($_POST['btnWifiStatus'])) {
    exec('sudo -n nmcli radio 2>&1', $screen);
}
if (isset($_POST['btnWifiOn'])) {
    exec('sudo -n nmcli radio wifi on 2>&1', $screen);
    exec('sudo -n nmcli radio wifi 2>&1', $screen);
}
if (isset($_POST['btnStartHotspot'])) {
    exec('sudo -n nmcli connection up Hotspot 2>&1', $screen);
}
if (isset($_POST['btnStopHotspot'])) {
    exec('sudo -n nmcli connection down Hotspot 2>&1', $screen);
}
if (isset($_POST['btnHotspotStatus'])) {
    exec('sudo -n nmcli -t -f NAME,TYPE,DEVICE connection show --active 2>&1', $screen);
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
            direction: ltr;
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
<fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
    <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
        <h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">WiFi Configurator</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <DIV style="height:150px">
                <table>
                    <tr>
                        <th>Screen</th>
                    </tr>
                    <tr>
                        <td>
                            <textarea name="scan" rows="10" cols="80"><?php echo implode("\n", $screen); ?></textarea>
                        </td>
                    </tr>
                </table>
            </DIV>

            <table>
                <tr>
                    <th width="110px">Action</th>
                    <th width="380px">Input</th>
                    <th width="110px">Action</th>
                </tr>
                <tr>
                    <td>
                        <button name="btnScan" type="submit" class="red" style="height:30px;width:105px;font-size:12px;">Air Scan</button>
                        <br>
                        <button name="btnConnList" type="submit" class="red" style="height:30px; width:105px;font-size:12px;">Conn List</button>
                        <br>
                        <button name="btnWifiStatus" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">WiFi Status</button>
                    </td>
                    <td>
                        SSID (network name): <input type="text" name="ssid" value="<?php echo $ssid;?>">
                        <br>
                        Password: <input type="password" name="password" value="<?php echo $password;?>">
                        <br>
                        <button name="btnAdd" type="submit" class="red" style="height:30px;font-size:12px;">Add Network & Connect</button>
                    </td>
                    <td>
                        <button name="btnSwitch" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Switch to SSID</button>
                        <br>
                        <button name="btnDelete" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">Delete SSID</button>
                        <br>
                        <button name="btnWifiOn" type="submit" class="red" style="height:30px; width:105px; font-size:12px;">WiFi On</button>
                        <br>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</fieldset>

<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>

</body>
</html>
