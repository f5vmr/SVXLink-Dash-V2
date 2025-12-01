<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../../include/talkgroups.php";

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
    <fieldset style="box-shadow:5px 5px 20px #999; background-color:#e8e8e8; width:855px; margin:5px 0 14px 6px; font-size:12px; border-radius:10px;">
        <div style="padding:10px; width:100%; background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%); border-radius:10px; border:1px solid LightGrey; white-space:normal;">

            <form method="POST">
                <center>
                    <h3 style="margin-bottom:10px; color:#464646; text-shadow:1px 1px 1px Lightgrey;">Reflector Talkgroup Settings</h3>

                    <?php if (defined('SINGLE_TG_DEVICE') && SINGLE_TG_DEVICE): ?>
                        <p style="color:red; font-weight:bold;">Notice: This device is single-talkgroup. DEFAULT_TG will be used as the only talkgroup.</p>
                    <?php endif; ?>

                    <?php if (isset($error) && $error !== ""): ?>
                        <p style="color:red; font-weight:bold;"><?php echo htmlspecialchars($error); ?></p>
                    <?php elseif (isset($message)): ?>
                        <p style="color:green; font-weight:bold;"><?php echo htmlspecialchars($message); ?></p>
                    <?php endif; ?>

                    <?php echo renderTalkgroupInputs($default_tg, $monitoring_tgs); ?>

                    <!-- Save & ReLoad Button -->
                    <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">
                        Save <br> & <br> ReLoad
                    </button>

                </center>
            </form>

        </div>
    </fieldset>
</div>

