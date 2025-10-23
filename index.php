<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['auth'] = 'UNAUTHORISED';

include "include/settings.php";
include "include/config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="index,follow">
    <meta name="language" content="English">
    <meta name="generator" content="SVXLink">
    <meta name="author" content="G4NAB, SP2ONG, SP0DZ">
    <meta name="description" content="Dashboard for SVXLink by G4NAB, SP2ONG, SP0DZ">
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="expires" content="0">
    <link rel="shortcut icon" href="images/favicon.ico" type="image/png">
    <title><?php echo $callsign; ?> Ver 2.1 Dashboard</title>

    <!-- jQuery & Scripts -->
    <script src="scripts/jquery.min.js"></script>
    <script src="scripts/functions.js"></script>
    <script src="scripts/pcm-player.min.js"></script>
    <script>
        $.ajaxSetup({ cache: false });
    </script>

    <!-- Featherlight lightbox -->
    <link href="css/featherlight.css" rel="stylesheet">
    <script src="scripts/featherlight.js"></script>

    <style>
        body {
            background-color: #e1e1e1;
            font: 11pt Arial, sans-serif;
        }
        .container { max-width: 1000px; margin: auto; }
        .header { display: flex; align-items: center; justify-content: space-between; padding: 10px; }
        .header .img img { height: 64px; }
        .header .text { text-align: right; color: PaleBlue; }
        .button.link { padding: 8px 12px; font-weight: bold; cursor: pointer; }
        .nav { margin-bottom: 20px; }
        .content { margin: 10px 0; }
        .content2 { margin-top: 20px; }
        iframe { border: none; }
    </style>
</head>
<body>
<div class="container">

    <!-- Header -->
    <div class="header">
        <div class="img"><img src="images/svxlink.ico" alt="SVXLink"></div>
        <div class="text">
            <h1 style="margin:0; font-size:32px; letter-spacing:4px;"><?php echo $callsign; ?></h1>
            <h2 style="margin:0; font-size:18px;"><?php echo $fmnetwork; ?></h2>
        </div>
    </div>

    <!-- Top menu -->
    <?php include_once "include/top_menu.php"; ?>

    <!-- RX Monitor Button -->
    <?php if (isProcessRunning('node')): ?>
        <div class="content">
            <button class="button link" onclick="playAudioToggle(8000, this)">
                <img src="images/speaker.png" alt="" style="vertical-align:middle">
                &nbsp;RX Monitor
            </button>
        </div>
    <?php endif; ?>

    <!-- Buttons include -->
    <?php
    if (MENUBUTTON == "TOP") {
        include_once "include/buttons.php";
    }
    ?>

    <!-- Main table layout -->
    <table style="width:100%; border-collapse: collapse; margin-bottom:0;">
        <tr>
            <!-- Left nav / status -->
            <td width="200px" valign="top" class="hide">
                <div class="nav" id="statusInfo">
                    <?php include "include/status.php"; ?>
                </div>
                <script>
                    function reloadStatusInfo() {
                        $("#statusInfo").load("include/status.php", function() {
                            setTimeout(reloadStatusInfo, 3000);
                        });
                    }
                    setTimeout(reloadStatusInfo, 3000);
                </script>
            </td>

            <!-- Main content -->
            <td valign="top">
                <div class="content">
                    <div id="LastHeard">
                        <?php include "include/lh.php"; ?>
                    </div>

                    <?php if(URLSVXRAPI != ""): ?>
                        <div id="svxref">
                            <?php include "include/svxref.php"; ?>
                        </div>
                        <script>
                            function reloadSVXREF() {
                                $("#svxref").load("include/svxref.php", function() {
                                    setTimeout(reloadSVXREF, 90000);
                                });
                            }
                            setTimeout(reloadSVXREF, 90000);
                        </script>
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>

    <!-- Bottom buttons -->
    <?php
    if (MENUBUTTON == "BOTTOM") {
        include_once "include/buttons.php";
    }
    ?>

    <!-- System Info -->
    <div class="content2" id="sysInfo">
        <?php include "include/system.php"; ?>
    </div>
    <script>
        function reloadSysInfo() {
            $("#sysInfo").load("include/system.php", function() {
                setTimeout(reloadSysInfo, 15000);
            });
        }
        setTimeout(reloadSysInfo, 15000);
    </script>

    <!-- Footer -->
    <div style="text-align:center; font-size:7pt; margin-top:15px;">
        SvxLink Dashboard Ver 2.1 &copy; G4NAB, SP2ONG, SP0DZ
        <?php
            $cdate=date("Y");
            echo ($cdate > "2021") ? "2021-" . $cdate : $cdate;
        ?>
    </div>

</div>
</body>
</html>
