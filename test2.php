<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the config file using require_once to prevent redefinition errors
require_once 'include/config.inc.php';

// Include other necessary files
include 'include/functions.php';

// Path to the configuration file
$file_path = '/etc/svxlink/';
$file_name = 'svxlink.conf';

$parsed_config = parse_config_with_header($file_path, $file_name);
$pdo = new PDO('sqlite:' . DATABASES . '/svxlink.db');
$pdo->exec("CREATE TABLE IF NOT EXISTS config_lines (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    line_number INTEGER NOT NULL,
    type TEXT NOT NULL,
    content TEXT NOT NULL
)");
insert_config_with_header_into_db($parsed_config, $pdo);

// Display and edit the configuration file
$pdo = new PDO('sqlite:' . DATABASES . '/svxlink.db');

$stmt = $pdo->query("SELECT line_number, type, content FROM config_lines ORDER BY line_number");
$config_lines = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['lines'] as $line_number => $line_content) {
        $stmt = $pdo->prepare("UPDATE config_lines SET content = :content WHERE line_number = :line_number");
        $stmt->execute([
            ':content' => $line_content,
            ':line_number' => $line_number
        ]);
    }
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
        /* Your CSS styles here */
    </style>
</head>
<body>
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
</body>
</html>
