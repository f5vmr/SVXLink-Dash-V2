<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
  <link href="/css/css.php" type="text/css" rel="stylesheet" />
<style type="text/css">
body {
  background-color: #eee;
  font-size: 18px;
  font-family: Arial;
  font-weight: 300;
  margin: 2em auto;
  max-width: 40em;
  line-height: 1.5;
  color: #444;
  padding: 0 0.5em;
}
h1, h2, h3 {
  line-height: 1.2;
}
a {
  color: #607d8b;
}
.highlighter-rouge {
  background-color: #fff;
  border: 1px solid #ccc;
  border-radius: .2em;
  font-size: .8em;
  overflow-x: auto;
  padding: .2em .4em;
}
pre {
  margin: 0;
  padding: .6em;
  overflow-x: auto;
}

#player {
    position:relative;
    width:205px;
    overflow: hidden;
    direction: ltl;
}

</style>
</head>
<body style = "background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<script src="web-audio-peak-meter.js"></script>
<center>
<fieldset style = "border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:500px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style = "padding:0px;width:495px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<h1 id="web-audio-peak-meters" style = "color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">SVXLink Audio Test Peak Meter</h1>
<p style = "font-size:14px;color:#454545;font-weight:bold;">Ideal Audio Level: <span style = "color:brown;"><b>-15</b> to  <b>-10dB</b></span> Max top Audio level (peak) <span style = "color:brown;"><b> -10dB</b>.</span></p>
<div>
<fieldset style = "border:rgb(255, 156, 42) 2px groove; box-shadow: 5px 5px 20px rgb(255, 236, 214); background-color:#f1f1f1; width:400px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div id="my-peak-meter" style = "width: 32em; height: 65px; margin: 10px;"></div>
</fieldset>
<p style = "margin-top:30px;"></p>
<?php

error_reporting(0);
$filelist=array();
foreach(glob('*.wav') as $filename) {
$filelist[] = $filename;
}
$filelist = array_reverse($filelist);
foreach($filelist as $value) {
echo '<p style = "margin-bottom:10px;margin-top:10px;"><a href='.$filelist[0].'><span style = "font-size:14px;color:blue;font-weight:bold">'.$filelist[0].'</span>&nbsp&nbsp&nbsp&nbsp;<img src=/images/download.png></a></p>';
echo '<div id="player"><audio id="my-audio" preload="none" crossorigin="anonymous" controls="controls"><source src="'.$filelist[0].'?[';
echo time(); 
echo ']" type="audio/wav"></audio></div>';
}
?>
  </center>
  <script>
    var myMeterElement = document.getElementById('my-peak-meter');
    var myAudio = document.getElementById('my-audio');
    var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    var sourceNode = audioCtx.createMediaElementSource(myAudio);
    sourceNode.connect(audioCtx.destination);
    var meterNode = webAudioPeakMeter.createMeterNode(sourceNode, audioCtx);
    webAudioPeakMeter.createMeter(myMeterElement, meterNode, {});
    myAudio.addEventListener('play', function() {
      audioCtx.resume();
    });
  </script>
<br>
</div>
</fieldset>
<?php
    if (isset($_POST['recAudio']))
    {
         exec('/var/www/html/audio/record.sh');
         header("Refresh:0; url=index.php");
    }
?>
<script>
function func() {
    document.getElementById('runRec').value  = 'Recording audio, please wait... ';
    document.getElementById('runRec').className  = 'orange';
}
</script>

<p style = "margin-bottom:0px;"></p>

    <form method="post">
    <p>
    <input name="recAudio" type="submit" class="red" onclick="func()" id="runRec" type="button" value="Click to record 15sec" style = "height:45px;font-size:18px;"></input>
    </p>
    </form>
<p style = "margin: 0 auto;"></p>
<p style = "margin-bottom:-2px;"></p>

</body>
</html>
