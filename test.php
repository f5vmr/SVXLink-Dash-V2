<?php
include "include/config.php";
function checkAuth($username, $password)
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Debugging output before authentication
    echo "Before authentication:<br>";
    echo "Session auth: {$_SESSION['auth']}<br>";
    
    // Check if received values match PHP_AUTH_USER and PHP_AUTH_PW
    if ($username == PHP_AUTH_USER && $password == PHP_AUTH_PW) {
        // Success
        $_SESSION['auth'] = "AUTHORISED";
    } else {
        $_SESSION['auth'] = "UNAUTHORISED";
    }
    
    // Debugging output after authentication
    echo "After authentication:<br>";
    echo "Session auth: {$_SESSION['auth']}<br>";
    
    session_write_close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process form submission
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    checkAuth($username, $password);

    // Debugging output after form submission
    echo "After form submission:<br>";
    echo "Session auth: {$_SESSION['auth']}<br>";
    echo "Username: $username<br>";
    echo "Password: $password<br>";
}
?>
<form method="POST">
                    Username:<input type="text" id="username" name="username" value="<?php echo $username; ?>"><br>
                    Password:<input type="password" id="password" name="password" value="<?php echo $password; ?>"><br>
                    <input type="submit" value="Submit">
                </form>
            </center>
        </div>
    </fieldset>
</body>
</html>
