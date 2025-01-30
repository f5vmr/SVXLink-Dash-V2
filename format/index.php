<?php include_once "include/top_menu.php"; ?>

<div class="content"><center>
<div style = "margin-top:0px;">
</div></center>
</div>
<?php
if (isProcessRunning('node')) {
echo '&nbsp;&nbsp;<button class="button link" onclick="playAudioToggle(8080, this)"><b>&nbsp;&nbsp;&nbsp;<img src=images/speaker.png alt="" style = "vertical-align:middle">&nbsp;&nbsp;RX Monitor&nbsp;&nbsp;&nbsp;</b></button><br><br>';
}
?>
<?php
if (MENUBUTTON=="TOP") {
include_once "include/buttons.php"; 
}
?>