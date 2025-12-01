<?php
include_once __DIR__ . '/tools.php';
include_once __DIR__ . '/config.talkgroups.php';

/**
 * Get the DEFAULT_TG from svxlink.conf
 */
function getDefaultTG() {
    $file = "/etc/svxlink/svxlink.conf";
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^\s*DEFAULT_TG\s*=\s*(\S+)\s*$/', $line, $matches)) {
            return $matches[1];
        }
    }
    return DEFAULT_TG;
}

/**
 * Get MONITOR_TGS from svxlink.conf as an array
 */
function getMonitorTGs() {
    $file = "/etc/svxlink/svxlink.conf";
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        if (preg_match('/^\s*MONITOR_TGS\s*=\s*(.*)$/', $line, $matches)) {
            return array_map('trim', explode(',', $matches[1]));
        }
    }
    return [];
}


/**
 * Validate suffixes in monitoring TGs
 */
function validateSuffixes($tgs) {
    $seen = ['++'=>0,'+'=>0,'-'=>0];
    foreach ($tgs as $tg) {
        if ($tg === "") continue;
        foreach ($seen as $suffix => $count) {
            if (str_ends_with($tg, $suffix)) {
                $seen[$suffix]++;
                if ($seen[$suffix] > 1) {
                    return "Only one of each suffix (++,+,-) can appear in the monitoring TGs.";
                }
            }
        }
    }
    return "";
}

/**
 * Update svxlink.conf with new DEFAULT_TG and MONITOR_TGS
 */
function updateTalkgroups($default_tg, $monitoring_array) {
    $file = "/etc/svxlink/svxlink.conf";
    $lines = file($file, FILE_IGNORE_NEW_LINES);

    foreach ($lines as &$line) {
        if (preg_match('/^\s*DEFAULT_TG\s*=.*$/', $line)) {
            $line = "DEFAULT_TG = " . $default_tg;
        }
        if (preg_match('/^\s*MONITOR_TGS\s*=.*$/', $line)) {
            $line = "MONITOR_TGS = " . implode(",", array_filter($monitoring_array, fn($tg) => $tg !== ""));
        }
    }

    file_put_contents($file, implode("\n", $lines));
}



/**
 * Render the input boxes for DEFAULT_TG and MONITOR_TGS
 */
function renderTalkgroupInputs($default_tg, $monitor_tgs) {
    $html = "<table style='margin:auto; text-align:center; width:580px;'>";


    // DEFAULT_TG single box
    $html .= "<tr><td>Default TG:</td>";
    $html .= "<td><input type='text' name='default_tg' value='" 
             . htmlspecialchars($default_tg) 
             . "' style='color:brown; width:125px; text-align:center; margin:2px;'></td></tr>";
    
    // MONITOR_TGS (max 6 boxes)
    $html .= "<tr><td>Monitor TGs:</td><td>";
    for ($i = 0; $i < 6; $i++) {
        $val = $monitor_tgs[$i] ?? '';
        $html .= "<input type='text' name='monitor_tgs[]' value='" 
                 . htmlspecialchars($val) 
                 . "' style='color:brown;  width:100px; text-align:center; margin:2px;'>";
    }
    $html .= "</td></tr></table>";

    return $html;
}



/**
 * Restart SVXLink
 */
function restartSVXLink() {
    exec("systemctl restart svxlink");
}
?>
