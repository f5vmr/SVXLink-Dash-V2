<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once "../include/functions.php";
include_once "../include/config.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="/css/css.php" type="text/css" rel="stylesheet"/>
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
        h1 { line-height: 1.2; }
        fieldset {
            border:#3083b8 2px groove;
            box-shadow:5px 5px 20px #999;
            background-color:#f1f1f1;
            width:555px;
            margin: 15px auto 0 auto;
            font-size:13px;
            border-radius: 10px;
        }
        .container {
            padding: 0.5em;
            background: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);
            border-radius: 10px;
            border: 1px solid LightGrey;
            text-align: center;
        }
        input[type="text"], input[type="password"] {
            font-size: 14px;
            padding: 4px;
            margin: 4px 0;
        }
        input[type="submit"] {
            font-size: 14px;
            padding: 4px 10px;
        }
    </style>
</head>
<body>
<center>
    <fieldset>
        <div class="container">
            <?php if ($_SESSION['auth'] === 'AUTHORISED') { ?>
                <h1 style="color:#00ff00;font-weight:bold;">Authorised</h1>
            <?php } else { ?>
                <h1 style="color:#00aee8;font-weight:bold;">Authorise</h1>
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
                    <p style="color:red;">Incorrect username or password.</p>
                <?php } ?>
                <form method="POST">
                    Username:<br>
                    <input type="text" name="username" required><br>
                    Password:<br>
                    <input type="password" name="password" required><br>
                    <input type="submit" value="Submit">
                </form>
            <?php } ?>
        </div>
    </fieldset>
</center>
</body>
</html>
