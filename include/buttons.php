<?php
include_once __DIR__ . "/tools.php";
include_once __DIR__ . "/config.buttons.php";

/*
 * Determine the correct SvxLink DTMF control file from:
 *
 * [GLOBAL]
 * LOGICS=RepeaterLogic,ReflectorLogic
 *
 * or:
 *
 * LOGICS=SimplexLogic,ReflectorLogic
 */
function getDtmfControlFile(string $configFile): ?string
{
    if (!is_readable($configFile)) {
        return null;
    }

    $config = parse_ini_file($configFile, true, INI_SCANNER_RAW);

    if (
        $config === false ||
        !isset($config["GLOBAL"]) ||
        !isset($config["GLOBAL"]["LOGICS"])
    ) {
        return null;
    }

    $logics = array_map(
        "trim",
        explode(",", $config["GLOBAL"]["LOGICS"])
    );

    if (in_array("RepeaterLogic", $logics, true)) {
        return "/dev/shm/repeater_dtmf_ctrl";
    }

    if (in_array("SimplexLogic", $logics, true)) {
        return "/dev/shm/simplex_dtmf_ctrl";
    }

    return null;
}

/*
 * Send a DTMF command to the selected SvxLink control file.
 */
function sendDtmf(string $dtmfCode, ?string $controlFile): bool
{
    if (
        $controlFile === null ||
        !file_exists($controlFile) ||
        !is_writable($controlFile)
    ) {
        return false;
    }

    $command =
        "printf '%s' " .
        escapeshellarg($dtmfCode) .
        " > " .
        escapeshellarg($controlFile);

    exec($command, $output, $returnCode);

    return $returnCode === 0;
}

$dtmfControlFile = getDtmfControlFile(
    "/etc/svxlink/svxlink.conf"
);

$dtmfError = null;

/*
 * Handle DTMF button presses.
 */
for ($i = 1; $i <= 20; $i++) {
    $buttonName = "button$i";
    $keyName = "KEY$i";

    if (
        isset($_POST[$buttonName]) &&
        defined($keyName)
    ) {
        $keyDefinition = constant($keyName);
        $dtmfCode = $keyDefinition[1];

        if (sendDtmf($dtmfCode, $dtmfControlFile)) {
            echo "<meta http-equiv='refresh' content='0'>";
            exit;
        }

        $dtmfError =
            "Unable to send the DTMF command. " .
            "The SvxLink control file is unavailable.";
    }
}

/*
 * Handle the manual DTMF input field.
 */
if (isset($_POST["dtmfsvx"])) {
    $dtmfCode = trim($_POST["dtmfsvx"]);

    if ($dtmfCode !== "") {
        if (sendDtmf($dtmfCode, $dtmfControlFile)) {
            echo "<meta http-equiv='refresh' content='0'>";
            exit;
        }

        $dtmfError =
            "Unable to send the DTMF command. " .
            "The SvxLink control file is unavailable.";
    }
}

/*
 * Handle jmpto commands.
 */
foreach (["jmpto", "jmptoA", "jmptoM"] as $field) {
    if (isset($_POST[$field])) {
        $talkgroup = trim($_POST[$field]);

        if ($talkgroup === "") {
            continue;
        }

        $prefix = ($field === "jmptoM") ? "94" : "91";
        $dtmfCode = $prefix . $talkgroup . "#";

        if (sendDtmf($dtmfCode, $dtmfControlFile)) {
            echo "<meta http-equiv='refresh' content='0'>";
            exit;
        }

        $dtmfError =
            "Unable to send the DTMF command. " .
            "The SvxLink control file is unavailable.";
    }
}
?>

<div class="content">
    <fieldset
        style="
            box-shadow:5px 5px 20px #999;
            background-color:#e8e8e8;
            width:855px;
            margin-top:5px;
            margin-bottom:14px;
            margin-left:6px;
            margin-right:0px;
            font-size:12px;
            border-radius:10px;
        "
    >
        <div
            style="
                padding:0px;
                width:100%;
                background-image:
                    linear-gradient(
                        to bottom,
                        #e9e9e9 50%,
                        #bcbaba 100%
                    );
                border-radius:10px;
                border:1px solid LightGrey;
                white-space:normal;
            "
        >

            <?php if ($dtmfError !== null): ?>
                <div
                    style="
                        color:#a00000;
                        font-weight:bold;
                        text-align:center;
                        padding:6px;
                    "
                >
                    <?php
                    echo htmlspecialchars(
                        $dtmfError,
                        ENT_QUOTES,
                        "UTF-8"
                    );
                    ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <center>
                    <?php
                    /*
                     * Generate buttons dynamically.
                     */
                    for ($i = 1; $i <= 20; $i++) {
                        $keyName = "KEY$i";

                        if (!defined($keyName)) {
                            continue;
                        }

                        $keyDefinition = constant($keyName);

                        if (
                            !isset($keyDefinition[0]) ||
                            $keyDefinition[0] === ""
                        ) {
                            continue;
                        }

                        $label = htmlspecialchars(
                            $keyDefinition[0],
                            ENT_QUOTES,
                            "UTF-8"
                        );

                        $color = isset($keyDefinition[2])
                            ? htmlspecialchars(
                                $keyDefinition[2],
                                ENT_QUOTES,
                                "UTF-8"
                            )
                            : "";

                        echo
                            "<input type='submit' " .
                            "name='button$i' " .
                            "class='$color' " .
                            "value='$label' /> ";
                    }
                    ?>
                </center>
            </form>

            <form
                action=""
                method="post"
                style="margin-top:4px;"
            >
                <center>
                    <label
                        style="
                            text-shadow:
                                1px 1px 1px LightGrey,
                                0 0 0.5em LightGrey,
                                0 0 1em whitesmoke;
                            font-weight:bold;
                            color:#464646;
                        "
                        for="dtmfsvx"
                    >
                        DTMF command (must end with #):
                    </label>

                    <input
                        type="text"
                        id="dtmfsvx"
                        name="dtmfsvx"
                    >

                    <input
                        type="submit"
                        value="Send DTMF code"
                        class="green"
                    >
                    <br>
                </center>
            </form>

        </div>
    </fieldset>
</div>