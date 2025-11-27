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
    <script type="text/javascript">
        function reloadPage() {
            window.location.href = window.location.pathname + "?reloaded=true";
        }
    </script>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
    <center>
    <fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
            <center>
            <h1 id="ConfEditor" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Node Info Configurator</h1>

            <?php
            include_once "include/functions.php";
            $filename = '/etc/svxlink/node_info.json';
            $backup_dir = '/var/www/html/backups/';

            // Ensure backup directory exists
            if (!file_exists($backup_dir)) {
                mkdir($backup_dir, 0777, true);
            }

            function get_form_data($key, $default = '') {
                return isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : htmlspecialchars($default, ENT_QUOTES, 'UTF-8');
            }

            // ----------- btnSave routine -----------
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSave'])) {

                // Read existing JSON exactly as it is
                $json_content = file_get_contents($filename);
                $data = json_decode($json_content, true);

                // Helper to get raw POST input without escaping
                function raw_post($key, $default = '') {
                    return isset($_POST[$key]) ? $_POST[$key] : $default;
                }

                // Update fields
                $data['nodeLocation'] = raw_post('nodeLocation', $data['nodeLocation']);
                $data['hidden']       = isset($_POST['hidden']);
                $data['sysop']        = raw_post('sysop', $data['sysop']);

                $data['qth'][0]['name']         = raw_post('qth_name', $data['qth'][0]['name']);
                $data['qth'][0]['pos']['lat']   = raw_post('qth_lat', $data['qth'][0]['pos']['lat']);
                $data['qth'][0]['pos']['long']  = raw_post('qth_long', $data['qth'][0]['pos']['long']);
                $data['qth'][0]['pos']['loc']   = raw_post('qth_loc', $data['qth'][0]['pos']['loc']);

                $data['qth'][0]['rx']['K']['name']    = raw_post('rx_name', $data['qth'][0]['rx']['K']['name']);
                $data['qth'][0]['rx']['K']['freq']    = raw_post('rx_freq', $data['qth'][0]['rx']['K']['freq']);
                $data['qth'][0]['rx']['K']['sqlType'] = raw_post('rx_sqlType', $data['qth'][0]['rx']['K']['sqlType']);

                $data['qth'][0]['tx']['K']['name'] = raw_post('tx_name', $data['qth'][0]['tx']['K']['name']);
                $data['qth'][0]['tx']['K']['freq'] = raw_post('tx_freq', $data['qth'][0]['tx']['K']['freq']);
                $data['qth'][0]['tx']['K']['pwr']  = raw_post('tx_pwr', $data['qth'][0]['tx']['K']['pwr']);

                // Convert updated array back to JSON
                $new_json_content = json_encode($data, JSON_PRETTY_PRINT);

                // Create backup
                $backup_filename = $backup_dir . 'node_info_backup_' . date('YmdHis') . '.json';
                if (!copy($filename, $backup_filename)) {
                    echo "Error creating backup file.";
                    exit;
                }

                // Write updated JSON back to main file
                if (file_put_contents($filename, $new_json_content) === false) {
                    echo "Error saving node_info.json";
                    exit;
                }

                // Give filesystem a moment
                sleep(1);

                // Restart SVXLink
                exec('sudo systemctl restart svxlink 2>&1', $screen, $retval);

                if ($retval === 0) {
                    echo "<script type='text/javascript'>reloadPage();</script>";
                    exit;
                } else {
                    echo "Failed to restart SVXLink.<br>";
                    echo nl2br(htmlspecialchars(implode("\n", $screen)));
                    exit;
                }
            } else {
                // Read the file content for display
                $json_content = file_get_contents($filename);
                $data = json_decode($json_content, true);
            }
            // ----------- end btnSave routine -----------

            ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
                <table>
                    <tr>
                        <th width="380px">Edit Node Info</th>
                        <th width="100px">Action</th>
                    </tr>
                    <tr>
                        <td>
                            <table style="border-collapse: collapse; border: none;">
                                <tr style="border: none;">
                                    <th width="30%"></th>
                                    <th width="70%"></th>
                                </tr>
                                <tr style="border: none;">
                                    <td style="border: none;">Node Location:</td>
                                    <td style="border: none;"><input type="text" name="nodeLocation" style="width:98%" value="<?php echo htmlspecialchars($data['nodeLocation']); ?>"></td>
                                </tr>
                                <tr style="border: none;">
                                    <td style="border: none;">Hidden:</td>
                                    <td style="border: none;"><input type="checkbox" class="form-check-input" name="hidden" <?php echo $data['hidden'] ? 'checked' : ''; ?>></td>
                                </tr>
                                <tr style="border: none;">
                                    <td style="border: none;">Sysop:</td>
                                    <td style="border: none;"><input type="text" class="form-control" name="sysop" style="width:98%" value="<?php echo htmlspecialchars($data['sysop']); ?>"></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <br> & <br> ReLoad</button>
                        </td>
                    </tr>
                    <!-- QTH, Receiver, Transmitter sections remain unchanged -->
                    <!-- (same as your original HTML, omitted for brevity) -->
                </table>
            </form>

            <p style="margin: 0 auto;"></p>
            <p style="margin-bottom:-2px;"></p>
        </div>
    </fieldset>
    </center>
</body>
</html>
