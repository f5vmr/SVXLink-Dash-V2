<?php
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
exec('sudo cp /etc/svxlink/node_info.json /etc/svxlink/node_info.bak');
$nodeInfoFile = '/etc/svxlink/node_info.json';  

//if (fopen($nodeInfoFile,'r'))
//{
//	$filedata = file_get_contents($nodeInfoFile);
//	$nodeInfo = json_decode($filedata,true);
//
//	build_ini_string(array($nodeInfo));
//	print_r($nodeInfo);
//};

if (fopen($nodeInfoFile, 'r')) {
    $filedata = file_get_contents($nodeInfoFile);
    $nodeInfo = json_decode($filedata, true);

    build_ini_string(array($nodeInfo));
    foreach ($nodeInfo as $key => $value) {
        if (is_array($value)) {
            echo $key . ":" . PHP_EOL;
            foreach ($value as $subKey => $subValue) {
                echo "    $subKey: $subValue" . PHP_EOL;
            }
        } else {
            echo "$key: $value\n";
        }
    }
}  
      
