<?php
session_start();
include_once "config.php";         
include_once "tools.php";        
//include_once __DIR__.'/functions.php';
//require_once __DIR__.'/include/config.php';
//function open_for_edit($filename) {
//        if (fopen($filename,'r')){
//                $file_conf = parse_ini_file($filename, true, INI_SCANNER_RAW);
//        }
//        $parts = explode(",", $file_conf['A']['B']);
//}

function getSVXLog() {
	// Open Logfile and copy loglines into LogLines-Array()
	$logLines = array();
	$logLines1 = array();
	$logLines2 = array();
//	if (file_exists(LOGPATH."/".SVXLOGPREFIX."-".gmdate("Y-m-d").".log")) {
	if (file_exists(SVXLOGPATH.SVXLOGPREFIX)) {
		$logPath = SVXLOGPATH.SVXLOGPREFIX;
		$logLines1 = explode("\n", `tail -10000 $logPath | egrep -a -h "Talker start on|Talker stop on" `);
	}
	$logLines1 = array_slice($logLines1, -250);
	if (sizeof($logLines1) < 250) {
		if (file_exists(SVXLOGPATH.SVXLOGPREFIX.".1")) {
			$logPath = SVXLOGPATH.SVXLOGPREFIX.".1";
			$logLines2 = explode("\n", `tail -10000 $logPath | egrep -a -h "Talker start on|Talker stop on" `);
		}
	}
	$logLines2 = array_slice($logLines2, -250);
//	$logLines = $logLines1 + $logLines2;
	$logLines = array_merge($logLines1,$logLines2);
	$logLines = array_slice($logLines, -500);
	return $logLines;
}

function getSVXStatusLog() {
	// Open Logfile and copy loglines into LogLines-Array()
	$logLines = array();
	$logLines1 = array();
	$logLines2 = array();
	if (file_exists(SVXLOGPATH.SVXLOGPREFIX)) {
		$logPath = SVXLOGPATH.SVXLOGPREFIX;
		$logLines1 = explode("\n", `tail -10000 $logPath | egrep -a -h "EchoLink QSO|ransmitter|Selecting" `);
	}
	$logLines1 = array_slice($logLines1, -250);
	if (sizeof($logLines1) < 250) {
		if (file_exists(SVXLOGPATH.SVXLOGPREFIX.".1")) {
			$logPath = SVXLOGPATH.SVXLOGPREFIX.".1";
			$logLines2 = explode("\n", `tail -10000 $logPath |egrep -a -h "Talker start on|Talker stop on" `);
		}
	}
	$logLines2 = array_slice($logLines2, -250);
//	$logLines = $logLines1 + $logLines2;
	$logLines = array_merge($logLines1,$logLines2);
	$logLines = array_slice($logLines, -250);
	return $logLines;
}

// SVXReflector connections
//2021-07-22 18:57:03: RefLogic: Heartbeat timeout
//2021-07-22 18:57:03: RefLogic: Disconnected from 127.0.0.1:5300: Locally ordered disconnect
//2021-07-22 18:59:18: RefLogic: Disconnected from 127.0.0.1:5300: Connection timed out
//2021-07-25 16:30:35: RefLogic: Disconnected from 127.0.0.1:5300: Connection refused
//2021-07-25 16:31:46: RefLogic: Disconnected from 127.0.0.1:5300: No route to host
//2021-07-22 19:07:03: RefLogic: Connection established to 127.0.0.1:5300
//2021-07-22 19:07:03: RefLogic: Authentication OK

function getSVXRstatus() {
	if (file_exists(SVXLOGPATH.SVXLOGPREFIX)) {
           $logPath = SVXLOGPATH.SVXLOGPREFIX; 
           $svxrstat = `tail -10000 $logPath | egrep -a -h "Authentication|Connection established|Heartbeat timeout|No route to host|Connection refused|Connection timed out|Locally ordered disconnect|Deactivating link|Activating link" | tail -1`;}
	if ($svxrstat=="" &&  file_exists(SVXLOGPATH.SVXLOGPREFIX.".1")) {
           $logPath = SVXLOGPATH.SVXLOGPREFIX.".1"; 
           $svxrstat = `tail -10000 $logPath | egrep -a -h "Authentication|Connection established|Heartbeat timeout|No route to host|Connection refused|Connection timed out|Locally ordered disconnect|Deactivating link|Activating link" | tail -1`;}
           if(strpos($svxrstat,"Authentication OK") || strpos($svxrstat,"Connection established") || strpos($svxrstat,"Activating link")){
              $svxrstatus="Connected";
            }
           elseif (strpos($svxrstat,"Heartbeat timeout") || strpos($svxrstat,"No route to host") || strpos($svxrstat,"Connection refused") || strpos($svxrstat,"Connection timed out") || strpos($svxrstat,"Locally ordered disconnect") || strpos($svxrstat,"Deactivating link")) { $svxrstatus="Not connected";}
           else { $svxrstatus="No status";}
      return $svxrstatus;
}

