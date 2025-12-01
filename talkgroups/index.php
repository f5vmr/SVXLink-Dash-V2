<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "Page is loading<br>";

include_once __DIR__ . '/../include/talkgroups.php';

echo "Include loaded successfully<br>";

if (function_exists('renderTalkgroupInputs')) {
    echo "Function renderTalkgroupInputs exists<br>";
} else {
    echo "Function renderTalkgroupInputs missing<br>";
}

