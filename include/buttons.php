
<div class="content">
<?php

 if(array_key_exists('button1', $_POST)) {
        $exec= "echo '" . KEY1[1] . "' > /tmp/dtmf_svx";
        exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button2', $_POST)) {
        $exec= "echo '" . KEY2[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button3', $_POST)) {
        $exec= "echo '" . KEY3[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button4', $_POST)) {
        $exec= "echo '" . KEY4[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button5', $_POST)) {
        $exec= "echo '" . KEY5[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button6', $_POST)) {
        $exec= "echo '" . KEY6[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button7', $_POST)) {
        $exec= "echo '" . KEY7[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }

 if(array_key_exists('button8', $_POST)) {
        $exec= "echo '" . KEY8[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }

 if(array_key_exists('button9', $_POST)) {
        $exec= "echo '" . KEY9[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button10', $_POST)) {
        $exec= "echo '" . KEY10[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
if(array_key_exists('button11', $_POST)) {
        $exec= "echo '" . KEY11[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button12', $_POST)) {
        $exec= "echo '" . KEY12[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button13', $_POST)) {
        $exec= "echo '" . KEY13[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button14', $_POST)) {
        $exec= "echo '" . KEY14[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button15', $_POST)) {
        $exec= "echo '" . KEY15[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button16', $_POST)) {
        $exec= "echo '" . KEY16[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button17', $_POST)) {
        $exec= "echo '" . KEY17[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button18', $_POST)) {
        $exec= "echo '" . KEY18[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button19', $_POST)) {
        $exec= "echo '" . KEY19[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
 if(array_key_exists('button20', $_POST)) {
        $exec= "echo '" . KEY20[1] . "' > /tmp/dtmf_svx";
            exec($exec,$output);
            echo "<meta http-equiv='refresh' content='0'>";
        }
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
if (defined('KEY11')) {
    echo '	<input type="submit" name="button11"';
    echo 'class=' . KEY11[2] . ' value="' . KEY11[0] . '" />';
}    
if (defined('KEY12')) {
    echo '	<input type="submit" name="button12"';
    echo 'class=' . KEY12[2] . ' value="' . KEY12[0] . '" />';
}    
if (defined('KEY13')) {
    echo '	<input type="submit" name="button13"';
    echo 'class=' . KEY13[2] . ' value="' . KEY13[0] . '" />';
}    
if (defined('KEY14')) {
    echo '	<input type="submit" name="button14"';
    echo 'class=' . KEY14[2] . ' value="' . KEY14[0] . '" />';
}    
if (defined('KEY15')) {
    echo '	<input type="submit" name="button15"';
    echo 'class=' . KEY15[2] . ' value="' . KEY15[0] . '" />';
}    
if (defined('KEY16')) {
    echo '	<input type="submit" name="button16"';
    echo 'class=' . KEY16[2] . ' value="' . KEY16[0] . '" />';
}    
if (defined('KEY17')) {
    echo '	<input type="submit" name="button17"';
    echo 'class=' . KEY17[2] . ' value="' . KEY17[0] . '" />';
}    
if (defined('KEY18')) {
    echo '	<input type="submit" name="button18"';
    echo 'class=' . KEY18[2] . ' value="' . KEY18[0] . '" />';
}    
if (defined('KEY19')) {
    echo '	<input type="submit" name="button19"';
    echo 'class=' . KEY19[2] . ' value="' . KEY19[0] . '" />';
}    
if (defined('KEY20')) {
    echo '	<input type="submit" name="button20"';
    echo 'class=' . KEY20[2] . ' value="' . KEY20[0] . '" />';
}    
?>

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
   $exec= "echo '" . $_POST['dtmfsvx'] . "' > /tmp/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }
  if (isset($_POST["jmpto"])) {
   $exec= "echo '91" . $_POST['jmpto'] . "#' > /tmp/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }
 if (isset($_POST["jmptoA"])) {
   $exec= "echo '91" . $_POST['jmptoA'] . "#' > /tmp/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }
if (isset($_POST["jmptoM"])) {
   $exec= "echo '94" . $_POST['jmptoM'] . "#' > /tmp/dtmf_svx";
   exec($exec,$output);
   echo "<meta http-equiv='refresh' content='0'>";
    }


?>
<p style = "margin-bottom:-2px;"></p>
</div>
</fieldset>

