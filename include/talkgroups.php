<?php
include_once __DIR__ . "/tools.php";
include_once __DIR__ . "/config.talkgroups.php";

function getDefaultTG() {
    global $config;
    return $config['ReflectorLogic']['DEFAULT_TG'] ?? DEFAULT_TG;
}

function getMonitoringTGs() {
    global $config;
    $tgs = isset($config['ReflectorLogic']['MONITORING_TGS'])
        ? explode(",", $config['ReflectorLogic']['MONITORING_TGS'])
        : [];
    return array_pad($tgs, 6, "");
}

function validateSuffixes(array $tgs): string {
    $suffixes = ['++'=>0,'+'=>0,'-'=>0];
    foreach ($tgs as $tg) {
        foreach ($suffixes as $suf => $count) {
            if (str_ends_with($tg, $suf)) {
                $suffixes[$suf]++;
                if ($suffixes[$suf] > 1) return "Suffix '{$suf}' can only appear once in MONITORING_TGS.";
            }
        }
    }
    return "";
}

function updateTalkgroups(string $default, array $monitoring) {
    $confFile = '/etc/svxlink/svxlink.conf';
    $lines = file($confFile, FILE_IGNORE_NEW_LINES);

    foreach ($lines as &$line) {
        if (preg_match('/^\s*DEFAULT_TG\s*=/', $line)) {
            $line = "DEFAULT_TG=$default";
        }
        if (preg_match('/^\s*MONITORING_TGS\s*=/', $line)) {
            $line = "MONITORING_TGS=" . implode(",", array_filter($monitoring, fn($tg) => $tg !== ""));
        }
    }
    file_put_contents($confFile, implode("\n", $lines));
}

function restartSVXLink() {
    exec("systemctl restart svxlink");
}

/**
 * Generate HTML input boxes for DEFAULT_TG and MONITORING_TGS
 * Returns HTML string ready to echo
 */
function renderTalkgroupInputs(string $default, array $monitoring): string {
    $html = "";

    // DEFAULT_TG
    $html .= '<label style="font-weight:bold; color:#464646;">Default TG:</label><br>';
    $html .= '<input type="text" name="default_tg" value="' . htmlspecialchars($default) . '" maxlength="6"><br><br>';

    // MONITORING_TGS with tooltips
    $html .= '<label style="font-weight:bold; color:#464646;">Monitoring TGs:</label><br>';
    for ($i = 0; $i < 6; $i++) {
        $html .= '<div class="tooltip" style="display:inline-block; margin:2px;">';
        $html .= '<input type="text" name="monitoring_tgs[]" value="' . htmlspecialchars($monitoring[$i]) . '" maxlength="6">';
        $html .= '<span class="tooltiptext">Optional suffixes:<br>++ (High Priority), + (Low Priority), - (Exclude)</span>';
        $html .= '</div>';
    }
    $html .= '<br><br>';

    // Legend
    $html .= '<div style="font-size:11px; color:#333; margin-top:5px;">';
    $html .= '<strong>Legend:</strong> ++ = High Priority, + = Low Priority, - = Exclude<br>';
    $html .= 'Current Default TG: ' . htmlspecialchars($default);
    $html .= '</div><br>';

    return $html;
}
