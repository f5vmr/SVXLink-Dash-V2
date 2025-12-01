<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../include/talkgroups.php';

// Load current values
$default_tg = getDefaultTG();
$monitoring_tgs = getMonitoringTGs();

// Handle POST
if (isset($_POST['btnSave'])) {
    $new_default = trim($_POST['default_tg'] ?? DEFAULT_TG);
    $raw_monitor = $_POST['monitoring_tgs'] ?? [];
    $clean_monitor = array_map('trim', $raw_monitor);

    $error = validateSuffixes($clean_monitor);

    if ($error === "") {
        updateTalkgroups($new_default, $clean_monitor);
        restartSVXLink();
        $message = "Talkgroup settings updated and SVXLink restarted.";

        // Refresh values
        $default_tg = getDefaultTG();
        $monitoring_tgs = getMonitoringTGs();
    }
}
?>

<div class="content">
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
    <fieldset style="box-shadow:5px 5px 20px #999; background-color:#e8e8e8; max-width:850px; width:95%; margin:5px auto 14px auto; font-size:12px; border-radius:10px;">
        <div style="padding:10px; width:100%; background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%); border-radius:10px; border:1px solid LightGrey; white-space:normal; box-sizing:border-box;">

            <form method="POST" style="text-align:center;">
                <h1 id="svxlink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Reflector Talkgroup Settings</h1>

                <?php
                // Single hints block
                if ((defined('SINGLE_TG_DEVICE') && SINGLE_TG_DEVICE) || (isset($error) && $error !== "") || isset($message)) {
                    $msg = "";
                    if (defined('SINGLE_TG_DEVICE') && SINGLE_TG_DEVICE) {
                        $msg .= "Notice: This device is single-talkgroup. DEFAULT_TG will be used as the only talkgroup.<br>";
                    }
                    if (isset($error) && $error !== "") {
                        $msg .= "<span style='color:red;'>$error</span><br>";
                    } elseif (isset($message)) {
                        $msg .= "<span style='color:green;'>$message</span><br>";
                    }
                    echo "<p style='font-weight:bold; text-align:center;'>$msg</p>";
                }
                ?>

                <table style="margin-top:0px; text-align:center; width:100%; max-width:400px; border:2px solid #0000ff; border-collapse:collapse;">
    
    <!-- Row 1: Default Talkgroup -->
    <tr>
        <td style="background:#0000ff; color:white; font-weight:bold; padding:5px; width:40%;">
            Default Talkgroup:
        </td>
        <td style="padding:5px;">
            <input type="text" name="default_tg" 
                   value="<?php echo htmlspecialchars($default_tg); ?>" 
                   style="width:90%; text-align:center; color:brown; font-weight:bold;">
        </td>
    </tr>

    <!-- Row 2: Monitoring TGs label and blank field -->
    <tr>
        <td style="background:#0000ff; color:white; font-weight:bold; padding:5px;">
            Monitoring TGs:
        </td>
        <td style="padding:5px;">
            <!-- Empty on purpose -->
        </td>
    </tr>

    <!-- Row 3: 6 monitoring TG inputs (two rows of three) + Save button -->
    <tr>
        <td style="padding:10px;">

            <?php
            // monitoring_tgs[] already loaded
            for ($i = 0; $i < 6; $i++) {
                $val = $monitoring_tgs[$i] ?? '';
                echo "<input type='text' name='monitoring_tgs[]' 
                        value='" . htmlspecialchars($val) . "' 
                        style='color:brown; font-weight:bold; width:70px; text-align:center; margin:3px;'>";

                if (($i + 1) % 3 == 0) {
                    echo "<br>";
                }
            }
            ?>

        </td>

        <!-- Save button moved into Column 2 -->
        <td style="padding:10px;">
            <button name="btnSave" type="submit" class="red" 
                    style="height:70px; width:90px; font-size:12px;">
                Save<br>&<br>Reload
            </button>
        </td>
    </tr>

</table>


                

            </form>

        </div>
    </fieldset>
</div>
