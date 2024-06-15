<?php

        
        $inRepeaterDefaultLang = $svxconfig['RepeaterLogic']['DEFAULT_LANG'];
        $inRepeaterCallsign = $svxconfig['RepeaterLogic']['CALLSIGN'];
        $inRepeaterModules = $svxconfig['RepeaterLogic']['MODULES'];
        $inRepShortInterval = $svxconfig['RepeaterLogic']['SHORT_IDENT_INTERVAL'];
        $inRepLongInterval = $svxconfig['RepeaterLogic']['LONG_IDENT_INTERVAL'];
        $inIdleTime = $svxconfig['RepeaterLogic']['IDLE_TIMEOUT'];
        $in1750 = $svxconfig['RepeaterLogic']['OPEN_ON_1750'];
        $inCTCSS = $svxconfig['RepeaterLogic']['OPEN_ON_CTCSS'];
        $inSqlFlank = $svxconfig['RepeaterLogic']['OPEN_SQL_FLANK'];
        $inIdleIntervalTime = $svxconfig['RepeaterLogic']['IDLE_SOUND_INTERVAL'];
        $inOnLineCommand = $svxconfig['RepeaterLogic']['ONLINE_CMD'];
        $inOnLine = $svxconfig['RepeaterLogic']['ONLINE'];
        $inFxGainNormal = $svxconfig['RepeaterLogic']['FX_GAIN_NORMAL'];
        $inFxGainLow = $svxconfig['RepeaterLogic']['FX_GAIN_LOW'];
        
        
        ?>
<table>
        <tr>
        <th width = "380px">Repeater Logic</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        <table style = "border-collapse: collapse; border: none;">
        <tr style = "border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        
        <tr style = "border: none;"> 
        <td style = "border: none;">Default Language</td>
        <td style = "border: none;"><input type="text" name="inRepeaterDefaultLang" style = "width:98%" value="<?php echo $inRepeaterDefaultLang;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Callsign</td>
        <td style = "border: none;"><input type="text" name="inRepeaterCallsign" style = "width:98%" value="<?php echo $inRepeaterCallsign;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Modules</td>
        <td style = "border: none;"><input type="text" name="inRepeaterModules" style = "width:98%" value="<?php echo $inRepeaterModules;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Short Ident Interval</td>
        <td style = "border: none;"><input type="text" name="inRepShortInterval" style = "width:98%" value="<?php echo $inRepShortInterval;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Long Ident Interval</td>
        <td style = "border: none;"><input type="text" name="inRepLongInterval" style = "width:98%" value="<?php echo $inRepLongInterval;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Idle Timeout</td>
        <td style = "border: none;"><input type="text" name="inIdleTime" style = "width:98%" value="<?php echo $inIdleTime;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Open on 1750 Hz</td>
        <td style = "border: none;"><input type="text" name="in1750" style = "width:98%" value="<?php echo $in1750;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Report CTCSS</td>
        <td style = "border: none;"><input type="text" name="inReportCTCSS" style = "width:98%" value="<?php echo $CTCSS;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Open on CTCSS</td>
        <td style = "border: none;"><input type="text" name="inCTCSS" style = "width:98%" value="<?php echo $inCTCSS;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Open Squelch Flank</td>
        <td style = "border: none;"><input type="text" name="inSqlFlank" style = "width:98%" value="<?php echo $inSqlFlank;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">Idle Sound Interval</td>
        <td style = "border: none;"><input type="text" name="inIdleInterval" style = "width:98%" value="<?php echo $inIdleInterval;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">On-Line Command</td>
        <td style = "border: none;"><input type="text" name="inOnLineCommand" style = "width:98%" value="<?php echo $inOnLineCommand;?>">
        </td></tr>
        <tr style = "border: none;"> 
        <td style = "border: none;">On-Line</td>
        <td style = "border: none;"><input type="text" name="inOnline" style = "width:98%" value="<?php echo $inOnline;?>">
        </td></tr>
        </table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style = "height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>
</tr>
</table>
