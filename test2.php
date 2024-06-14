<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include 'include/config.inc.php';  // Assuming this file defines constants or configurations
include 'include/functions.php';   // Include your functions file

// Path to the configuration file
$file_path = '/etc/svxlink/';
$file_name = 'svxlink.conf';

// Initialize $config array using parse_config function
$config = parse_config($file_path, $file_name);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $_POST['lines'][$line_to_modify] = $config['config'][$line_to_modify]['content'];
    } else {
        foreach ($_POST['lines'] as $line_number => $line_content) {
            $config['config'][$line_number]['content'] = $line_content;
        }

        // Write updated configuration back to file (if needed)
        $new_config_lines = array_merge($config['header'], array_column($config['config'], 'content'));
        file_put_contents($file_path . $file_name, implode("\n", $new_config_lines) . "\n");
    }

    // Redirect to avoid resubmission on page refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Configuration</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type=text] {
            width: 100%;
            box-sizing: border-box;
        }
        .action-column {
            width: 150px; /* Adjust the width as needed */
        }
    </style>
</head>
<body>

<h2>Edit Configuration</h2>

<table>
    <thead>
        <tr>
            <th>Content</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php display_config($config); ?>
    </tbody>
</table>

</body>
</html>
