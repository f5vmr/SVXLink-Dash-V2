<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    $knowledgeFile = DL3EL . "/knowledge";
    if (file_exists($knowledgeFile)) {
	$knowledge = trim(shell_exec('cat ' . $knowledgeFile));
    } else {
	if ((defined('DL3EL_EXPERT')) && (DL3EL_EXPERT === "yes")) {
	    $knowledge = "Expert";
	} else {
	    $knowledge = "Normal";
	}    
    }
    if (isset($_POST['btn_expert'])) {
	define("DL3EL_EXPERT", "yes");
	$knowledge = "Expert";
	$knowledge_new = "Expert >" . $knowledgeFile;
        shell_exec("echo $knowledge_new");
    }
    if (isset($_POST['btn_normal'])) {
	define("DL3EL_EXPERT", "no");
	$knowledge = "Normal";
	$knowledge_new = "Normal >" . $knowledgeFile;
	shell_exec("echo $knowledge_new");
    }
    if ($knowledge == "Expert") {
	$kn_exp = "<b>Expert</b>";
	$kn_nor = "Normal";
    } else {
	$kn_exp = "Expert";
	$kn_nor = "<b>Normal</b>";
    }
?>
   <div id="display-links" align=right>
	<p style = "padding-right: 5px; text-align: right; color: #000000;">
	<a style = "color: black;">Display</a> |
	<a href="./index.php" style = "color: #000000;">Dashboard</a> | 
	<a href="./node.php" style = "color: #000000;">Nodes</a> | 
	<a href="./echolink_dl3el.php" style = "color: #0000ff;">Echolink</a> | 
	<a href="./tg.php" style = "color: #000000;">Talk Groups</a> | 
	<a href="./relais.php" style = "color: #0000ff;">FM Relais</a> | 
	<!--<a href="./dtmf.php" style = "color: #0000ff;">Dtmf</a> | -->
	<!--<a href="./audio.php" style = "color: #0000ff;">Audio </a> | -->
<?php
	if ((defined('DL3EL_VERSION')) && (strncmp(DL3EL_VERSION, "develop", 7) === 0)) {
		echo '<a href="./wifi.php" style = "color: #0000ff;">Wifi</a> | ';
		echo '<a href="./network.php" style = "color: #0000ff;">Network</a> |';
	}	

?>
	<!--<a href="/nodeInfo.php" style = "color: #0000ff;">Node Info</a> |-->

	<a href="./editor.php?id=log" style = "color: crimson;" id="log">Log</a> |
	<a href="./monitor.php" style = "color: crimson;" id="log">MonitorCalls</a> 
<?php
	if ((defined('DL3EL_NOAUTH')) && (DL3EL_NOAUTH === "yes")) {
	    echo ' ';
	} else {    
	    echo '| <a href="./authorise.php" style = "color: crimson;">Authorise</a>';
	}
	if ((defined('DL3EL_VERSION')) && (strncmp(DL3EL_VERSION, "develop", 7) === 0) && (file_exists('/usr/bin/dvs'))) {
	    echo '<a href="/DVSwitch/index.php" style = "color: #0000ff;">| DV-Switch</a></p>';
	}
?>
	</div>
	<div id="full-edit-links"  align=right>