// SVXLink proxy public log lines
//2021-06-19 20:45:16: Connected to EchoLink proxy 51.83.134.252:8100
//2021-06-19 20:45:16: *** ERROR: Access denied to EchoLink proxy
//2021-06-19 20:45:16: Disconnected from EchoLink proxy 51.83.134.252:8100
//2021-06-19 20:53:19: Connected to EchoLink proxy 44.137.75.82:8100

function getEchoLinkProxy() {
	if (file_exists(SVXLOGPATH.SVXLOGPREFIX)) {
           $elogPath = SVXLOGPATH.SVXLOGPREFIX; 
           $echoproxy = `tail -10000 $elogPath | grep -a -h "EchoLink proxy" | tail -1`;}
	if ($echoproxy=="" && file_exists(SVXLOGPATH.SVXLOGPREFIX.".1")) {
           $elogPath = SVXLOGPATH.SVXLOGPREFIX.".1"; 
           $echoproxy = `tail -10000 $elogPath | grep -a -h "EchoLink proxy" | tail -1`;}
           if(strpos($echoproxy,"Connected to EchoLink proxy")){
              $proxy=substr($echoproxy,strpos($echoproxy,"Connected to EchoLink proxy")+27);
              $eproxy="Connected to proxy<br><span style=\"color:brown;font-weight:bold;\">".$proxy."</span>";
            }
           elseif(strpos($echoproxy,"Disconnected from EchoLink proxy")){
              $proxy=substr($echoproxy,strpos($echoproxy,"Disconnected from EchoLink proxy")+32);
              $eproxy="<span style=\"color:red;font-weight:bold;\">Disconnected proxy</span><br><span style=\"color:brown;font-weight:bold;\">".$proxy."</span>";
            }
           elseif(strpos($echoproxy,"Access denied to EchoLink proxy")){
              $eproxy="Access denied to proxy";
            }
           else { $eproxy="";}

      return $eproxy;
}


function getEchoLog() {
	if (file_exists(SVXLOGPATH.SVXLOGPREFIX)) {
           $elogPath = SVXLOGPATH.SVXLOGPREFIX; 
           $echolog = explode("\n",`tail -10000 $elogPath | grep -a -h "EchoLink QSO" `);}
           $echolog = array_slice($echolog, -250);
      return $echolog;
}

function getConnectedEcholink($echolog) {
        $users = Array();
        foreach ($echolog as $ElogLine) {
                //if(strpos($ElogLine,"EchoLink QSO")){
                        //$users = Array();
                //}
                if(strpos($ElogLine,"state changed to CONNECTED")) {
                        $lineParts = explode(" ", $ElogLine);
              if (!in_array(substr($lineParts[2],0,-1), $users)) {
                                array_push($users,trim(substr($lineParts[2],0,-1)));
                        }
                }
                if(strpos($ElogLine,"state changed to DISCONNECTED")) {
                    $lineParts = explode(" ", $ElogLine);
    		    $call=substr($lineParts[2],0,-1);
        	    $pos = array_search($call, $users);
                    array_splice($users, $pos, 1);
                }
        }
        return $users;
}

// check callsign EchoLink talker TXing form log line
// ### EchoLink talker stop SP2ABC
// ### EchoLink talker start SP2ABC


function getEchoLinkTX() {
        $logPath = SVXLOGPATH.SVXLOGPREFIX;
        $echotxing="";
        $logLine = `tail -10000 $logPath | egrep -a -h "### EchoLink" | tail -1`;
        if (strpos($logLine,"### EchoLink talker start")) {
          $echotxing=substr($logLine,strpos($logLine,"start")+6,12);
         }
        return $echotxing;
}

function getSVXTGSelect() {
        $logPath = SVXLOGPATH.SVXLOGPREFIX;
        $tgselect="0";
        $logLine = `tail -10000 $logPath | egrep -a -h "Selecting" | tail -1`;
        if (strpos($logLine,"TG #")) {
          $tgselect=substr($logLine,strpos($logLine,"#")+1,12);
         }
        return $tgselect;
}

