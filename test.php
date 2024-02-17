<?php

// Step 1: Initialize cURL session
$ch = curl_init();

// Step 2: Set cURL options
$url = 'http://192.168.1.213:8181/status';
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Step 3: Execute cURL session
$json_data = curl_exec($ch);

// Step 4: Check for errors
if(curl_error($ch)) {
    echo 'Error fetching data: ' . curl_error($ch);
    exit;
}

// Step 5: Close cURL session
curl_close($ch);

// Step 6: Decode JSON data into an associative array
$data_array = json_decode($json_data, true);

// Rest of the code remains the same
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

// Present the structured array in a tabular form using HTML
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
