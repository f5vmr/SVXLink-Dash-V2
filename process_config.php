<?php
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $config_file = 'svxlink.conf';
    $lines = file($config_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Read the updated lines from POST data
    $updated_lines = $_POST['lines'];

    // Update the configuration file
    foreach ($updated_lines as $index => $line_content) {
        // Use the line index to update the specific line in the file
        $lines[$_POST['line_index'][$index]] = $line_content;
    }

    // Write the updated lines back to the config file
    file_put_contents($config_file, implode("\n", $lines) . "\n");

    echo "Configuration file updated successfully.";
} else {
    echo "No data received.";
}



