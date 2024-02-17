<?php

// Step 1: Fetch JSON data from the URL
$url = 'http://192.168.1.213:8181/status';
$json_data = file_get_contents($url);

// Step 2: Decode JSON data into an associative array
$data_array = json_decode($json_data, true);

// Step 3: Extract necessary information and create a structured array
$table_data = array();
foreach ($data_array as $node) {
    // Extract relevant data from each node
    $node_id = $node['id'];
    $node_name = $node['name'];
    $node_status = $node['status'];
    
    // Create a structured array for tabular presentation
    $table_data[] = array(
        'ID' => $node_id,
        'Name' => $node_name,
        'Status' => $node_status
    );
}

// Step 4: Present the structured array in a tabular form using HTML
echo '<table border="1">';
echo '<tr><th>ID</th><th>Name</th><th>Status</th></tr>';
foreach ($table_data as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td>' . $cell . '</td>';
    }
    echo '</tr>';
}
echo '</table>';

?>
