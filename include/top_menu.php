<?php
session_start();
?>
   <div id="display-links">
	<p style="padding-right: 5px; text-align: right; color: #000000;">
	<a style="color: black;">Display</a> |
	<a href="/index.php" style="color: #0000ff;">Dashboard</a> | 
	<a href="/node.php" style="color: #0000ff;">Nodes</a> | 
	<a href="/tg.php" style="color: #0000ff;">Talk Groups</a> | 
	<a href="/dtmf.php" style="color: #0000ff;">Dtmf</a> | 
	<!--<a href="/audio.php" style="color: #0000ff;">Audio</a> | 
	<a href="/wifi.php" style="color: #0000ff;">Wifi</a> | 
	<a href="/network.php" style="color: #0000ff;">Network</a> |
	<a href="/nodeInfo.php" style="color: #0000ff;">Node Info</a> |-->
	<a href="/log.php" style="color: #0000ff;">Log</a> |
	<a href="/authorise.php" style="color: crimson;">Authorise</a></p>
	</div>
	<div id="full-edit-links">
	<p style="padding-right: 5px; text-align: right; color: #000000;" <a style="color: black;">Full Edit</a> |
	<a href="/editor.php?id=svxlink" style="color: crimson;" id="svxlink">SVXLink </a> |
	<a href="/editor.php?id=gpio" style="color: crimson;" id="gpio">GPIO</a> |
	<a href="/editor.php?id=echolink" style="color: crimson;" id="echolink">EchoLink</a> |
	<a href="/editor.php?id=metarinfo" style="color: crimson;" id="metarinfo">MetarInfo</a> |
	<a href="/editor.php?id=nodeInfo" style="color: crimson;" id="nodeInfo">NodeInfo</a> |
	<a href="/power.php" style="color: green;">Power</a></p>
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
 //if ($isEchoLink==true) {echo ' <a href="/echolink.php" style="color: #0000ff;">EchoLink</a> |';};
//$globalRf = $svxconfig['GLOBAL']['RF_MODULE'];

/*if ($globalRf <> "No")
{
	echo'	<a href="/rf.php" style="color: #0000ff;"> Rf</a> |';
}
}*/
?>

