<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
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
h1, h2, h3 { line-height: 1.2; }
a { color: #607d8b; }
#player audio { width:100%; border-radius:8px; }
</style>
<script src="web-audio-peak-meter.js"></script>
</head>
<body style="background-color:#e1e1e1;font:11pt arial, sans-serif;">
<center>

<fieldset style="border:#3083b8 2px groove; box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:500px; margin-top:15px; font-size:13px; border-radius:10px; padding:0;">
  <div style="width:100%; padding:10px; background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%); border-radius:10px; border:1px solid LightGrey; box-sizing:border-box;">
    <center>
      <h1 style="color:#00aee8; font:18pt arial, sans-serif; font-weight:bold; text-shadow:0.25px 0.25px gray;">SVXLink Audio Test Peak Meter</h1>
      <p style="font-size:14px; color:#454545; font-weight:bold;">
        Идеальный уровень звука: <span style="color:brown;">от <b>-15</b> до <b>-10dB</b></span>,
        Макс. уровень звука (пиковый) <span style="color:brown;"><b>-10dB</b></span>.
      </p>

      <!-- Peak meter fieldset -->
      <fieldset style="border:rgb(255, 156, 42) 2px groove; box-shadow:5px 5px 20px rgb(255,236,214); background-color:#f1f1f1; width:100%; margin-top:15px; font-size:13px; border-radius:10px; padding:10px; box-sizing:border-box;">
        <div id="my-peak-meter" style="width:100%; height:65px;"></div>
      </fieldset>
    </center>
  </div>
</fieldset>

<p style="margin-top:30px;"></p>
<?php
$filelist = glob('/var/www/html/audio/audio-*.wav');
rsort($filelist); // newest first

if (!empty($filelist)) {
    $latestFile = $filelist[0];
    echo '<div id="player">';
    echo '<audio id="my-audio" preload="auto" controls style="width:100%; display:block; border-radius:8px; box-sizing:border-box;">';
    echo '<source src="' . $latestFile . '?t=' . time() . '" type="audio/wav">';
    echo '</audio></div>';
}
?>



<script>
window.addEventListener('DOMContentLoaded', function() {
    var myAudio = document.getElementById('my-audio');
    var meterElement = document.getElementById('my-peak-meter');

    if (myAudio && meterElement) {
        var audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        // Use the MediaElementSource for playback from latest WAV
        var sourceNode = audioCtx.createMediaElementSource(myAudio);
        webAudioPeakMeter.createMeter(meterElement, sourceNode, {});
        myAudio.addEventListener('play', function() {
            audioCtx.resume();
        });
    } else {
        console.error('Audio element or meter element not found.');
    }
});

// Button feedback during recording
function func() {
    var btn = document.getElementById('runRec');
    btn.value = 'Идет запись, подождите ... ';
    btn.className = 'orange';
}
</script>

<form method="post">
    <input name="recAudio" type="submit" class="red" onclick="func()" id="runRec" value="Записать 15 сек. фрагмент" style="height:45px;font-size:18px;">
</form>

<?php
if (isset($_POST['recAudio'])) {
    // Record from rx_dup, 15 sec
    exec('/var/www/html/audio/record.sh');
    header("Refresh:0; url=index.php");
}
?>

</center>
</body>
</html>
