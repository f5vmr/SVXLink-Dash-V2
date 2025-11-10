
<?php

?>

<div class="content">
<?php
include_once __DIR__ . "/tools.php";
include_once __DIR__ . "/config.buttons.php";

// Debug mode: don't refresh automatically
$debug = true;

for ($i = 1; $i <= 20; $i++) {
    $buttonName = "button$i";
    if (array_key_exists($buttonName, $_POST)) {
        if (defined("KEY$i")) {
            $dtmfCode = constant("KEY$i")[1]; // second element: DTMF code
            $exec = "echo '$dtmfCode' > /var/run/svxlink/dtmf_svx";

            // Execute only if not in debug mode
            if (!$debug) {
                exec($exec);
                echo "<meta http-equiv='refresh' content='0'>";
            } else {
                echo "<p>Debug: would execute -> $exec</p>";
            }
        } else {
            echo "<p>Debug: KEY$i not defined</p>";
        }
    }
}
?>
/*
// if(array_key_exists('button8', $_POST)) {
//        $exec="".KEY8[1]."";
//            exec($exec,$output);
//            echo "<meta http-equiv='refresh' content='0'>";
//        }

//if (SHOWPTT=="TRUE") {

// if(array_key_exists('button9', $_POST)) {
//        $exec="".KEY9[1]."";
//            exec($exec,$output);
//           echo "<meta http-equiv='refresh' content='0'>";
//        }
// if(array_key_exists('button10', $_POST)) {
//        $exec="".KEY10[1]."";
//            exec($exec,$output);
//            echo "<meta http-equiv='refresh' content='0'>";
//        }
// }
//
//
*/
?> 



<div class="content">   
<fieldset style = "box-shadow:5px 5px 20px #999;background-color:#e8e8e8e8; width:855px;margin-top:5px;margin-bottom:14px;margin-left:6px;margin-right:0px;font-size:12px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style = "padding:0px;width:100%;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;white-space:normal;">
<p style = "margin-bottom:0px;"></p>
<form method="post">
    <center>
        <input type="submit" name="button1"
            class=<?php echo KEY1[2] ?>  value='<?php echo KEY1[0] ?>' />
        <input type="submit" name="button2"
            class=<?php echo KEY2[2] ?> value='<?php echo KEY2[0] ?>' />
        <input type="submit" name="button3"
            class=<?php echo KEY3[2] ?> value='<?php echo KEY3[0] ?>' />
        <input type="submit" name="button4"
	        class=<?php echo KEY4[2] ?> value='<?php echo KEY4[0] ?>' />
        <input type="submit" name="button5"
	        class=<?php echo KEY5[2] ?> value='<?php echo KEY5[0] ?>' />
	    <input type="submit" name="button6"
            class=<?php echo KEY6[2] ?> value='<?php echo KEY6[0] ?>' />
	    <input type="submit" name="button7"
            class=<?php echo KEY7[2] ?> value='<?php echo KEY7[0] ?>' />
	    <input type="submit" name="button8"
            class=<?php echo KEY8[2] ?> value='<?php echo KEY8[0] ?>' />
	    <input type="submit" name="button9"
            class=<?php echo KEY9[2] ?> value='<?php echo KEY9[0] ?>' />
	    <input type="submit" name="button10"
            class=<?php echo KEY10[2] ?> value='<?php echo KEY10[0] ?>' />

<?php	
//    if (KEY6[0]!="") {
//        echo "<input type=\"submit\" name=\"button6\" class=".KEY6[2]." value='".KEY6[0]."' />";
//        }
//    if (KEY7[0]!="") {
//        echo "<input type=\"submit\" name=\"button7\" class=".KEY7[2]."  value='".KEY7[0]."' />";
//        }

  //  if (SHOWPTT=="TRUE") {
  //      echo "<input type=\"submit\" name=\"button9\" class=".KEY9[2]." value='".KEY9[0]."' />";
  //      echo "<input type=\"submit\" name=\"button10\" class=".KEY10[2]." value='".KEY10[0]."' />";
  //     }

?>
 </center>
    </form>
<p style = "margin: 0 auto;"></p>
<form action="" method="POST" style = "margin-top:4px;">
  <center>
  <label style = "text-shadow: 1px 1px 1px Lightgrey, 0 0 0.5em LightGrey, 0 0 1em whitesmoke;font-weight:bold;color:#464646;" for="dtmfsvx">DTMF command (must end with #):</label>  
  <input type="text" id="dtmfsvx" name="dtmfsvx">
  <input type="submit" value="Send DTMF code" class="green"><br>
  </center>
</form>
<?php
  if (isset($_POST["dtmfsvx"])){
   $exec= "echo '" . $_POST['dtmfsvx'] . "' > /var/run/svxlink/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }
  if (isset($_POST["jmpto"])) {
   $exec= "echo '91" . $_POST['jmpto'] . "#' > /var/run/svxlink/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }
 if (isset($_POST["jmptoA"])) {
   $exec= "echo '91" . $_POST['jmptoA'] . "#' > /var/run/svxlink/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }
if (isset($_POST["jmptoM"])) {
   $exec= "echo '94" . $_POST['jmptoM'] . "#' > /var/run/svxlink/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }


?>
<p style = "margin-bottom:-2px;"></p>
</div>
</fieldset>

