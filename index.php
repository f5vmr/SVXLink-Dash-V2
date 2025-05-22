<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    $base_dir_def = '<?php define("DL3EL_BASE", "' . __DIR__ . '/");?>' . "\n";;
    $base_dir_dl3el_def = '<?php define("DL3EL", "' . __DIR__ . '/dl3el");?>' . "\n";;
    $base_dir_file = __DIR__ . "/include/basedir.php";
    if (!$handle = fopen($base_dir_file, 'w')) {
            die("Cannot open file ($base_dir_file) for writing. Check file permissions.");
        }
    fwrite($handle, $base_dir_def);
    fwrite($handle, $base_dir_dl3el_def);
    fclose($handle);

if ((!file_exists('include/config.inc.php')) && (file_exists('include/config.inc.php.example')) ) {
    copy('include/config.inc.php.example', 'include/config.inc.php');
    sleep(3); 
}

if ((!file_exists('include/config.php')) && (file_exists('include/config.php.example')) ) {
    copy('include/config.php.example', 'include/config.php');
    sleep(3); 
}

if ((!file_exists('include/buttos.php')) && (file_exists('include/buttons.php.example')) ) {
    copy('include/buttons.php.example', 'include/buttons.php');
    sleep(3); 
}

include "include/settings.php";
$DMRTGFile = DL3EL . "/dbversion";
$dbversion = shell_exec('cat ' . $DMRTGFile);

if ((defined('DL3EL_NOAUTH')) && (DL3EL_NOAUTH === "yes")) {
// always stay logged on
    $_SESSION['auth'] = "AUTHORISED";
}
if ((defined('DL3EL_SC_CHANGE')) && (DL3EL_SC_CHANGE === "yes")) {
    $svxConfigFile = SVXCONFPATH."/".SVXCONFIG;
    if (fopen($svxConfigFile,'r')) {
        $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW); 
        if (defined('DL3EL_SC_STRING')) {
            $sc_port_cmp = DL3EL_SC_STRING;
        } else {    
            $sc_port_cmp = "Audio Device";
        }
        $sc_port_linux = 'aplay -l | grep "' . $sc_port_cmp . '"';
        $sc = 'aplay -l | grep "Audio Device"';
        $sc = substr(shell_exec($sc),5,1);
         $sc_port_name = $svxconfig['SimplexLogic']['RX']; 
         $sc_port = substr($svxconfig[$sc_port_name]['AUDIO_DEV'],12,5); 
         if ($sc != $sc_port) {
            echo "<b>Soundcard&nbsp;mismatch:<br>Card:" . $sc . "/" . substr($svxconfig[$sc_port_name]['AUDIO_DEV'],5,8) . "</b>, will be changed";
            $sc_port_raw = $svxconfig[$sc_port_name]['AUDIO_DEV']; 
            echo "Data old: " . $sc_port_raw;
            $sc_port_new = substr($svxconfig[$sc_port_name]['AUDIO_DEV'],0,12) . $sc; 
            echo "will be changed to Data new: " . $sc_port_new . "<br>";
            $content = file_get_contents($svxConfigFile);
            $backup_filename = $svxConfigFile . "." . date("YmdHis");
            exec('sudo cp -p ' . $svxConfigFile . ' ' . $backup_filename);
            $content = str_replace($sc_port_raw,$sc_port_new,$content); 
            file_put_contents($svxConfigFile, $content);
            echo "done, now restarting svxlink..<br>";
            sleep(1);
            exec('sudo systemctl restart svxlink 2>&1', $screen, $retval);
            if ($retval === 0) {
                echo "SVXLink sucessfull restartet, please reload page";
            } else {
                echo "SVXLink restart failure, check log";
            }
         }   
    }
}
?>
<!DOCTYPE html >
<html>
<head>
    <meta name="robots" content="index" />
    <meta name="robots" content="follow" />
    <meta name="language" content="English" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="generator" content="SVXLink" />
    <meta name="Author" content="G4NAB, SP2ONG, SP0DZ" />
    <meta name="Description" content="Dashboard for SVXLink by G4NAB, SP2ONG, SP0DZ" />
    <meta name="KeyWords" content="SVXLink,G4NAB, SP2ONG, SP0DZ" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
