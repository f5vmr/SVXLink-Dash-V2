<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the functions file
include '../include/functions.php';

// Check if the action parameter is set to fetch_log
if (isset($_GET['action']) && $_GET['action'] === 'fetch_log') {
    // Get last file offset from client
    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    // Fetch the log tail content and the new offset
    $result = getLogContent($offset);

    // Return JSON so JS can handle both new lines and offset
    header('Content-Type: application/json');
    echo json_encode($result);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
    <style type="text/css">
        body {
            background-color: #eee;
            font-size: 18px;
            font-family: Arial;
            font-weight: 300;
            margin: 2em auto;
            max-width: 40em;
            line-height: 1.5;
            color: #444;
            padding: 0 0.5em;
        }
        h1 {
            color: #00aee8;
            font: 18pt arial, sans-serif;
            font-weight: bold;
            text-shadow: 0.25px 0.25px gray;
        }
        #log {
            text-align: left;
            background-color: #111;
            color: #0f0;
            border: 1px solid #333;
            padding: 5px;
            font-family: monospace;
            font-size: 11px;
            height: 400px;
            width: 540px;
            overflow-y: scroll;
            white-space: pre;
            margin: 0 auto;
            border-radius: 8px;
        }
        fieldset {
            border: #3083b8 2px groove;
            box-shadow: 5px 5px 20px #999;
            background-color: #f1f1f1;
            width: 555px;
            margin-top: 15px;
            border-radius: 10px;
        }
        .log-container {
            padding: 10px;
            width: 550px;
            background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);
            border-radius: 10px;
            border: 1px solid LightGrey;
            margin: auto;
            line-height: 1.6;
        }
    </style>

    <script>
        let logOffset = 0;

        function fetchLog() {
            fetch(`index.php?action=fetch_log&offset=${logOffset}`)
                .then(response => response.json())
                .then(data => {
                    const logElem = document.getElementById('log');
                    const isAtBottom = logElem.scrollHeight - logElem.scrollTop <= logElem.clientHeight + 5;

                    // Append new lines if available
                    if (data.content) {
                        logElem.innerText += data.content;
                    }

                    // Update offset
                    logOffset = data.offset;

                    // Auto-scroll only if user was already at the bottom
                    if (isAtBottom) {
                        logElem.scrollTop = logElem.scrollHeight;
                    }
                })
                .catch(error => console.error('Error fetching log:', error));
        }

        // Poll every 2 seconds
        setInterval(fetchLog, 2000);
        window.onload = fetchLog;
    </script>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
    <fieldset>
        <div class="log-container">
            <h1>Live Log Viewer</h1>
            <pre id="log">Loading log...</pre>
        </div>
    </fieldset>
</center>
</body>
</html>
