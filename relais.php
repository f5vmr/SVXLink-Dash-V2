<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "include/settings.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" lang="en">
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

<?php echo ("<title>" . $callsign ." Ver 2.1 Dashboard</title>"); ?>

<?php include_once "include/browserdetect.php"; ?>
    <script type="text/javascript" src="scripts/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/functions.js"></script>
    <script type="text/javascript" src="scripts/pcm-player.min.js"></script>
    <script type="text/javascript">
      $.ajaxSetup({ cache: false });
    </script>
    <link href="css/featherlight.css" type="text/css" rel="stylesheet" />
    <script src="scripts/featherlight.js" type="text/javascript" charset="utf-8"></script>
</script>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
    <div class="text"style = "padding-right:230px">
<center><p style = "margin-top:5px;margin-bottom:0px;">
<span style = "font-size: 32px;letter-spacing:4px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $callsign; ?></span>
<p style = "margin-top:0px;margin-bottom:0px;">
<span style = "font-size: 18px;letter-spacing:4px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $fmnetwork; ?></span>
<span style = "font-size: 12px;letter-spacing:2px;font-family: &quot;sans-serif&quot;, sans-serif;font-weight:500;color:PaleBlue"><?php echo $dbversion; ?></span>
</p></center>
</div></div>
</div>
<?php include_once "include/top_menu.php"; ?>
<!--- 
<div class="content"><center>
<div style = "margin-top:0px;">
</div></center>
</div>
-->
<?php
if (MENUBUTTON=="TOP") {
include_once "include/buttons.php"; 
}
?>
<?php
    echo '<table style = "margin-bottom:0px;border:0; border-collapse:collapse; cellspacing:0; cellpadding:0; background-color:#f1f1f1;"><tr style = "border:none;background-color:#f1f1f1;">';
//    echo '<td width="200px" valign="top" class="hide" style = "height:auto;border:0;background-color:#f1f1f1;">';
//    echo '<td valign="top" class="hide" style = "height:auto;border:0;background-color:#f1f1f1;">';
    echo '<div class="nav" style = "margin-bottom:1px;margin-top:1px;">'."\n";

    echo '</div>'."\n";
    echo '</td>'."\n";

   if (defined('DL3EL')) {
      $ELQueryFile = DL3EL . "/el_query";
      $elquery = shell_exec('cat ' . $ELQueryFile);
      $RelaisFile = DL3EL . "/relais.csv";
      $FMQueryFile = DL3EL . "/fm_query";
      $fmquery = shell_exec('cat ' . $FMQueryFile);
      $FMLQueryFile = DL3EL . "/fml_query";
      $fmlquery = shell_exec('cat ' . $FMLQueryFile);
   } else {
      $RelaisFile = "relais.csv";
   }
?>
   <p style = "padding-left: 5px; text-align: left;"> &nbsp;
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="reloadPage()">
        <label for="prefix">Call</label>
        <input type="text" id="prefix" name="prefix" value=<?php echo $fmquery;?>>  
        <label for="locator"> oder Locator</label>
        <input type="text" id="locator" name="locator" value=<?php echo $fmlquery;?>>  
        <input type="checkbox" name="type_el" checked value="1">Echolink&nbsp;&nbsp;
        <input type="checkbox" name="type_fr" checked value="1">FM Funknetz&nbsp;&nbsp;
        <input type="checkbox" name="type_fhs" value="1">FM Funknetz Hotspots&nbsp;&nbsp;
        <button type="submit">Query</button>
        <input type="hidden" name="form_submitted" value="1">
    </form>
   </p> 
    <?php
//wget -O relais.csv -q "http://relais.dl3el.de/cgi-bin/relais.pl?ctrcall=dl3el-14&sel=ctrcall&type_fr=1&printas=csv&maxgateways=20&nohtml=yes"
    $loc = "";
    $loc_found = 0;
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
//    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['type_el'])) { $query_el = "&type_el=" . $_POST['type_el'];}
        if (isset($_POST['type_fr'])) { $query_fr = "&type_fr=" . $_POST['type_fr'];}
        if (isset($_POST['type_fhs'])) { $query_fhs = "&type_fhs=" . $_POST['type_fhs'];}
        if (isset($_POST['prefix'])) { $query_loc = "sel=ctrcall&ctrcall=" . $_POST['prefix'] ;}
        if (isset($_POST['locator']) && ($_POST['locator'] != "")) { 
            $query_loc = "sel=gridsq&gs=" . $_POST['locator'] ;
            $loc = $_POST['locator'];
            $loc_found = 1;
        }
    
//        echo "EL: " . $query_el . "&nbsp; FMR" . $query_fr . "<br>";
//        $cmd = "wget -O " . $RelaisFile . " -q \"http://relais.dl3el.de/cgi-bin/relais.pl?sel=ctrcall&ctrcall=" . $_POST['prefix'] . $query_el . $query_fr . $query_fhs . "&printas=csv&maxgateways=20&nohtml=yes&quelle=y\"";
        $cmd = "wget -O " . $RelaisFile . " -q \"http://relais.dl3el.de/cgi-bin/relais.pl?" . $query_loc . $query_el . $query_fr . $query_fhs . "&printas=csv&maxgateways=20&nohtml=yes&quelle=y\"";