<link rel="shortcut icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/png">    

<?php echo ("<title>" . $callsign ." Ver 2.1 Dashboard</title>" ); ?>

<?php include_once "include/browserdetect.php"; ?>
    <script type="text/javascript" src="scripts/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" src="scripts/pcm-player.min.js"></script>
<!---- ## PTT --->
    <script type="text/javascript" src="scripts/ptt.js"></script>
    <script type="text/javascript">
      $.ajaxSetup({ cache: false });
    </script>
    <link href="css/featherlight.css" type="text/css" rel="stylesheet" />
    <script src="scripts/featherlight.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="">
 <script type="text/javascript">
        function reloadPage() {
            window.location.href = window.location.pathname + "?reloaded=true";
        }
    </script>

</head>
<body style = "background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
<fieldset style = "box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:0px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div class="container"> 
<div class="header">
<div class="parent">
    <div class="img" style = "padding-left:30px"><img src="images/svxlink.ico" /></div>

<!---
    <div class="text"style = "padding-right:230px">
--->    
<center><p style = "margin-top:5px;margin-bottom:0px;">
<span style = "font-size: 32px;letter-spacing:4px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $callsign;?></span>
<p style = "margin-top:0px;margin-bottom:0px;">
<span style = "font-size: 18px;letter-spacing:4px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $fmnetwork; ?></span>
<span style = "font-size: 12px;letter-spacing:2px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $dbversion; ?></span>

</p></center>
</div></div>
    <div class="text"style = "padding-right:230px">
</div>
<?php include_once "include/top_menu.php"; ?>

<div class="content"><center>
<div style = "margin-top:0px;">
</div></center>
</div>
<?php
// klingt seltsam
//if (isProcessRunning('node')) {
//echo '&nbsp;&nbsp;<button class="button link" onclick="playAudioToggle(8080, this)"><b>&nbsp;&nbsp;&nbsp;<img src=images/speaker.png alt="" style = "vertical-align:middle">&nbsp;&nbsp;RX Monitor&nbsp;&nbsp;&nbsp;</b></button><br><br>';
//}
?>
<!-- PTT button -->
<?php 
if (SHOWPTT=="TRUE") {
    include "ptt.html";
}
?>

<?php
if (MENUBUTTON=="TOP") {
include_once "include/buttons.php"; 
}

if (defined('DL3EL')) {
######## DVSwitch Stati
    $DMRStatusFile = DL3EL . "/dmr_status";
    $dmrstatus = trim(shell_exec('cat ' . $DMRStatusFile));
    $color = "blue";
    $colorb = "blue";
//    if (strncmp($dmrstatus, "DMR_FM", 6) === 0) {
    if ($dmrstatus == "DMR_FM") {
        $mode ="DMR_FM";
        $color = "blue";
        $colorb = "green";
    }
//    if (strncmp($dmrstatus, "DMR_only", 8) === 0) {
    if ($dmrstatus == "DMR_only") {
        $mode ="DMR_only";
        $color = "red";
        $colorb = "blue";
    }
    $DMRTGFile = DL3EL . "/dmr_tg";
    $dmrtg = shell_exec('cat ' . $DMRTGFile);

    $DMRdefTG = "9";
    if (defined('DL3EL_DMRTG')) {
        $DMRdefTG = DL3EL_DMRTG;
    }    
    if ($dmrtg == "") {
        $dmrtg = $DMRdefTG;
    }    

//    if (strncmp($dmrstatus, "FM_only", 7) === 0) {
    if ($dmrstatus == "FM_only") {
        $mode ="FM_only";
        $color = "blue";
        $colorb = "blue";
        $dmrtg = "off";
    }
	if (file_exists('/usr/bin/dvs')) {
       $dmr_support = "1";
       $dmrtg = "off";
    } else {
       $dmr_support = "0";
       $dmrtg = "no DMR";
    }   

}   
?>
<?php
if (isset($_POST['btn_DMR_FM']))
    {
        if ($mode == "FM_only") {
// aktuelle svxlink.conf sollte Kopie von svxlink.conf.dmr_fm sein
            $mode ="DMR_FM";
            $color = "blue";
            $colorb = "green";
            $kanal = "OV F49";
            $command = "/opt/MMDVM_Bridge/dvswitch.sh mode DMR 2>&1";
            exec($command,$screen,$retval);
            $command = "echo '*71#' > /tmp/dtmf_svx";
            exec($command,$screen,$retval);
            $dmrtg = $DMRdefTG;
            $command = "/opt/MMDVM_Bridge/dvswitch.sh tune " . $dmrtg . " 2>&1";
            exec($command,$screen,$retval);
            if (defined('DL3EL')) {
                $dmrstatus = "DMR_FM >" . $DMRStatusFile;
                shell_exec("echo $dmrstatus");
                $dmrtgsel = $dmrtg . " >" . $DMRTGFile;
                shell_exec("echo $dmrtgsel");
            }    
        }
}

