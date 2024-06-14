<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define the DATABASES constant
define("DATABASES", "/var/www/html/databases");

// Include the functions
include '/var/www/html/include/config.php';
include '/var/www/html/include/functions.php';

// Path to the configuration file
$file_path = '/etc/svxlink/';
$file_name = 'svxlink.conf';

try {
    // Parse and insert the configuration file into the database
    $parsed_config = parse_config_with_header($file_path, $file_name);
    $pdo = new PDO('sqlite:' . DATABASES . '/svxlink.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("CREATE TABLE IF NOT EXISTS config_lines (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        line_number INTEGER NOT NULL,
        type TEXT NOT NULL,
        content TEXT NOT NULL
    )");
    insert_config_with_header_into_db($parsed_config, $pdo);

    // Handle form submission to update the configuration
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_POST['lines'] as $line_number => $line_content) {
            $stmt = $pdo->prepare("UPDATE config_lines SET content = :content WHERE line_number = :line_number");
            $stmt->execute([
                ':content' => $line_content,
                ':line_number' => $line_number
            ]);
        }
        // Redirect to avoid resubmission on page refresh
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // Fetch configuration lines from the database
    $stmt = $pdo->query("SELECT line_number, type, content FROM config_lines ORDER BY line_number");
    $config_lines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
} catch (Exception $e) {
    echo "General error: " . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Configuration</title>
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
    <style>
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
            position: relative;
            width: 205px;
            overflow: hidden;
            direction: ltl;
        }
        textarea {
            background-color: #111;
            border: 1px solid #000;
            color: #ffffff;
            padding: 1px;
            font-family: courier new;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<center>
    <fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
            <center>
                <h1 id="svxlink" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">SVXLink Configurator</h1>

                <h2>Edit Configuration</h2>
                <form method="post">
                    <table>
                        <thead>
                            <tr>
                                <th>Line Number</th>
                                <th>Type</th>
                                <th>Content</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($config_lines as $line): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($line['line_number']); ?></td>
                                    <td><?php echo htmlspecialchars($line['type']); ?></td>
                                    <td>
                                        <input type="text" name="lines[<?php echo $line['line_number']; ?>]" value="<?php echo htmlspecialchars($line['content']); ?>" style="width: 100%;">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>
                    <button type="submit">Save Changes</button>
                </form>
            </center>
        </div>
    </fieldset>
</center>
</body>
</html>