function getSVXTGTMP() {
        $logPath = SVXLOGPATH.SVXLOGPREFIX;
        $tgselect="0";
        $logLine = `tail -10000 $logPath | egrep -a -h "emporary monitor" | tail -1`;
        if (strpos($logLine,"Add")) {
          $tgselect=substr($logLine,strpos($logLine,"#")+1,12);
         }
         else {$tgselect=""; }
        return $tgselect;
}

function initModuleArray() {
    $modules = Array();
    foreach (SVXMODULES as $enabled) {
                $modules[$enabled] = 'Off';
        }
    return $modules;
}

function getActiveModules() {
    $logLines = array();
    $logPath = SVXLOGPATH.SVXLOGPREFIX;
    $logLines = explode("\n",`tail -10000 $logPath | egrep -a -h "Activating module|Deactivating module" `);
    $logLines = array_slice($logLines, -250);
    $modules = initModuleArray();
        foreach ($logLines as $logLine) {
                if(strpos($logLine,"Activating module")) {
                        $lineParts = explode(" ", $logLine);
	    $modul = substr($lineParts[5],0,-3);
                        if (!array_search($modul, $modules)) {
                                $modules[$modul] = 'On';
                        }
	    if (array_search($modul, $modules)) {
		$modules[$modul] = 'On';
	    }
                }
                if(strpos($logLine,"Deactivating module")) {
                        $lineParts = explode(" ", $logLine);
	    $modul = substr($lineParts[5],0,-3);
	    $modules[$modul] = 'Off';
                }

        }
        return $modules;
}



//SVXLink log line
//14.06.2021 16:00:00: Tx1: Turning the transmitter ON
//14.06.2021 16:00:44: Tx1: Turning the transmitter OFF
//14.06.2021 16:57:27: RefLogic: Talker start on TG #7: DMR-Bridge
//14.06.2021 16:57:27: RefLogic: Selecting TG #7
//14.06.2021 16:57:27: Transmission starts (TG# 0)
//14.06.2021 16:57:28: Tx1: Turning the transmitter ON
//14.06.2021 16:57:33: Transmission stops (TG# 0)
//14.06.2021 16:57:33: RefLogic: Talker stop on TG #7: DMR-Bridge
//14.06.2021 16:57:33: Tx1: Turning the transmitter OFF

function getHeardList($logLines) {
	//array_multisort($logLines,SORT_DESC);
	$heardList = array();
        //print_r($logLines);
	foreach ($logLines as $logLine) {
	     if(strpos($logLine,"Tx1") || strpos($logLine,"Rx1") || strpos($logLine, ": Talker start on") || strpos($logLine, ": Talker stop on")) {
		if (strpos($logLine,": Talker stop on")) {
                $calltemp = substr($logLine,strpos($logLine,"TG")+4,27);
		$callsign = substr($calltemp,strpos($calltemp,":")+1,27);
		$callsign = trim($callsign);
                $target = "TG ".trim(get_string_between($logLine, "#", ":"));
		$source = "SVXRef";
		$timestamp = substr($logLine, 0, 19);
                $tx="OFF";
               } 
		if (strpos($logLine,": Talker start on")) {
                 $calltemp = substr($logLine,strpos($logLine,"TG")+4,27);
		 $callsign = substr($calltemp,strpos($calltemp,":")+1,27);
		 $callsign = trim($callsign);
                 $target = "TG ".trim(get_string_between($logLine, "#", ":"));
		 $source = "SVXRef";
		 $timestamp = substr($logLine, 0, 19);
                 $tmss=strtotime($timestamp);
                 $tmst=strtotime('now');
		 $diff=$tmst-$tmss;
                 if ($diff>300) {
                	$tx="OFF"; 
		    } else { $tx="ON";}
                } 
		// Callsign should be less than 16 chars long, otherwise it could be errorneous
		if ( strlen($callsign) < 16 ) {
			array_push($heardList, array($timestamp, $callsign, $target, $tx, $source));
		}
	}
}
	return $heardList;
}

function getLastHeard($logLines) {
	$lastHeard = array();
	$heardCalls = array();
	$heardList = getHeardList($logLines);
	$counter = 0;
	foreach ($heardList as $listElem) {
		if ( $listElem[4] == "SVXRef" ) {
			$callUuid = $listElem[1]."#".$listElem[2];
			if(!(array_search($callUuid, $heardCalls) > -1)) {
				array_push($heardCalls, $callUuid);
				array_push($lastHeard, $listElem);
				$counter++;
			}
		}
	}
	return $lastHeard;
}

