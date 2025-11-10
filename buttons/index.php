<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

include_once __DIR__ . '/../include/config.buttons.php';
if (!file_exists(__DIR__ . '/../include/config.buttons.php')) {
    die("config.buttons.php not found at " . realpath(__DIR__ . '/../include/config.buttons.php'));
}

?>
<!DOCTYPE html>
<html lang="en">
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
        textarea {
            background-color: #111;
            border: 1px solid #000;
            color: #ffffff;
            padding: 1px;
            font-family: courier new;
            font-size:10px;
        }
    </style>
    <script type="text/javascript">
        function reloadPage() {
            window.location.href = window.location.pathname + "?reloaded=true";
        }
        function clearRow(k) {
            document.getElementsByName('label['+k+']')[0].value='';
            document.getElementsByName('code['+k+']')[0].value='';
            document.getElementsByName('color['+k+']')[0].value='';
        }
    </script>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
    <center>
    <fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-radius: 10px;">
        <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;border: 1px solid LightGrey;margin-top:4px;line-height:1.6;white-space:normal;">
            <center>
            <h1 id="svxlink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Button Configurator</h1>
            <h3 style="color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">To comment '//' a line, clear the checkbox</h3>

<?php
$configFile = __DIR__ . '/../include/config.buttons.php';


$maxKeys = 20;

// Load config file lines
$lines = file($configFile, FILE_IGNORE_NEW_LINES);

$buttons = [];
$colorSet = [];

// Parse each line
foreach ($lines as $i => $line) {
    if (preg_match('/^\s*(\/\/)?\s*define\("KEY(\d+)",\s*array\s*\(\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*,\s*\'([^\']*)\'\s*\)\s*\)\s*;/', $line, $m)) {
        $commented = !empty($m[1]);
        $keyNum = intval($m[2]);
        if ($keyNum > $maxKeys) continue;

        $buttons[$keyNum] = [
            'key' => $keyNum,
            'indent' => '',
            'commented' => $commented,
            'label' => $m[3],
            'code'  => $m[4],
            'color' => $m[5],
            'line_index' => $i
        ];

        if ($m[5] !== '' && !in_array($m[5], $colorSet)) $colorSet[] = $m[5];
    }
}

// Add default colors
sort($colorSet);
$defaultColors = ['red','green','blue','purple','orange','yellow','grey','black','white'];
foreach ($defaultColors as $c) {
    if (!in_array($c, $colorSet)) $colorSet[] = $c;
}

// Fill missing buttons
for ($k = 1; $k <= $maxKeys; $k++) {
    if (!isset($buttons[$k])) {
        $buttons[$k] = [
            'key' => $k,
            'indent' => '',
            'commented' => true,
            'label' => '',
            'code' => '',
            'color' => '',
            'line_index' => null
        ];
    }
}

// Save updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    for ($key = 1; $key <= $maxKeys; $key++) {
        $label = $_POST['label'][$key] ?? '';
        $code  = $_POST['code'][$key] ?? '';
        $color = $_POST['color'][$key] ?? '';
        $enabled = isset($_POST['enabled'][$key]);

        $line = '';
        if (!$enabled) $line .= '// ';
        $line .= 'define("KEY' . $key . '", array(\'' . $label . '\',\'' . $code . '\',\'' . $color . '\'));';

        if ($buttons[$key]['line_index'] !== null) {
            $lines[$buttons[$key]['line_index']] = $line;
        } else {
            $lines[] = $line;
        }
    }

    file_put_contents($configFile, implode("\n", $lines) . "\n");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<form method="post">
<table>
<tr><th>Key</th><th>Enable</th><th>Label</th><th>Code</th><th>Color</th><th>Clear</th></tr>
<?php foreach ($buttons as $b): ?>
<tr>
<td><?="KEY".$b['key']?></td>
<td><input type="checkbox" name="enabled[<?=$b['key']?>]" <?=$b['commented']?"":"checked"?>></td>
<td><input type="text" name="label[<?=$b['key']?>]" value="<?=htmlspecialchars($b['label'])?>"></td>
<td><input type="text" name="code[<?=$b['key']?>]" value="<?=htmlspecialchars($b['code'])?>"></td>
<td>
<select name="color[<?=$b['key']?>]">
<option value=""></option>
<?php foreach ($colorSet as $c): ?>
<option value="<?=$c?>" <?=$b['color']==$c?"selected":""?>><?=$c?></option>
<?php endforeach; ?>
</select>
</td>
<td><button type="button" onclick="clearRow(<?=$b['key']?>)">Clear</button></td>
</tr>
<?php endforeach; ?>
</table>
<br>
<button type="submit">Save</button>
</form>
</fieldset>
</center>
</body>
</html>