//        $cmd = "wget -O " . $RelaisFile . " -q \"https://relais.dl3el.de/FM-Relais/DM7DS/relaisgeo_fmn_el.php\"";
//        echo "<br>Aufruf: " . $cmd . "<br>";
        echo "",exec($cmd, $output, $retval);
        if (defined('DL3EL')) {
            $fmquery = $_POST['prefix'] . " >" . $FMQueryFile;
            shell_exec("echo $fmquery");
            $fmlquery = $_POST['locator'] . " >" . $FMLQueryFile;
            shell_exec("echo $fmlquery");
        }    
    }
    if (($handle = fopen($RelaisFile, "r")) !== FALSE) {
        echo '<form method="post">';
        while (($data = fgetcsv($handle, 1000, ";", "\"", "\\")) !== FALSE) {
//        echo "0: " . $data[0] . "/ 1:" . $data[1] . "/ 2:"  . $data[2] . "/ 3:"  . $data[3] . "/ 4:"  . $data[4] . "/ 5:" . $data[5] . "/ 6:" . $data[6] . "/ 7:"  . $data[7] . "/ 8:"  . $data[8] . "/ 9:"  . $data[9] . "/ 10:" . $data[10] . "/ 11:"  . $data[11] . "/ 12:"  . $data[12] . "<br>";
            if (!$loc_found && ($data[3] !== "Locator")) {
                $loc = $data[3];
                $loc_found = 1;
            }    
            if ((strncmp($data[0], "Daten m", 7) !== 0) && (strncmp($data[0], "Call", 4) !== 0)) {
                if (strncmp($data[12], "FR", 2) === 0) {
                    $bold_b = "<b>";
                    $bold_e = "</b>";
                } else {
                    $bold_b = "";
                    $bold_e = "";
                }
                if (strncmp($data[12], "E", 1) === 0) {
//          printf "<button type=submit id=jmptoE name=jmptoE class=active_id value=%s>%s:%s</button>",$CurrentLoginsTab{$call}{'ELNODE'},$CurrentLoginsTab{$call}{'ELCALL'},$CurrentLoginsTab{$call}{'ELNODE'};
                    $echolink_conn = "<button type=submit id=jmptoE name=jmptoE class=active_id value=" . $data[13] . ">EL#" . $data[13] . "</button>";
                } elseif (strncmp($data[12], "F", 1) === 0) {
                    $echolink_conn = "<button type=submit id=jmptoE name=jmptoA class=active_id value=" . $data[14] . ">TG " . $data[14] . "</button>";
                } else {
                    $echolink_conn = "";
                }    
//                substr($data[4], 0, 20);
//                echo "<tr><td>" . $bold_b . $data[0] . $bold_e . "</td><td>" . $data[1] . "</td><td>"  . $data[2] . "</td><td>"  . $data[3] . "</td><td>" . $bold_b . $data[4] . $bold_e . "</td><td>"  . $data[9] . "</td><td>"  . $echolink_conn . "</td><td>"  . $data[11] . "</td></tr>";
                echo "<tr><td>" . $bold_b . $data[0] . $bold_e . "</td><td>" . $data[1] . "</td><td>"  . $data[2] . "</td><td>"  . $data[3] . "</td><td>" . $bold_b . substr($data[4], 0, 50) . $bold_e . "</td><td>"  . $data[9] . "</td><td>"  . $echolink_conn . "</td><td>"  . $data[11] . "</td></tr>";
            }
        }
        fclose($handle);
        $cmd = "wget -O- -q \"http://relais.dl3el.de/cgi-bin/metar.pl?sel=gridsq&gs=" . $loc . "\"";
        echo "",exec($cmd, $output, $retval);
        echo '</form>';
    } else {
      echo "wrong file: " . $RelaisFile ."<br>";  
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_submitted'])) {
//    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Refresh the page to show updated values
        echo "<script type='text/javascript'>
//            reloadPage();
        </script>";    
    }    
  ?>
</table>

<?php
    if (MENUBUTTON=="BOTTOM") {
        include_once "include/buttons.php"; 
    }

    echo '<div class="content2">'."\n";
    echo '<script type="text/javascript">'."\n";
    echo 'function reloadSysInfo(){'."\n";
    echo '  $("#sysInfo").load("include/system.php",function(){ setTimeout(reloadSysInfo,15000) });'."\n";
    echo '}'."\n";
    echo 'setTimeout(reloadSysInfo,15000);'."\n";
    echo '$(window).trigger(\'resize\');'."\n";
    echo '</script>'."\n";
    echo '<div id="sysInfo">'."\n";
    include "include/system.php";
    echo '</div>'."\n";
    echo '</div>'."\n";
?>
<?php

if (isset($_POST["jmptoE"])) {
    $exec= "echo '2#" . $_POST['jmptoE'] . "#' > /tmp/dtmf_svx";
    exec($exec,$output);
}

?>
<!--- Please do not remove copyright info -->
<center><span title="Dashboard" style = "font: 7pt arial, sans-serif;">SvxLink Dashboard Ver 2.1 ©  G4NAB, SP2ONG, SP0DZ <?php $cdate=date("Y"); if ($cdate > "2021") {$cdate="2021-".date("Y");} echo $cdate; ?>
	</div>
</div>
</fieldset>
<br>
</body>
</html>
