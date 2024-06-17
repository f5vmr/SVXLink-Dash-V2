<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the functions file
include '../include/functions.php';

// Check if the action parameter is set to fetch_log
if (isset($_GET['action']) && $_GET['action'] === 'fetch_log') {
    // Output the log content and exit to avoid rendering the rest of the page
    echo getLogContent();
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
        h1, h2, h3 {
            line-height: 1.2;
        }
        a {
            color: #607d8b;
        }
        .highlighter-rouge {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: .2em;
            font-size: .8em;
            overflow-x: auto;
            padding: .2em .4em;
        }
        pre {
            margin: 0;
            padding: .6em;
            overflow-x: auto;
        }
        #player {
            position:relative;
            width:205px;
            overflow: hidden;
            direction: ltl;
        }
        textarea {
            background-color: #111;
            border: 1px solid #000;
            color: #ffffff;
            padding: 1px;
            font-family: courier new;
            font-size:10px;
        }
        pre {
            white-space: pre-wrap; /* Ensures that long lines wrap */
            word-wrap: break-word; /* Ensures that long words wrap */
        }
    </style>
    <script>
        function fetchLog() {
            fetch('index.php?action=fetch_log')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('log').innerHTML = data;
                })
                .catch(error => console.error('Error fetching log:', error));
        }

        // Fetch log every 5 seconds
        setInterval(fetchLog, 5000);

        // Initial fetch
        window.onload = fetchLog;
    </script>
</head>
<body style="background-color: #e1e1e1;font: 11pt arial, sans-serif;">
<center>
    <fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
            <h1 id="web-audio-peak-meters" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Live Log Viewer</h1>
            <pre id="log" style="text-align: left;">Loading log...</pre>

        </div>
    </fieldset>
</center>
<p style="margin: 0 auto;"></p>
<p style="margin-bottom:-2px;"></p>
</body>
</html>
