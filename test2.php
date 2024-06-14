<?php
// Your PHP script handling form submission and actions

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['lines'] as $line_number => $line_content) {
        $config['config'][$line_number]['content'] = $line_content;
    }

    // Write updated configuration back to file (if needed)
    $new_config_lines = array_merge($config['header'], array_column($config['config'], 'content'));
    file_put_contents($file_path . $file_name, implode("\n", $new_config_lines) . "\n");

    // Redirect to avoid resubmission on page refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle commenting/uncommenting actions
if (isset($_GET['action']) && ($_GET['action'] === 'comment' || $_GET['action'] === 'uncomment')) {
    $line_to_modify = $_GET['line'];
    $current_content = $config['config'][$line_to_modify]['content'];
    
    // Toggle comment/uncomment based on current content
    if ($_GET['action'] === 'comment') {
        if (substr($current_content, 0, 1) !== '#') {
            $config['config'][$line_to_modify]['content'] = '#' . $current_content;
        }
    } elseif ($_GET['action'] === 'uncomment') {
        if (substr($current_content, 0, 1) === '#') {
            $config['config'][$line_to_modify]['content'] = ltrim($current_content, "# \t");
        }
    }

    // Redirect to avoid resubmission on page refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
