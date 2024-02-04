<?php
session_start();
include_once "config.php";         
include_once "tools.php";        
include_once "functions.php";    
include_once "tgdb.php";    
?>
<span style="font-weight: bold;font-size:14px;">SVXReflector Activity</span>
<fieldset style=" width:550px;box-shadow:5px 5px 20px #999;background-color:#e8e8e8e8;margin-top:10px;margin-left:0px;margin-right:0px;font-size:12px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
  <form method="post">
  <table style="margin-top:0px;">
    <tr height=25px>
      <th width=150px>Time (<?php echo date('T')?>)</th>
      <th width=100px>Callsign</th>
      <th width=100px>TG #</th>
	<th width=30px> M </th>
	<th width=30px> A </th>
      <th>TG Name</th>
    </tr>
<?php
$i = 0;
for ($i = 0;  ($i <= 20); $i++) { //Last 20 calls
	if (isset($lastHeard[$i])) {
		$listElem = $lastHeard[$i];
		if ( $listElem[1] ) {
      if (isset($svxconfig['GLOBAL']['TIMESTAMP_FORMAT'])) {
        $local_time = substr($listElem[0],-8); }
        else {
        $local_time = substr($listElem[0],-8); }
        //$local_time = date("%e F Y", strtotime('2010-01-08'))
    echo"<tr height=24px style=\"font-size:12.5px;>\">";
		echo"<td align=\"left\">&nbsp; $local_time </td>";
                if ($listElem[3] == "OFF" ) {$bgcolor=""; $tximg="";}
                if ($listElem[3] == "ON" ) {$bgcolor=""; $tximg="<img src=images/tx.gif height=21 alt='TXing' title='TXing' style=\"vertical-align: middle;\">";}
                $ref = substr($listElem[1],0,3);
                $call=$listElem[1];
                $ssid = strpos($listElem[1],"-");
                if ((!preg_match('/[A-Za-z].*[0-9]|[0-9].*[A-Za-z]/', $listElem[1]) or $ref=="XLX" or $ref=="YSF" or $ref=="M17" or substr($listElem[1],0,3)=="TG" )) {
                  echo "<td $bgcolor align='left' valign='middle' class=mh_call>&nbsp;&nbsp;<b>$listElem[1]</b>&nbsp;$tximg</td>";
                } else {
                  if ($ssid){
                  $call = substr($listElem[1],0,$ssid);}
                  echo "<td $bgcolor align=\"left\">&nbsp;&nbsp;<a href=\"http://www.qrz.com/db/".$call."\" target=\"_blank\" class=\"qrz_link\"><b>$listElem[1]</b></a>&nbsp;$tximg</td>";
               }
		//echo "<td align=\"left\">&nbsp;<span style=\"color:#b5651d;font-weight:bold;\">$listElem[2]</span></td>";
		$tgnumber = substr($listElem[2],3);
                $name=$tgdb_array[$tgnumber];
		echo "<td align=\"left\">&nbsp;<span style=\"color:#b5651d;font-weight:bold;\">$tgnumber</span></td>";
		echo "<td><button type=submit id=jumptoM name=jmptoM class=monitor_id value=\"$listElem[2]\"><i class=\"material-icons\"style=\"font-size:15px;\">volume_up</i></button></td>";
		//echo "<td onlick='monitorTmpTG(".$tgnumber.")'> M </a></td>";
		//echo "<td><button> T </button></td>";
                echo "<td><button type=submit id=jumptoA name=jmptoA class=active_id value=\"$listElem[2]\"><i class=\"material-icons\"style=\"font-size:15px;\">cell_tower</i></button></td>";
	       //$tgnumber = substr($listElem[2],3);
               //$name=$tgdb_array[$tgnumber];

               if ( $name==""){ $name ="------";}
               if ( $tgnumber>=1239900 and $tgnumber<= 1239999){ $name ="AUTO QSY";}
		echo "<td style=\"font-weight:bold;color:#464646;\">&nbsp;<b>".$name."</b></td>";
		echo"</tr>\n";
		}
	}
}

?>
  </table></form>
</fieldset>
