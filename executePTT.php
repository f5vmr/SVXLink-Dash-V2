<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['command'] == 'first') {
        // Erster Befehl
        exec( "echo 'O' > /tmp/sql");
        //echo "Erster Befehl ausgeführt!";
    } elseif ($_POST['command'] == 'second') {
        // Zweiter Befehl
        exec("echo 'Z' > /tmp/sql");
        //echo "Zweiter Befehl ausgeführt!";
    }
}
?>