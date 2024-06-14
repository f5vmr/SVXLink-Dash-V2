<?php
// Initialize or load configuration
$file_path = '/etc/svxlink/';
$file_name = 'svxlink.conf';
$config = parse_config($file_path, $file_name);

// Handle commenting/uncommenting actions
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && ($_GET['action'] === 'comment' || $_GET['action'] === 'uncomment')) {
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

    // Write updated configuration back to file
    $new_config_lines = array_merge($config['header'], array_column($config['config'], 'content'));
    file_put_contents($file_path . $file_name, implode("\n", $new_config_lines) . "\n");

    // Redirect to avoid resubmission on page refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Function to display configuration table
function display_config($config) {
    foreach ($config['config'] as $i => $entry) {
        $content = htmlspecialchars($entry['content']);
        $commented = (substr($content, 0, 1) === '#');
        $toggleAction = $commented ? 'uncomment' : 'comment';

        echo "<tr>";
        echo "<td>{$content}</td>"; // Display content
        echo "<td>";
        if ($commented) {
            // Display commented line with leading '#'
            echo "<input type='text' name='lines[{$i}]' value='{$content}' style='width: 100%;'>";
        } else {
            // Display editable text input for content
            echo "<input type='text' name='lines[{$i}]' value='{$content}' style='width: 100%;'>";
        }
        echo "</td>";

        echo "<td>";
        // Display toggle button for commenting/uncommenting lines
        if ($commented) {
            echo "<a href='?action=uncomment&line={$i}' style='text-decoration: none; color: #00aee8; font-weight: bold;'>[Uncomment]</a>";
        } else {
            echo "<a href='?action=comment&line={$i}' style='text-decoration: none; color: #00aee8; font-weight: bold;'>[Comment]</a>";
        }
        echo "</td>";

        echo "</tr>";
    }
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
