
<?php
include_once __DIR__ . "/tools.php";
include_once __DIR__ . "/config.talkgroups.php";
?>

<div class="content">
<fieldset style="box-shadow:5px 5px 20px #999;background-color:#e8e8e8; width:855px;margin-top:5px;margin-bottom:14px;margin-left:6px;margin-right:0px;font-size:12px;border-radius:10px;">
<div style="padding:0px;width:100%;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius:10px;border:1px solid LightGrey;white-space:normal;">

<form method="post">
    <center>
   
    </center>
</form>

<form action="" method="POST" style="margin-top:4px;">
    <center>
        <label style="text-shadow:1px 1px 1px Lightgrey,0 0 0.5em LightGrey,0 0 1em whitesmoke;font-weight:bold;color:#464646;" for="dtmfsvx">DTMF command (must end with #):</label>  
        <input type="text" id="dtmfsvx" name="dtmfsvx">
        <input type="submit" value="Send DTMF code" class="green"><br>
    </center>
</form>


</div>
</fieldset>
</div>
