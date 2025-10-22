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
    </style>
</head>

<body style="background-color: #e1e1e1; font: 11pt arial, sans-serif;">
  <div style="display: flex; justify-content: center; margin-top: 15px;">
    <fieldset
      style="border: #3083b8 2px groove;
             box-shadow: 5px 5px 20px #999;
             background-color: #f1f1f1;
             width: 555px;
             font-size: 13px;
             border-radius: 10px;">
      <div
        style="padding: 0;
               width: 550px;
               background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);
               border-radius: 10px;
               border: 1px solid LightGrey;
               margin: 4px 0;
               line-height: 1.6;
               white-space: normal;
               text-align: center;">

        <?php
        if ($_SESSION['auth'] === 'AUTHORISED') {
            echo '<h1 id="authorise" style="color:#00ff00;font:18pt arial,sans-serif;font-weight:bold;text-shadow:0.25px 0.25px gray;">Authorised</h1>';
        } else {
            echo '<h1 id="authorise" style="color:#00aee8;font:18pt arial,sans-serif;font-weight:bold;text-shadow:0.25px 0.25px gray;">Authorise</h1>';
        }

        // --- Authentication Logic ---
        function checkAuth($username, $password)
        {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

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

        <form method="POST" style="margin-top: 10px;">
          <label>Username:
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>">
          </label><br>
          <label>Password:
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password ?? ''); ?>">
          </label><br>
          <input type="submit" value="Submit">
        </form>

      </div>
    </fieldset>
  </div>
</body>

</html>
