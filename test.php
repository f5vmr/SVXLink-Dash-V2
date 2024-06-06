<?php
// Include the functions
include '../include/functions.php';

// Path to the configuration file
$file_path = '/etc/svxlink/';
$file_name = 'svxlink.conf';

// Parse the configuration file
$config = parse_config($file_path, $file_name);

// Display the configuration
display_config($config);

// Edit the configuration (Example: uncomment line 10)
$line_number = 10;
$new_content = ltrim($config[$line_number]['content'], '#');
edit_config($config, $line_number, $new_content, false);

// Save the edited configuration
save_config($config, $file_path, $file_name);

// Display the updated configuration
display_config($config);

      
