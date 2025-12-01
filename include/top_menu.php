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
	<a href="/dtmf.php" style = "color: #0000ff;">Dtmf</a> | 
	<a href="/audio.php" style = "color: #0000ff;">Audio</a> | 
	<a href="editor.php?id=log" style = "color: crimson;" id="log">Log</a> |
	<a href="/authorise.php" style = "color: crimson;">Authorise</a></p>
	</div>
	<div id="full-edit-links">
	<p style = "padding-right: 5px; text-align: right; color: #000000;" <a style = "color: black;">Full Edit</a> |
	<a href="/editor.php?id=svxlink" style = "color: crimson;" id="svxlink">SVXLink</a> |
	<a href="/editor.php?id=talkgroups" style = "color: crimson;" id="talkgroups">TalkGroups</a> |
	<a href="/editor.php?id=buttons" style = "color: crimson;" id="buttons">Buttons</a> |
	<a href="/editor.php?id=amixer" style = "color: crimson;" id="echolink">Amixer</a> |
	<a href="/editor.php?id=echolink" style = "color: crimson;" id="echolink">EchoLink</a> |
	<a href="/editor.php?id=metarinfo" style = "color: crimson;" id="metarinfo">MetarInfo</a> |
	<a href="/editor.php?id=nodeInfo" style = "color: crimson;" id="nodeInfo">NodeInfo</a> |
	<a href="/editor.php?id=power" style = "color: green;">Power</a></p>
    </div>

	 



<?php

include_once('parse_svxconf.php');

?>

