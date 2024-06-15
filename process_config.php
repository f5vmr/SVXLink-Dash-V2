<?php
// Check if form is submitted and lines are posted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lines'])) {
    // Sanitize each line
    $modified_lines = array_map('trim', $_POST['lines']);

    // Save modified lines back to svxlink.conf
    $config_file = 'svxlink.conf';
    $file = fopen($config_file, 'w');

    foreach ($modified_lines as $line) {
        fwrite($file, $line . "\n");
    }

    fclose($file);

    echo "Changes saved successfully.";
} else {
    echo "No data received or invalid request.";
}