if (isset($_POST['btn_DMR_only']))
    {
        if ($mode == "FM_only") {
            $command = "sudo cp -p /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.conf.dmr_fm 2>&1";
        }
        $mode ="DMR_only";
        $color = "red";
        $colorb = "blue";
        $kanal = "OV F49";
        $command = "sudo cp -p /etc/svxlink/svxlink.conf.dmr_only /etc/svxlink/svxlink.conf 2>&1";
        exec($command,$screen,$retval);
        $command = "sudo service svxlink restart 2>&1";
        exec($command,$screen,$retval);
        $dmrtg = $DMRdefTG;
        $command = "/opt/MMDVM_Bridge/dvswitch.sh tune " . $dmrtg . " 2>&1";
        exec($command,$screen,$retval);
        if (defined('DL3EL')) {
            $dmrstatus = "DMR_only >" . $DMRStatusFile;
            shell_exec("echo $dmrstatus");
            $dmrtgsel = $dmrtg . " >" . $DMRTGFile;
            shell_exec("echo $dmrtgsel");
        }    
        $command = "/opt/MMDVM_Bridge/dvswitch.sh mode DMR 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btn_DMR_91']))
    {
        if (strncmp($mode, "DMR", 3) === 0) {
            $color = "red";
            $kanal = "WW";
            $dmrtg = "91";
            $command = "/opt/MMDVM_Bridge/dvswitch.sh tune " . $dmrtg . " 2>&1";
            exec($command,$screen,$retval);
            $DMRTGFile = DL3EL . "/dmr_tg";
            if (defined('DL3EL')) {
                $dmrtgsel = $dmrtg ." >" . $DMRTGFile;
                shell_exec("echo $dmrtgsel");
            }    
        }
    }

if (isset($_POST['btn_DMR_262649']))
    {
        if (strncmp($mode, "DMR", 3) === 0) {
            $color = "red";
            $kanal = "OV F49";
            $dmrtg = "262649";
            $command = "/opt/MMDVM_Bridge/dvswitch.sh tune " . $dmrtg . " 2>&1";
            exec($command,$screen,$retval);
            $DMRTGFile = DL3EL . "/dmr_tg";
            if (defined('DL3EL')) {
                $dmrtgsel = $dmrtg ." >" . $DMRTGFile;
                shell_exec("echo $dmrtgsel");
            }    
        }
    }
if (isset($_POST['btn_DMR_only_DISC']))
    {
        if ($mode == "DMR_FM") {
            $command = "echo '*7#' > /tmp/dtmf_svx";
            exec($command,$screen,$retval);
            $command = "echo '*91262649#' > /tmp/dtmf_svx";
            exec($command,$screen,$retval);
        } else {
            $command = "/opt/MMDVM_Bridge/dvswitch.sh tune 4000 2>&1";
            exec($command,$screen,$retval);
            $command = "sudo cp -p /etc/svxlink/svxlink.conf /etc/svxlink/svxlink.conf.dmr_only 2>&1";
            exec($command,$screen,$retval);
            $command = "sudo cp -p /etc/svxlink/svxlink.conf.dmr_fm /etc/svxlink/svxlink.conf 2>&1";
            exec($command,$screen,$retval);
            $command = "sudo service svxlink restart 2>&1";
            exec($command,$screen,$retval);
        }
        $mode ="FM_only";
        $color = "blue";
        $colorb = "blue";
        $kanal = "disconnect";
        if (defined('DL3EL')) {
            $dmrstatus = "FM_only >" . $DMRStatusFile;
            shell_exec("echo $dmrstatus");
        }    
        if ($dmr_support == "1") {
            $dmrtg = "off";
        } else {
            $dmrtg = "no DMR";
        }    
}

// YSF
if (isset($_POST['btn_YSF']))
    {
        $mode ="YSF";
        $kanal = " ";
        $command = "/opt/MMDVM_Bridge/dvswitch.sh mode YSF 2>&1";
        exec($command,$screen,$retval);
//        echo "YSF: " . $command;
//        echo '<pre>YSF'; print_r($screen); echo '</pre>';
}


if (isset($_POST['btn_YSF_DISC']))
    {
        $mode ="YSF";
        $kanal = "disconnect";
        $command = "/opt/MMDVM_Bridge/dvswitch.sh tune disconnect 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btn_YSF_HES']))
    {
        $mode ="YSF";
        $kanal = "Hessen";
        $command = "/opt/MMDVM_Bridge/dvswitch.sh tune c4fm.dehessen.de:42000 2>&1";
        exec($command,$screen,$retval);
}

if (isset($_POST['btn_YSF_26269']))
    {
        $mode ="YSF";
        $kanal = "Hessen";
        $command = "/opt/MMDVM_Bridge/dvswitch.sh tune dc9vq.ysf-deutschland.de/ysf2:42001 2>&1";
        exec($command,$screen,$retval);
}
// DSTAR
if (isset($_POST['btn_DSTAR']))
    {
        $mode ="DSTAR";
        $kanal = " ";
        $command = "/opt/MMDVM_Bridge/dvswitch.sh mode DSTAR 2>&1";
        exec($command,$screen,$retval);
}


if (isset($_POST['btn_DSTAR_DISC']))
    {
        $mode ="DSTAR";
        $kanal = "disconnect";
        $command = "/opt/MMDVM_Bridge/dvswitch.sh tune disconnect 2>&1";
        exec($command,$screen,$retval);
//        echo '<pre>D* 1KL'; print_r($screen); echo '</pre>';
}

if (isset($_POST['btn_DSTAR_HES']))
    {
        $mode ="DSTAR";
        $kanal = "Hessen";
        $command = "/opt/MMDVM_Bridge/dvswitch.sh tune DCS001KL 2>&1";
        exec($command,$screen,$retval);
}

if ((defined('DL3EL_VERSION')) && (strncmp(DL3EL_VERSION, "develop", 7) === 0)) {
// always stay logged on
//    $_SESSION['auth'] = "AUTHORISED";
    ?>

    <table>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<!---
        <button name="btn_DMR" type="submit" style = "border-radius:8px; color:white;border-color:transparent; background-color:blue; height:30px; width:100px; font-size:12px;"><b>DMR ein</b></button>
--->
        <?php 
        if ($dmr_support == "1") {
            echo '<button  style = "border-radius:8px; color:white;border-color:transparent; background-color:orange; height:30px; font-size:12px;"><b>';
            echo $dmrtg . '/';
            echo $mode;
            echo '</b></button>';
        }    
        ?>
        &nbsp;&nbsp;&nbsp;
<!---
        <button name="btn_DMR_only" type="submit" style = "border-radius:8px; color:white;border-color:transparent; background-color:<?php echo $color;?>; height:30px; font-size:12px;"><b>DMR ein</b></button>
--->
        <?php
            if ($dmr_support == "1") {
                if ($mode == "FM_only") {
                    echo '<button name="btn_DMR_only" type="submit" style = "border-radius:8px; color:white;border-color:transparent; background-color:' . $color .'; height:30px; font-size:12px;"><b>DMR ein</b></button>';
                    echo "&nbsp;&nbsp;&nbsp;";
                    echo '<button name="btn_DMR_FM" type="submit" style = "border-radius:8px; color:white;border-color:transparent; background-color:' . $colorb .'; height:30px; font-size:12px;"><b>DMR Bridge ein</b></button>';
                    echo "&nbsp;&nbsp;&nbsp;";
                }
                if ($mode == "DMR_only") {
                    echo '<button name="btn_DMR_only_DISC" type="submit" style = "border-radius:8px; color:white;border-color:transparent; background-color:' . $color .'; height:30px; font-size:12px;"><b>DMR aus</b></button>';
                    echo "&nbsp;&nbsp;&nbsp;";
                }
                if ($mode == "DMR_FM") {
                    echo '<button name="btn_DMR_only_DISC" type="submit" style = "border-radius:8px; color:white;border-color:transparent; background-color:' . $color .'; height:30px; font-size:12px;"><b>DMR Bridge aus</b></button>';
                    echo "&nbsp;&nbsp;&nbsp;";
                }    
                if (($mode == "DMR_only") || ($mode == "DMR_FM")) {
                    echo '<button name="btn_DMR_262649" type="submit" style = "border-radius:8px; color:white;border-color:transparent; background-color:blue; height:30px; font-size:12px;"><b>OV F49</b></button>';
                    echo "&nbsp;&nbsp;&nbsp;";
                    echo '<button name="btn_DMR_91" type="submit" style = "border-radius:8px; color:white; border-color:transparent; background-color:blue; height:30px; font-size:12px;"><b>DMR WW</b></button>';
                    echo '&nbsp;&nbsp;&nbsp;';

                    echo '</form>';
                    echo '<form method="post" action="">';

                    echo '<label for="dmrtg">DMR TG</label>';
                    echo '<input type="text" id="dmrtg" name="dmrtg" value="">';
                    echo '<button type="submit">ausw&auml;hlen</button>';
                    echo '<input type="hidden" name="form_submitted" value="1">';
                    echo '</form>';
                }
                echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]). '">';
                if ($mode == "DMR_only") {
                    $DVSStatusFile = DL3EL . "/dvs_status";
                    $dvsstatus = shell_exec('cat ' . $DVSStatusFile);
                    if (strncmp($dvsstatus, "DMR_DSTAR_YSF", 13) === 0) {
                        echo '	<button name="btn_YSF" type="submit" style="border-radius:8px; color:white; border-color:transparent; background-color:green; height:30px; width:100px; font-size:12px;"><b>YSF</b></button>';
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '	<button name="btn_YSF_HES" type="submit" style = "border-radius:8px; color:white; border-color:transparent; background-color:green; height:30px; width:100px; font-size:12px;"><b>YSF Hessen</b></button>';
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '	<button name="btn_YSF_26269" type="submit" style = "border-radius:8px; color:white; border-color:transparent; background-color:green; height:30px; width:100px; font-size:12px;"><b>YSF 26269</b></button>';
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '	<button name="btn_YSF_DISC" type="submit" style = "border-radius:8px; color:white; border-color:transparent; background-color:green; height:30px; width:100px; font-size:12px;"><b>YSF Discon</b></button>';
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '	<button name="btn_DSTAR" type="submit" style="border-radius:8px; color:white; border-color:transparent; background-color:green; height:30px; width:100px; font-size:12px;"><b>DSTAR</b></button>';
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '	<button name="btn_DSTAR_HES" type="submit" style = "border-radius:8px; color:white; border-color:transparent; background-color:green; height:30px; width:100px; font-size:12px;"><b>D* Hessen</b></button>';
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                        echo '	<button name="btn_DSTAR_DISC" type="submit" style = "border-radius:8px; color:white; border-color:transparent; background-color:green; height:30px; width:100px; font-size:12px;"><b>DSTAR Discon</b></button>';
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                    }
                }    
                echo '</form>';
            }
        ?>
    </table>    
    <?php
}

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
        if (isset($_POST['dmrtg'])) {
            $dmrtg = $_POST['dmrtg'];
            $command = "/opt/MMDVM_Bridge/dvswitch.sh tune " . $dmrtg . " 2>&1";
            exec($command,$screen,$retval);
            if (defined('DL3EL')) {
                $dmrtgsel = $dmrtg . " >" . $DMRTGFile;
                shell_exec("echo $dmrtgsel");
            }    
        }
    }    
    ?>