//14.06.2021 16:57:33: Rx1: The squelch is OPEN (2.07523)
//14.06.2021 16:57:33: Rx1: The squelch is CLOSED (4.43843)
//14.06.2021 16:57:33: Tx1: Turning the transmitter ON
//14.06.2021 16:57:33: Tx1: Turning the transmitter OFF

function getTXInfo() {
	$logPath = SVXLOGPATH.SVXLOGPREFIX;
	if (file_exists(SVXLOGPATH.SVXLOGPREFIX)) { 
                $txstat =`tail -10000 $logPath | egrep -a -h "Turning the transmitter|squelch is|squelch for" | tail -1`; 
	        //print($txstat);
                
                if (strpos($txstat, "ON")) { 
	   	// $timestamp = substr($txstat, 0, 19);
        //      //date_default_timezone_set('Europe/Warsaw');
                // $tmss=strtotime($timestamp);
                // $tmst=strtotime('now');
          	// $diff=$tmst-$tmss;
                // if ($diff>250) {
            	//       $txs="<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Listening</div></td></tr>\n"; 
        	//	    } else { $txs="<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">TX</div></td></tr>\n";
                //            }    
                return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">TX</div></td></tr>\n";       
                //return $txs;
        }
        //if (strpos($txstat, "OFF")) { 
        //  
        //                return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">TXdone</div></td></tr>\n";
        //        }


           //     $txs =  "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\"></div></td></tr>\n";
        
	//};
	        if (strpos($txstat, "OPEN")) { 		
               
                 return "<tr><td style=\"background:#4aa361;color:black;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">RX</div></td></tr>\n";
        //;;
                } ;
                return  "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Listening</div></td></tr>\n"; 

        }
}

//}

//2022-11-08 00:36:01: Rx1: Distortion detected! Please lower the input volume!

function getRXPeak() {
        $logPath = SVXLOGPATH.SVXLOGPREFIX;
        if (file_exists(SVXLOGPATH.SVXLOGPREFIX)) { 
                $txstat =`tail -100 $logPath | egrep -a -h "Distortion detected!" | tail -1`; 
                //print($txstat);
		$timestamp = substr($txstat, 0, 19);
        //      //date_default_timezone_set('Europe/Warsaw');
                $tmss=strtotime($timestamp);
                $tmst=strtotime('now');
                $diff=$tmst-$tmss;
                // if ($diff>250)



                if (strpos($txstat, "Distortion") && ($diff < 1) ) { 
                return "<tr><td style=\"background:#ff6600;color:white;\"><div style=\"margin-top:2px;margin-bottom:2px;font-weight:bold;\">DISTORTION</div></td></tr>\n";
                //return $txs;
        }
                return  "<td style=\"background:#c3e5cc;\"><div style=\"margin-top:2px;margin-bottom:2px;color:#464646;font-weight:bold;\">Peak OK</div></td></tr>\n";
        }
}

//}








function getConfigItem($section, $key, $configs) {
	// retrieves the corresponding config-entry within a [section]
	$sectionpos = array_search("[" . $section . "]", $configs) + 1;
	$len = count($configs);
	while(startsWith($configs[$sectionpos],$key."=") === false && $sectionpos <= ($len) ) {
		if (startsWith($configs[$sectionpos],"[")) {
			return null;
		}
		$sectionpos++;
	}

	return substr($configs[$sectionpos], strlen($key) + 1);
}

function get_string_between($string, $start, $end) {
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) {
	return "";
    }
    $ini += strlen($start);   
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

$logLinesSVX = getSVXLog();
$reverseLogLinesSVX = $logLinesSVX;
array_multisort($reverseLogLinesSVX,SORT_DESC);
$lastHeard = getLastHeard($reverseLogLinesSVX);
function build_ini_string(array $a) {
        $out = '';
        $sectionless = '';
        foreach($a as $rootkey => $rootvalue){
            if(is_array($rootvalue)){
                // find out if the root-level item is an indexed or associative array
                $indexed_root = array_keys($rootvalue) == range(0, count($rootvalue) - 1);
                // associative arrays at the root level have a section heading
                if(!$indexed_root) $out .= PHP_EOL."[$rootkey]".PHP_EOL;
                // loop through items under a section heading
                foreach($rootvalue as $key => $value){
                    if(is_array($value)){
                        // indexed arrays under a section heading will have their key omitted
                        $indexed_item = array_keys($value) == range(0, count($value) - 1);
                        foreach($value as $subkey=>$subvalue){
                            // omit subkey for indexed arrays
                            if($indexed_item) $subkey = "";
                            // add this line under the section heading
                            $out .= "{$key}[$subkey] = $subvalue" . PHP_EOL;
                        }
                    }else{
                        if($indexed_root){
                            // root level indexed array becomes sectionless
                            $sectionless .= "{$rootkey}[] = $value" . PHP_EOL;
                        }else{
                            // plain values within root level sections
                            $out .= "$key = $value" . PHP_EOL;
                        }
                    }
                }
    
            }else{
                // root level sectionless values
                $sectionless .= "$rootkey = $rootvalue" . PHP_EOL;
            }
        }
        return $sectionless.$out;
    }


