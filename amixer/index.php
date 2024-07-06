<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to execute amixer command and retrieve output
function execute_amixer($command) {
    $output = [];
    exec($command, $output);
    return $output;
}

// Function to parse current values from amixer output
function parse_amixer_value($output) {
    foreach ($output as $line) {
        if (strpos($line, ": values=") !== false) {
            $value = trim(str_replace(": values=", "", $line));
            return $value;
        }
    }
    return null;
}

// Function to calculate percentage relative to maximum permitted value and return as whole number
function calculate_percentage($current_value, $max_value) {
    return round(($current_value / $max_value) * 100);
}

// Retrieve and parse current values using amixer cget
function get_current_amixer_values() {
    $headphone_output = execute_amixer("sudo amixer cget numid=6");
    $mic_output = execute_amixer("sudo amixer cget numid=4");
    $capture_output = execute_amixer("sudo amixer cget numid=8");
    $autogain_output = execute_amixer("sudo amixer sget numid=9");

    $current_values = [
        'headphone' => parse_amixer_value($headphone_output),
        'mic' => parse_amixer_value($mic_output),
        'capture' => parse_amixer_value($capture_output),
        'autogain' => null
    ];

    if ($autogain_output) {
        foreach ($autogain_output as $line) {
            if (strpos($line, ": values=") !== false) {
                $current_values['autogain'] = trim(str_replace(": values=", "", $line));
                break;
            }
        }
    }

    return $current_values;
}

// Maximum permitted values based on numid
$max_values = [
    'headphone' => 151, // numid=6
    'mic' => 32, // numid=4
    'capture' => 16 // numid=8
];

// Get current values from amixer
$current_values = get_current_amixer_values();

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

        h1,
        h2,
        h3 {
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
            position: relative;
            width: 205px;
            overflow: hidden;
            direction: ltl;
        }

        textarea {
            background-color: #111;
            border: 1px solid #000;
            color: #ffffff;
            padding: 1px;
            font-family: courier new;
            font-size: 10px;
        }
    </style>
    <script type="text/javascript">
        function refreshPage() {
            setTimeout(function() {
                window.location.reload();
            }, 500); // Adjust the delay as needed
        }
    </script>
</head>

<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
    <center>
        <fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
            <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
                <center>
                    <h1 id="svxlink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Audio Configurator</h1>
                    <h3 style="color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">AMixer settings</h3>

                    <!-- HTML Form to adjust ALSA settings -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="refreshPage()">
                        <h3 style="color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Headphone - TX Levels</h3>
                        <label for="headphone">Set for 65 (0-100):</label>
                        <input type="number" id="headphone" name="headphone" min="0" max="100" value="<?php echo htmlspecialchars(calculate_percentage($current_values['headphone'], $max_values['headphone'])); ?>" required>
                        <br>
                        <h3 style="color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Microphone - Not Used</h3>
                        <label for="mic">(0-100): Set to 0</label>
                        <input type="number" id="mic" name="mic" min="0" max="100" value="<?php echo htmlspecialchars(calculate_percentage($current_values['mic'], $max_values['mic'])); ?>" required>
                        <br>
                        <h3 style="color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Audio Capture - RX Levels</h3>
                        <label for="capture">(0-100) Set for 25:</label>
                        <input type="number" id="capture" name="capture" min="0" max="100" value="<?php echo htmlspecialchars(calculate_percentage($current_values['capture'], $max_values['capture'])); ?>" required>
                        <br>
                        <h3 style="color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Auto Gain</h3>
                        <label for="autogain">Set to OFF for optimum control</label>
                        <select id="autogain" name="autogain" required>
                            <option value="off" <?php if ($current_values['autogain'] === 'off') echo 'selected'; ?>>Off</option>
                            <option value="on" <?php if ($current_values['autogain'] === 'on') echo 'selected'; ?>>On</option>
                        </select>
                        <br>
                        <button type="submit">Apply Settings</button>
                        <input type="hidden" name="form_submitted" value="1">
                    </form>
                </center>
            </div>
        </fieldset>
    </center>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
        if (isset($_POST['headphone'])) {
            $headphone_percentage = intval($_POST['headphone']);
            $headphone_value = ($headphone_percentage / 100) * $max_values['headphone'];
            exec("sudo amixer cset numid=6 " . escapeshellarg($headphone_value));
        }

        if (isset($_POST['mic'])) {
            $mic_percentage = intval($_POST['mic']);
            $mic_value = ($mic_percentage / 100) * $max_values['mic'];
            exec("sudo amixer cset numid=4 " . escapeshellarg($mic_value));
        }

        if (isset($_POST['capture'])) {
            $capture_percentage = intval($_POST['capture']);
            $capture_value = ($capture_percentage / 100) * $max_values['capture'];
            exec("sudo amixer cset numid=8 " . escapeshellarg($capture_value));
        }

        if (isset($_POST['autogain'])) {
            $autogain = $_POST['autogain'] === 'on' ? 'on' : 'off';
            exec("sudo amixer sset numid=9 " . escapeshellarg($autogain));
        }

        // Refresh the page to show updated values
        echo '<script type="text/javascript">refreshPage();</script>';
    }
    ?>
</body>

</html>

