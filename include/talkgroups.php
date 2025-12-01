<?php
include_once __DIR__ . '/tools.php';
include_once __DIR__ . '/config.talkgroups.php';

/**
 * Get the DEFAULT_TG from svxlink.conf
 */
function getDefaultTG() {
    global $config;
    return $config['ReflectorLogic']['DEFAULT_TG'] ?? DEFAULT_TG;
}

/**
 * Get MONITOR_TGS from svxlink.conf as an array
 */
function getMonitoringTGs() {
    global $config;
    $tgs = $config['ReflectorLogic']['MONITOR_TGS'] ?? '';
    return array_map('trim', explode(',', $tgs));
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
    $file = "/etc/svxlink/svxlink.conf";  // adjust path if needed
    $lines = file($file, FILE_IGNORE_NEW_LINES);
    foreach ($lines as &$line) {
        if (str_starts_with(trim($line), "DEFAULT_TG=")) {
            $line = "DEFAULT_TG=" . $default_tg;
        }
        if (str_starts_with(trim($line), "MONITOR_TGS=")) {
            $line = "MONITOR_TGS=" . implode(",", array_filter($monitoring_array, fn($tg) => $tg !== ""));
        }
    }
    file_put_contents($file, implode("\n", $lines));
}

/**
 * Render the input boxes for DEFAULT_TG and MONITOR_TGS
 */
function renderTalkgroupInputs($default_tg, $monitoring_tgs) {
    $html = "<table style='margin:auto; text-align:center;'>";
    // DEFAULT_TG single box
    $html .= "<tr><td>Default TG:</td>";
    $html .= "<td><input type='text' name='default_tg' value='" . htmlspecialchars($default_tg) . "' style='color:brown; font-weight:bold; width:90px; text-align:center; margin:2px;'></td></tr>";
    
    // MONITOR_TGS (max 6 boxes)
    $html .= "<tr><td>Monitoring TGs:</td><td>";
    for ($i = 0; $i < 6; $i++) {
        $val = $monitoring_tgs[$i] ?? '';
        $html .= "<input type='text' name='monitoring_tgs[]' value='" . htmlspecialchars($val) . "' style='color:brown; font-weight:bold; width:90px; text-align:center; margin:2px;'>";
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
