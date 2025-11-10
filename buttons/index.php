<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    </script>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
    <center>
    <fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
            <center>
            <h1 id="svxlink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Button Configurator</h1>
            <h3 style="color:#00aee8;font: 12pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">To comment '#' a line, clear the checkbox</h3>

            <?php
// button_editor.php - Enhanced: dropdown colors, clear row buttons, max KEY1–KEY20

$configFile = __DIR__ . '/buttons.config.php';
$maxKeys = 20;

// --------------------------------------------
// Load config file as lines
// --------------------------------------------
$lines = file($configFile, FILE_IGNORE_NEW_LINES);

$buttons = [];
$pattern = '/^(\s*)(\/\/)?\s*define\("KEY(\d+)",\s*array\(\s*\'(.*?)\'\s*,\s*\'(.*?)\'\s*,\s*\'(.*?)\'\s*\)\s*\);/';

$colorSet = [];

foreach ($lines as $i => $line) {
    if (preg_match($pattern, $line, $m)) {
        $keyNum = intval($m[3]);
        if ($keyNum > $maxKeys) continue;

        $buttons[$keyNum] = [
            'key' => $keyNum,
            'indent' => $m[1],
            'commented' => !empty($m[2]),
            'label' => trim($m[4]),
            'code' => trim($m[5]),
            'color' => trim($m[6]),
            'line_index' => $i
        ];

        if ($m[6] !== '' && !in_array($m[6], $colorSet)) {
            $colorSet[] = trim($m[6]);
        }
    }
}

sort($colorSet);

$defaultColors = ['red','green','blue','purple','orange','yellow','grey','black','white'];
foreach ($defaultColors as $c) {
    if (!in_array($c, $colorSet)) $colorSet[] = $c;
}

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

// --------------------------------------------
// Save
// --------------------------------------------
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
            $idx = $buttons[$key]['line_index'];
            $lines[$idx] = $buttons[$key]['indent'] . $line;
        } else {
            $lines[] = $line;
        }
    }

    file_put_contents($configFile, implode("\n", $lines) . "\n");
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Button Editor</title>
<style>
    body { font-family: Arial; background:#eee; padding:10px; }
    table { border-collapse: collapse; width:100%; max-width:560px; background:white; font-size:12px; }
    th, td { border:1px solid #ccc; padding:4px 6px; }
    input[type=text] { width:98%; padding:2px; font-size:11px; }
    select { width:100%; padding:2px; font-size:11px; }
    .clear-btn { font-size:10px; padding:2px 4px; }
</style>
<script>
function clearRow(k) {
    document.getElementsByName('label['+k+']')[0].value='';
    document.getElementsByName('code['+k+']')[0].value='';
    document.getElementsByName('color['+k+']')[0].value='';
}
</script>
</head>
<body>
<h2>Button Configuration Editor</h2>
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
<td><button type="button" class="small" onclick="clearRow(<?=$b['key']?>)">Clear</button></td>
</tr>
<?php endforeach; ?>
</table>
<br>
<button type="submit">Save</button>
</form>
</body>
</html>

    
