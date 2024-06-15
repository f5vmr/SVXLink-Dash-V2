<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Configuration</title>
    <style>
        .config-line {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h2>Edit Configuration</h2>
    <form action="process_config.php" method="post">
        <?php
        // Read svxlink.conf file
        $config_file = 'svxlink.conf';
        $lines = file($config_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Display each line as an editable input field
        foreach ($lines as $index => $line) {
            // Escape HTML entities to prevent XSS attacks
            $escaped_line = htmlspecialchars($line);
            echo '<div class="config-line">';
            echo "<label for='line{$index}'>Line {$index}:</label>";
            echo "<input type='text' id='line{$index}' name='lines[]' value='{$escaped_line}' size='80'>";
            echo '</div>';
        }
        ?>
        <br>
        <input type="submit" value="Save Changes">
    </form>
</body>
</html>