<?php

	echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) .'"> ';
	echo '<button name="btn_expert" type="submit" >' . $kn_exp . '</button>';
	echo '<button name="btn_normal" type="submit" >' . $kn_nor . '</button>';
	ECHO '&nbsp;&nbsp;&nbsp;&nbsp;';
	if ($knowledge == "Expert") {
	    echo '<a style = "padding-right: 5px; text-align: right; color: #000000;" <a style = "color: black;">Full Edit <b>(Expert)</b></a> | ';
	    echo '<a href="./edit.php?file=' . SVXCONFPATH . SVXCONFIG . '" style = "color: black;" id="svxlink">SVXLink</a> | ';
	    echo '<a href="./edit.php?file=' . MODULEPATH . '/' . ECHOLINKCONFIG . '" style = "color: black;" id="echolink">EchoLink</a> | ';
	    echo '<a href="./edit.php?file=' . MODULEPATH . '/' . METARINFO . '" style = "color: black;" id="metarinfo">MetarInfo</a> | ';
	    echo '<a href="./edit.php?file=' . SVXCONFPATH . 'node_info.json" style = "color: black;" id="nodeInfo">NodeInfo</a> | ';
	    echo '<a href="./edit.php?file=log" style = "color: black;" id="log">Log</a> | ';
	    if ((file_exists('/etc/default/shellinabox')) && ((defined('DL3EL_SSH')) && (strncmp(DL3EL_SSH, "yes", 3) === 0))) {
		$getPortCommand = "grep -m 1 'SHELLINABOX_PORT=' /etc/default/shellinabox | awk -F '=' '/SHELLINABOX_PORT=/ {print $2}'";
		$shellPort = exec($getPortCommand);    
		if ($shellPort == 4200) {
		    echo '<a href="./ssh.php" style = "color: black;">SSH</a> | ';
		}
	    }	
	    echo '<a href="./editor.php?id=amixer" style = "color: black;" id="amixer">Amixer</a> | ';
	    if ((defined('TclVoiceMail')) && (strncmp(TclVoiceMail, "no", 1) !== 0)) {
		echo '<a href="./edit.php?file=TclVoiceMail.conf" style = "color: black;" id="tclvoicemail">TclVoiceMail</a> | ';
	    }
	    echo '<a href="./edit.php?file=' . DL3EL_BASE . 'include/config.php" style = "color: black;" id="configphp">config.php</a> | ';
	} else {
	    echo '<a style = "padding-right: 5px; text-align: right; color: #000000;" <a style = "color: black;">Full Edit (normal)</a> | ';
	    echo '<a href="./editor.php?id=svxlink" style = "color: black;" id="svxlink">SVXLink</a> | ';
	    echo '<a href="./editor.php?id=echolink" style = "color: black;" id="echolink">EchoLink</a> | ';
	    echo '<a href="./editor.php?id=metarinfo" style = "color: black;" id="metarinfo">MetarInfo</a> | ';
	    echo '<a href="./editor.php?id=nodeInfo" style = "color: black;" id="nodeInfo">NodeInfo</a> | ';
	    if ((defined('TclVoiceMail')) && (strncmp(TclVoiceMail, "no", 1) !== 0)) {
		echo '<a href="./editor.php?id=tclvoicemail" style = "color: black;" id="tclvoicemail">TclVoiceMail</a> | ';
	    }
	    echo '<a href="./editor.php?id=amixer" style = "color: black;" id="amixer">Amixer</a> | ';
	}	
	if (defined('DL3EL_RADIO')) {
	    $svxRadio = DL3EL_RADIO;
	    if ($svxRadio == "Shari") {
		echo '<a href="./rf.php" style = "color: black;" id="radio">Radio</a> | ';
	    }
	}

?>
	<a href="./editor.php?id=power" style = "color: green;">Power</a></p>
    </div>
    </form>
<?php

include_once('parse_svxconf.php');


/*if (fopen($svxConfigFile,'r'))
{

  $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);
  $logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
  foreach ($logics as $key) {
	if ($key == "SimplexLogic") $isSimplex = true;
	if ($key == "RepeaterLogic") $isRepeater = true; 
  };
  $logics = explode(",",$svxconfig['GLOBAL']['LOGICS']);
  if ($isSimplex) $modules = explode(",",str_replace('Module','',$svxconfig['SimplexLogic']['MODULES']));
  if ($isRepeater) $modules = explode(",",str_replace('Module','',$svxconfig['RepeaterLogic']['MODULES']));
  foreach ($modules as $key){
	if ($key == "EchoLink") $isEchoLink = true;
 }
 */
 //if ($isEchoLink==true) {echo ' <a href="/echolink.php" style = "color: #0000ff;">EchoLink</a> |';};
//$globalRf = $svxconfig['GLOBAL']['RF_MODULE'];

/*if ($globalRf <> "No")
{
	echo'	<a href="/rf.php" style = "color: #0000ff;"> Rf</a> |';
}
}*/
?>

