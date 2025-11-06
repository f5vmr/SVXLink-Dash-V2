<?php
$useragent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

$is_mobile = false;

// Modern mobile indicators
if (preg_match('/mobile|android|iphone|ipod|blackberry|phone|opera mini|windows phone|iemobile|kindle|silk/i', $useragent)) {
    $is_mobile = true;
}

// Tablets (treat separately if you wish)
elseif (preg_match('/ipad|tablet|playbook|silk/i', $useragent)) {
    $is_mobile = true; // or false if you prefer full layout
}

if ($is_mobile) {
    echo '<link rel="stylesheet" type="text/css" media="screen and (max-width: 829px)" href="css/css-mini.php">' . PHP_EOL;
} else {
    echo '<link rel="stylesheet" type="text/css" media="screen and (min-width: 830px)" href="css/css.php">' . PHP_EOL;
}


