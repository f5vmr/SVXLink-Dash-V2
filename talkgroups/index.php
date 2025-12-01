<?php
include_once __DIR__ . "/../../tools.php";       // Adjust path if needed
include_once __DIR__ . "/../../config.talkgroups.php"; // For DEFAULT_TG and priority constants

// Load current values from svxlink.conf parser functions
$default_tg = $config['ReflectorLogic']['DEFAULT_TG'] ?? DEFAULT_TG;
$monitoring_tgs = isset($config['ReflectorLogic']['MONITORING_TGS']) 
                  ? explode(",", $config['ReflectorLogic']['MONITORING_TGS']) 
                  : [];

// Ensure 6 boxes for MONITORING_TGS
$monitoring_tgs = array_pad($monitoring_tgs, 6, "");

// Handle form POST
if (isset($_POST['btnSave'])) {
    // Trim and clean default TG
    $new_default = trim($_POST['default_tg'] ?? DEFAULT_TG);

    // Collect monitoring TGs and remove empties
    $raw_monitor = $_POST['monitoring_tgs'] ?? [];
    $clean_monitor = array_filter(array_map('trim', $raw_monitor), fn($tg) => $tg !== "");

    // Validate that each priority suffix occurs at most once
    $suffixes = [
        '++' => 0,
        '+'  => 0,
        '-'  => 0
    ];
    foreach ($clean_monitor as $tg) {
        foreach ($suffixes as $suf => $count) {
            if (str_ends_with($tg, $suf)) {
                $suffixes[$suf]++;
                if ($suffixes[$suf] > 1) {
                    $error = "Suffix '{$suf}' can only appear once in MONITORING_TGS.";
                    break 2;
                }
            }
        }
    }

    if (!isset($error)) {
        $new_monitor_line = implode(",", $clean_monitor);

        // Call your existing function to write back to svxlink.conf
        updateReflectorTalkgroups($new_default, $new_monitor_line);

        // Restart SVXLink
        restart_svxlink(); // replace with your existing restart function

        $message = "Talkgroup settings updated and SVXLink restarted.";
    }
}
?>

<div class="content">
    <fieldset style="box-shadow:5px 5px 20px #999; background-color:#e8e8e8; width:855px; margin:5px 0 14px 6px; font-size:12px; border-radius:10px;">
        <div style="padding:10px; width:100%; background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%); border-radius:10px; border:1px solid LightGrey; white-space:normal;">

            <form method="POST">
                <center>
                    <h3 style="margin-bottom:10px; color:#464646; text-shadow:1px 1px 1px Lightgrey;">Reflector Talkgroup Settings</h3>

                    <?php if (defined('SINGLE_TG_DEVICE') && SINGLE_TG_DEVICE): ?>
                        <p style="color:red; font-weight:bold;">Notice: This device is single-talkgroup. DEFAULT_TG will be used as the only talkgroup.</p>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <p style="color:red; font-weight:bold;"><?php echo htmlspecialchars($error); ?></p>
                    <?php elseif (isset($message)): ?>
                        <p style="color:green; font-weight:bold;"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>

                    <!-- DEFAULT_TG -->
                    <label style="font-weight:bold; color:#464646;">Default TG:</label><br>
                    <input type="text" name="default_tg" value="<?php echo htmlspecialchars($default_tg); ?>" maxlength="6"><br><br>

                    <!-- MONITORING_TGS with tooltips -->
                    <label style="font-weight:bold; color:#464646;">Monitoring TGs:</label><br>
                    <?php for ($i = 0; $i < 6; $i++): ?>
                        <div class="tooltip" style="display:inline-block; margin:2px;">
                            <input type="text" 
                                   name="monitoring_tgs[]" 
                                   value="<?php echo htmlspecialchars(trim($monitoring_tgs[$i])); ?>" 
                                   maxlength="6">
                            <span class="tooltiptext">
                                Optional suffixes:<br>
                                ++ (High Priority), + (Low Priority), - (Exclude)
                            </span>
                        </div>
                    <?php endfor; ?>
                    <br><br>

                    <!-- Legend and Default TG Reminder -->
                    <div style="font-size:11px; color:#333; margin-top:5px;">
                        <strong>Legend:</strong> 
                        <span>++ = High Priority, + = Low Priority, - = Exclude</span><br>
                        <span>Current Default TG: <?php echo htmlspecialchars($default_tg); ?></span>
                    </div>
                    <br>

                    <!-- Save & ReLoad Button -->
                    <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">
                        Save <br> & <br> ReLoad
                    </button>

                </center>
            </form>

        </div>
    </fieldset>
</div>