<?php
    echo '<table style = "margin-bottom:0px;border:0; border-collapse:collapse; cellspacing:0; cellpadding:0; background-color:#f1f1f1;"><tr style = "border:none;background-color:#f1f1f1;">';
    echo '<td width="200px" valign="top" class="hide" style = "height:auto;border:0;background-color:#f1f1f1;">';
    echo '<div class="nav" style = "margin-bottom:1px;margin-top:10px;">'."\n";

    echo '<script type="text/javascript">'."\n";
    echo 'function reloadStatusInfo(){'."\n";
    echo '$("#statusInfo").load("include/status.php",function(){ setTimeout(reloadStatusInfo,3000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadStatusInfo,3000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="statusInfo" style = "margin-bottom:30px;">'."\n";
    include "include/status.php";
    echo '</div>'."\n";
    echo '</div>'."\n";
    echo '</td>'."\n";

    echo '<td valign="top" style = "height:auto;border:none;  background-color:#f1f1f1;">';
    echo '<div class="content">'."\n";
    echo '<script type="text/javascript">'."\n";

    if (URLSVXRAPI!="") {
        echo 'function reloadSVXREF(){'."\n";
        echo '  $("#svxref").load("include/svxref.php",function(){ setTimeout(reloadSVXREF,90000) });'."\n";
        echo '}'."\n";
        echo 'setTimeout(reloadSVXREF,90000);'."\n";
    }

    echo 'function reloadLastHeard(){'."\n";
    echo '  $("#LastHeard").load("include/lh.php",function(){ setTimeout(reloadLastHeard,3000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadLastHeard,3000);'."\n";

    echo 'function reloadLastHeardDMR(){'."\n";
    echo '  $("#LastHeardDMR").load("/DVSwitch/include/lh.php",function(){ setTimeout(reloadLastHeardDMR,3000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadLastHeardDMR,3000);'."\n";


    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<center><div id="LastHeard" style = "margin-bottom:30px;">'."\n";
    include "include/lh.php";
    echo '</div></center>'."\n";
    if ($dmr_support == "1") {
        echo '<center><div id="LastHeardDMR" style = "margin-bottom:30px;">'."\n";
        include "/DVSwitch/include/lh.php";
        echo '</div></center>'."\n";
    }    
    echo "<br />\n";
    if (URLSVXRAPI!="") {
    echo '<center><div id="svxref" style = "margin-bottom:30px;">'."\n";
    //include "include/svxref.php";
    echo '</div></center>'."\n";
    }
    echo '</td></tr></table>';
?>


<?php
if (MENUBUTTON=="BOTTOM") {
include_once "include/buttons.php"; }
?>
<?php
    echo '<div class="content2">'."\n";
    echo '<script type="text/javascript">'."\n";
    echo 'function reloadSysInfo(){'."\n";
    echo '  $("#sysInfo").load("../include/system.php",function(){ setTimeout(reloadSysInfo,15000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadSysInfo,15000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="sysInfo">'."\n";
    include "include/system.php";
    echo '</div>'."\n";
    echo '</div>'."\n";
?>
<center><span title="Dashboard" style = "font: 7pt arial, sans-serif;">SvxLink Dashboard Ver 2.1 ©  G4NAB, SP2ONG, SP0DZ <?php $cdate=date("Y"); if ($cdate > "2021") {$cdate="2021-".date("Y");} echo $cdate ;
 ?>
</div>
</fieldset>
<br>
</body>
</html>
