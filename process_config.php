<?php
// Check if form is submitted and file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['config_file']['tmp_name'])) {
    // File handling
    $file_tmp = $_FILES['config_file']['tmp_name'];
    $file_name = $_FILES['config_file']['name'];
    $file_type = $_FILES['config_file']['type'];
    
    // Check file type (must be text/plain)
    if ($file_type !== 'text/plain') {
        die('Error: Only .txt files are allowed.');
    }

    // Read lines from uploaded file
    $lines = file($file_tmp, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Initialize variables to store the parts of the file
    $header_lines = [];
    $sections = [];
    $current_section = null;

    // Iterate through each line in the file
    foreach ($lines as $line) {
        // Strip any leading/trailing whitespace
        $line = trim($line);

        // Check if the line starts with '#', indicating a comment
        if (strpos($line, '#') === 0) {
            if ($current_section === null) {
                // Add to header lines until a section starts
                $header_lines[] = $line;
            } else {
                // Store comment lines within the current section
                $sections[$current_section]['comments'][] = $line;
            }
        } elseif (strpos($line, '[') === 0 && strpos($line, ']') !== false) {
            // Start of a new section
            $current_section = trim($line, '[]');
            $sections[$current_section] = ['lines' => [], 'comments' => []];
        } elseif (!empty($line)) {
            // Non-empty line, part of current section
            if ($current_section === null) {
                // Collect lines before any section starts
                $header_lines[] = $line;
            } else {
                $sections[$current_section]['lines'][] = $line;
            }
        }
    }

    // Process form inputs
    $section_to_edit = isset($_POST['section_to_edit']) ? $_POST['section_to_edit'] : '';
    $edit_comments = isset($_POST['edit_comments']) ? $_POST['edit_comments'] : '';

    // Modify the configuration based on form inputs
    if (!empty($section_to_edit) && isset($sections[$section_to_edit])) {
        // Modify comments if provided
        if (!empty($edit_comments)) {
            $sections[$section_to_edit]['comments'] = explode("\n", $edit_comments);
        }
    }

    // Save modified configuration to a new file
    $new_config_file = 'new_config.txt';
    $file = fopen($new_config_file, 'w');

    // Write header lines
    foreach ($header_lines as $line) {
        fwrite($file, $line . "\n");
    }

    // Write each section
    foreach ($sections as $section => $content) {
        fwrite($file, "\n[{$section}]\n");
        foreach ($content['lines'] as $line) {
            fwrite($file, $line . "\n");
        }
        foreach ($content['comments'] as $comment) {
            fwrite($file, $comment . "\n");
        }
    }

    fclose($file);

    echo "Modified configuration saved to {$new_config_file}";
} else {
    // Display the HTML form if no file is uploaded
    include('test4.html');
}
?>
