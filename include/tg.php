<?php
session_start();
include_once "config.php";         
include_once "tools.php";        
include_once "functions.php";    
include_once "tgdb.php";    
$svxConfigFile = '/etc/svxlink/svxlink.conf';
    if (fopen($svxConfigFile,'r'))
       { $svxconfig = parse_ini_file($svxConfigFile,true,INI_SCANNER_RAW);  
        $callsign = $svxconfig['ReflectorLogic']['CALLSIGN'];
        $fmnetwork =$svxconfig['ReflectorLogic']['HOSTS'];
        //$tgUri = $svxconfig['ReflectorLogic']['TG_URI'];
}



if (isset($_POST['btnUpdateTgs']))
    {

        $retval = null;
        $screen = null;
        //$sAconn = $_POST['sAconn'];
        //$password = $_POST['password'];
        //exec('nmcli dev wifi rescan');
        $command = "sudo wget ".$tgUri." 2>&1";
        exec($command,$screen,$retval);
	//if ($retval) {
	//echo "*";
	$command2 = "sudo mv /var/www/html/tgdb.txt /var/www/html/include/tgdb.php 2>&1";
        exec($command2,$screen,$retval);
	//}
        //$_SESSION['refresh']=True; header("Refresh: 3");

};

?>
<span style="font-weight: bold;font-size:14px;">Talk Groups</span>
<fieldset style=" width:550px;box-shadow:5px 5px 20px #999;background-color:#e8e8e8e8;margin-top:10px;margin-left:0px;margin-right:0px;font-size:12px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
  <form method="post">
  <table style="margin-top:0px;">
    <tr height=25px>
      <th width=100px>TG #</th>
      <th width=30px> M </th>
      <th width=30px> A </th>
      <th>TG Name</th>
    </tr>
<?php
foreach ($tgdb_array as $tg => $tgname)
{ 

		echo "<td align=\"left\">&nbsp;<span style=\"color:#b5651d;font-weight:bold;\">$tg</span></td>";
		echo "<td><button type=submit id=jumptoM name=jmptoM class=monitor_id value=\"$tg\"><i class=\"material-icons\" style=\"font-size:15px;\">volume_up</i></button></td>";
                echo "<td><button type=submit id=jumptoA name=jmptoA class=active_id value=\"$tg\"><i class=\"material-icons\" style=\"font-size:15px;\">cell_tower</i></button></td>";
		echo "<td style=\"font-weight:bold;color:#464646;\">&nbsp;<b>".$tgname."</b></td>";
		echo"</tr>\n";
};

?>
  </table>
<!--<button name="btnUpdateTgs" type="submit" class="red" style="height:30px; width:120px; font-size:12px;">Update Tgs</button>-->
</form>
</fieldset>
