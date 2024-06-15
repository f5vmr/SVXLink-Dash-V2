<?php
// Command to execute with sudo
$command = 'sudo alsactl store';

// Execute the command using exec
exec($command, $output, $return_var);

// Check if the command executed successfully
if ($return_var === 0) {
    echo "Settings saved successfully.";
} else {
    echo "Failed to save settings. Error code: $return_var";
    // Output any error messages from the command
    if (!empty($output)) {
        echo "<pre>" . implode("\n", $output) . "</pre>";
    }
}
