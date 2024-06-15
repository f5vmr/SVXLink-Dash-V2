<?php
require('vendor/autoload.php'); // Path to phpseclib autoload file

use phpseclib\Net\SSH2;

$ssh = new SSH2('example.com');
if (!$ssh->login('username', 'password')) {
    exit('Login Failed');
}

// Execute alsamixer command
$ssh->exec('alsamixer');

// More commands or actions here as needed

echo "Command executed successfully.";

?>