function createjson($filename)
{
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: attachment; filename="node_info.json');

        $json_array = [];

        $json_array["nodeLocation"] = $_POST['nodeLocation'];
        $json_array["hidden"] = FALSE;
        $json_array["sysop"] = $_POST['sysop'];

        foreach ($_POST['toneToTalkgroup'] as $key => $value) {

                $ctcss_id = str_replace(".", "_", $value);
                $json_array["toneToTalkgroup"][$value] = intval($_POST["toneToTalkgroup_" . $ctcss_id]);

        }

        $json_array["qth"][0]["name"] = $_POST['name'];
        $json_array["qth"][0]["pos"]["lat"] = $_POST['lat'];
        $json_array["qth"][0]["pos"]["long"] = $_POST['long'];
        $json_array["qth"][0]["pos"]["loc"] = $_POST['Locator'];

        $json_array["qth"][0]["pos"]["loc"] = $_POST['Locator'];


        // RX section
        $json_array["qth"][0]["rx"][$_POST["RxL"]]["name"] = "Rx1";
        $json_array["qth"][0]["rx"][$_POST["RxL"]]["freq"] = floatval($_POST['RX_freq']);
        $json_array["qth"][0]["rx"][$_POST["RxL"]]["sqlType"] = implode(" , ", $_POST['SQL_TYPE']);

        $json_array["qth"][0]["rx"][$_POST["RxL"]]["ant"]["comment"] = $_POST['ant_comment'];
        $json_array["qth"][0]["rx"][$_POST["RxL"]]["ant"]["height"] = $_POST['anth'];
        $json_array["qth"][0]["rx"][$_POST["RxL"]]["ant"]["dir"] = $_POST['Adir'];

        // tx Section

        $json_array["qth"][0]["tx"][$_POST["Txl"]]["name"] = "Tx1";
        $json_array["qth"][0]["tx"][$_POST["Txl"]]["freq"] = floatval($_POST['TX_freq']);
        $json_array["qth"][0]["tx"][$_POST["Txl"]]["pwr"] = $_POST['power'];

        $json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["comment"] = $_POST['ant_comment'];
        $json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["height"] = $_POST['anth'];
        $json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["dir"] = $_POST['Adir'];
        $json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["gain"] = $_POST['antg'];
        $json_array["qth"][0]["tx"][$_POST["Txl"]]["ant"]["Antenna_type"] = $_POST['antt'];


        return (json_encode($json_array, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
function file_name($file_name) {
//        $command = "sudo cp /etc/svxlink/".$file_name." /etc/svxlink/".$file_name."bak";
//        echo exec($command);
        $file_and_path = "/etc/svxlink/".$file_name;
        return($file_and_path);
}
// should filename include path?

function file_backup($file_name){
        $command = "sudo cp /etc/svxlink/".$file_name." /etc/svxlink/".$file_name.".".date('d-m-Y_hia');
        echo exec($command);
        return;
}
function file_replace($file_name){
        $command = "sudo cp -r /var/www/htmp/php_work/".$file_name." /etc/svxlink/".$file_name;
        echo exec($command);
}
      // Refresh iframe on save
      function refreshIframe() {

        echo '<script>';
        echo 'function refreshEditIframe() {';
        echo '  var iframe = document.getElementById("editIframe");';
        echo '  iframe.src = iframe.src;';
        echo '}';  
        echo '</script>';
      
      
       
      }
      function displayEditor($content) {
        echo '<textarea name="content">'.$content.'</textarea>';

      }

     //function checkAuth($username, $password) {
     //  // Check if received values match PHP_AUTH_USER and PHP_AUTH_PW
     //  if ($username == PHP_AUTH_USER && $password == PHP_AUTH_PW) {
     //          // Success
     //          return 'AUTHORISED';            
     //      
     //          } else {
     //          
     //          return "UNAUTHORISED";
     //   
     //  }
     // }
    
?>
