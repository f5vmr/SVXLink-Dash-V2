<?php
session_start();
include_once "config.php";          
include_once "tools.php";       
include_once "functions.php";    

$url=URLSVXRAPI;
if ($url!="") {
//  Initiate curl
$ch = curl_init();
// Will return the response, if false it print the response
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// Set the url
curl_setopt($ch, CURLOPT_URL,$url);
// Execute
$result=curl_exec($ch);
// Closing
curl_close($ch);
$nodes = json_decode($result, true);
 } else { $nodes="";}
if ($nodes!="") {
if(array_key_exists('Name', $nodes)) {
    $name=$nodes['Name'];
} else { $name="";}
?>
<!--

<span style="font-weight: bold;font-size:14px;">SVXReflector Nodes</span>

<br>

-->
<fieldset style="width:550px;border:rgb(255, 156, 42) 2px groove; box-shadow: 5px 5px 20px rgb(255, 236, 214); background-color:#f1f1f1;margin-left:0px;margin-right:0px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
<div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
<center>
<p id="rcornerh" style="margin-bottom:35px;"><span style="font-weight: 580;font-size:18px;">Connected Nodes to: </span><span style="font-weight: 580;font-size:16px;color:red;"><?php echo FMNETWORK; ?></span><br>
<?php
$count=0;
foreach ($nodes['nodes'] as $key =>$value)
 { $count=$count+1;}
echo "<span style=\"line-height:1.8;font-weight:600;font-size:16px;color:black;\">Number of connected nodes:&nbsp;&nbsp;</span>
      <span style=\"line-height:1.8;font-weight:600;font-size:15px;color:brown;\">";
echo $count;
echo "</span></p>";
?>
</center>
<div style="text-align:left;font:9pt arial,sans-serif;margin-left:25px; margin-right:25px;margin-top:15px;margin-bottom:30px;line-height:1.6;white-space:normal;">
<center>
<?php
foreach ($nodes['nodes'] as $key =>$value)
 { 
   echo "<span class=\"tooltip\" style=\"border-bottom: 1px dotted white;\">";
   echo "<span class=\"node\">".$key."<span class=\"tooltiptext\" style=\"top:100%;left:25%;margin-left:-50%;max-width:200px;width:195px;word-wrap: break-word;white-space: pre-wrap; padding: 3px 0;\">";
   if ($nodes['nodes'][$key]['nodeLocation']!=""){
   echo "&nbsp;&nbsp;Info:<br><span style=\"color:gold;margin-left:10px;margin-right:10px;\"><b>";
   echo $nodes['nodes'][$key]['nodeLocation']."</span><br>";
      }
   echo "&nbsp;&nbsp;Monitored TGs:<br><span style='color:yellow;margin-left:10px;margin-right:10px;'>";
   echo "<form method=\"post\">";
   echo "<BR><center>";
   echo "<table style=\"width:150px\" >";
   echo"<tr height=15px>";
	echo "<th >TG#</th>";
	echo "<th >M</th>";
	echo "<th >A</th>";
   echo"</tr>";
   foreach ($nodes['nodes'][$key]['monitoredTGs'] as $item)
     {  
	
	echo "<tr><td align=\"left\">";
	//echo "<span style=\"margin-left:0px;margin-right:0px; padding:0px 0;\">[";
	//echo "<input type=submit id=jmptoM name=jmptoM class=black value=".$item." />";
	echo "<span style=\"color:#b5651d;font-weight:bold; padding:1px 10px;\">$item</span>";
	//echo "$item";
	echo "</td><td>";
	echo "<button type=submit id=jmptoM name=jmptoM class=monitor_id value=".$item."><i class=\"material-icons\"style=\"font-size:15px;\">volume_up</i></button>";
	echo "</td><td>";
	echo "<button type=submit id=jmptoA name=jmptoA class=active_id value=".$item."><i class=\"material-icons\"style=\"font-size:15px;\">cell_tower</i></button>";
	//echo "]";
	//echo "</span> ";
	//echo "<BR>";
	echo "</td></Tr>";
}
echo "</table>";
echo "<br></center>";
   echo "</form></span></span></span></span>";
 }
?>
</center>
</div><br></div></fieldset>
<?php 
}
?>
