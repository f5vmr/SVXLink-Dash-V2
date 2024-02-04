<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php

echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
echo "the full works";
echo $_SERVER['PHP_SELF'];
echo "php_self<br>";
echo $_SERVER['SERVER_NAME'];
echo "server_name<br>";
echo $_SERVER['HTTP_HOST'];
echo "http_host<br>";
echo $_SERVER['HTTP_REFERER'];
echo "http_referer<br>";
echo $_SERVER['HTTP_USER_AGENT'];
echo "http_user_agent<br>";
echo $_SERVER['SCRIPT_NAME'];
echo "script_name<br>";
?>

</body>
</html>