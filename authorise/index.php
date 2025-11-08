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
    <style>
        *, *:before, *:after {
            box-sizing: border-box;
        }

        body {
            background-color: #eee;
            font-size: 18px;
            font-family: Arial, sans-serif;
            font-weight: 300;
            margin: 2em auto;
            max-width: 40em;
            line-height: 1.5;
            color: #444;
            padding: 0 1em;
        }

        fieldset {
            width: 80%;
            max-width: 550px;
            margin: 20px auto;
            border: #3083b8 2px groove;
            border-radius: 10px;
            box-shadow: 5px 5px 20px #999;
            background-color: #f1f1f1;
            padding: 0;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1em;
            width: 100%;
            background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);
            border-radius: 10px;
            border: 1px solid LightGrey;
        }

        h1 {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 0.5em;
            text-shadow: 0.25px 0.25px gray;
        }

        form {
            width: 100%;
            text-align: center;
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            max-width: 300px;
            padding: 0.3em;
            margin: 0.3em 0;
            font-size: 1em;
        }

        input[type="submit"] {
            padding: 0.4em 1em;
            font-size: 1em;
            margin-top: 0.5em;
        }
    </style>
</head>
<body>
    <fieldset>
        <div class="container">
            <?php
            if ($_SESSION['auth'] === 'AUTHORISED') {
                echo '<h1 style="color:#00ff00;">Authorised</h1>';
            } else {
                echo '<h1 style="color:#00aee8;">Authorise</h1>';
            }

            function checkAuth($username, $password)
            {
                if (session_status() == PHP_SESSION_NONE) session_start();
                if ($username == PHP_AUTH_USER && $password == PHP_AUTH_PW) {
                    $_SESSION['auth'] = "AUTHORISED";
                } else {
                    $_SESSION['auth'] = "UNAUTHORISED";
                }
                session_write_close();
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                include_once "../include/config.php";
                $username = $_POST['username'] ?? '';
                $password = $_POST['password'] ?? '';
                checkAuth($username, $password);
            }
            ?>
            <form method="POST">
                <input type="text" id="username" name="username" placeholder="Username" value="<?php echo $username ?? ''; ?>"><br>
                <input type="password" id="password" name="password" placeholder="Password" value="<?php echo $password ?? ''; ?>"><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </fieldset>
</body>
</html>
