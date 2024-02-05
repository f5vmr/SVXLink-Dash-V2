<?php
session_start();
include_once "../include/functions.php";
include_once "../include/config.php";

function checkAuth($username, $password) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if received values match PHP_AUTH_USER and PHP_AUTH_PW
    if ($username == PHP_AUTH_USER && $password == PHP_AUTH_PW) {
        // Success
        $_SESSION['auth'] = 'AUTHORISED';
        echo "Authorization successful. Session auth set to AUTHORISED.<br>";
    } else {
        $_SESSION['auth'] = 'UNAUTHORISED';
        echo "Authorization failed. Session auth set to UNAUTHORISED.<br>";
    }

    session_write_close();

    return $_SESSION['auth'];
}


$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $_SESSION['auth'] = checkAuth($username, $password);
    // Redirect to the same page to refresh
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="/css/css.php" type="text/css" rel="stylesheet" />
    <style type="text/css">
        /* Your CSS styles... */
    </style>
    <!-- JavaScript to automatically refresh the page -->
    <script>
        function refreshPage() {
            location.reload();
        }
    </script>
</head>

<body style="background-color: #e1e1e1; font: 11pt arial, sans-serif;">
    <center>
        <fieldset style="border:#3083b8 2px groove;box-shadow:5px 5px 20px #999; background-color:#f1f1f1; width:555px;margin-top:15px;margin-left:0px;margin-right:5px;font-size:13px;border-top-left-radius: 10px; border-top-right-radius: 10px;border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
            <div style="padding:0px;width:550px;background-image: linear-gradient(to bottom, #e9e9e9 50%, #bcbaba 100%);border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px;border: 1px solid LightGrey;margin-left:0px; margin-right:0px;margin-top:4px;margin-bottom:0px;line-height:1.6;white-space:normal;">
                <center>
                    <?php if ($_SESSION['auth'] == 'AUTHORISED') : ?>
                        <h1 id="authorise" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Authorised</h1>
                    <?php else : ?>
                        <h1 id="authorise" style="color:#00aee8;font: 18pt arial, sans-serif;font-weight:bold; text-shadow: 0.25px 0.25px gray;">Authorise</h1>
                    <?php endif; ?>

                    <?php var_dump($_SESSION['auth']) . $username . $password; ?>

                    <form method="POST">
                        Username:<input type="text" id="username" name="username" value="<?php echo $username; ?>"><br>
                        Password:<input type="password" id="password" name="password" value="<?php echo $password; ?>" autocomplete="off" <br>
                        <input type="submit" value="Submit" onclick="refreshPage()">
                    </form>
                </center>
            </div>
        </fieldset>
    </center>
</body>

</html>


