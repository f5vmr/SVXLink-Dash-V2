<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
   <div id="display-links">
	<p style = "padding-right: 5px; text-align: right; color: #000000;">
	<a style = "color: black;">Display</a> |
	<a href="/index.php" style = "color: #0000ff;">Dashboard</a> | 
	<a href="/node.php" style = "color: #0000ff;">Nodes</a> | 
	<a href="/tg.php" style = "color: #0000ff;">Talk Groups</a> | 
	<!--<a href="/dtmf.php" style = "color: #0000ff;">Dtmf</a> | --> 
	<a href="/audio.php" style = "color: #0000ff;">Audio</a> | 
	<!--<a href="/wifi.php" style = "color: #0000ff;">Wifi</a> | 
	<a href="/network.php" style = "color: #0000ff;">Network</a> |
	<a href="/nodeInfo.php" style = "color: #0000ff;">Node Info</a> |-->
	
	<a href="editor.php?id=log" style = "color: crimson;" id="log">Log</a> |
	<a href="/authorise.php" style = "color: crimson;">Authorise</a></p>
	</div>
	<div id="full-edit-links">
<p style = "padding-right: 5px; text-align: right; color: #000000;" <a style = "color: black;">Full Edit</a> |
<a href="/editor.php?id=global" style = "color: crimson;" id="global">Global</a> |
<?php
if (isset($svxconfig['SimplexLogic'])) {
    echo '<a href="/editor.php?id=simplexlogic" style = "color: crimson;" id="simplexlogic">SimplexLogic</a> |';
}
if (isset($svxconfig['RepeaterLogic'])) {
    echo '<a href="/editor.php?id=repeaterlogic" style = "color: crimson;" id="repeaterlogic">RepeaterLogic</a> |';
}
if (isset($svxconfig['ReflectorLogic'])) {
    echo '<a href="/editor.php?id=reflectorlogic" style = "color: crimson;" id="reflectorlogic">ReflectorLogic</a> |';
}
?>
<a href="/editor.php?id=amixer" style = "color: crimson;" id="amixer">Amixer</a> |
<a href="/editor.php?id=echolink" style = "color: crimson;" id="echolink">EchoLink</a> |
<a href="/editor.php?id=tclvoicemail" style = "color: crimson;" id="tclvoicemail">TclVoiceMail</a> |
<a href="/editor.php?id=macros" style = "color: crimson;" id="macros">Macros</a> |
<a href="/editor.php?id=metarinfo" style = "color: crimson;" id="metarinfo">MetarInfo</a> |
<a href="/editor.php?id=nodeInfo" style = "color: crimson;" id="nodeInfo">NodeInfo</a> |
 <a href="/editor.php?id=power" style = "color: green;">Power</a></p>
</div>


	 



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

