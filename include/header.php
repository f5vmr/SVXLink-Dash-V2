<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['auth'] = 'UNAUTHORISED';
include "include/settings.php";
include "include/config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="generator" content="SVXLink">
    <meta name="Author" content="G4NAB, SP2ONG, SP0DZ">
    <meta name="Description" content="Dashboard for SVXLink by G4NAB, SP2ONG, SP0DZ">
    <meta name="KeyWords" content="SVXLink,G4NAB, SP2ONG, SP0DZ">
    <meta http-equiv="cache-control" content="max-age=0, no-cache, no-store, must-revalidate">
    <meta http-equiv="expires" content="0">
    <meta http-equiv="pragma" content="no-cache">
    <link rel="shortcut icon" href="images/favicon.ico" sizes="16x16 32x32" type="image/png">
    <title><?php echo $title; ?> Ver 2.1 Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="scripts/jquery.min.js"></script>
    <script src="scripts/functions.js"></script>
    <script src="scripts/pcm-player.min.js"></script>
    <script>
      $.ajaxSetup({ cache: false });
    </script>
    <link href="css/featherlight.css" rel="stylesheet">
    <script src="scripts/featherlight.js" charset="utf-8"></script>
</head>
<body>
    <center>
        <fieldset>
            <div class="container"> 
                <div class="header">
                    <div class="parent">
                        <div class="img"><img src="images/svxlink.ico" /></div>
                        <div class="text">
                            <center>
                                <p>
                                    <span class="callsign"> <?php echo $callsign; ?> </span>
                                </p>
                                <p>
                                    <span class="fmnetwork"> <?php echo $fmnetwork; ?> </span>
                                </p>
                            </center>
                        </div>
                    </div>
                </div>
                <?php include_once "include/top_menu.php"; ?>
            </div>
        </fieldset>
    </center>
</body>