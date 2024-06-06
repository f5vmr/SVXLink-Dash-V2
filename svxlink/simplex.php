<?php

	$inSimplexDefaultLang = $svxconfig['SimplexLogic']['DEFAULT_LANG'];
        $inSimplexCallsign = $svxconfig['SimplexLogic']['CALLSIGN'];
	$inSimplexModules = $svxconfig['SimplexLogic']['MODULES'];
        $inSimShortInterval = $svxconfig['SimplexLogic']['SHORT_IDENT_INTERVAL'];
        $inSimLongInterval = $svxconfig['SimplexLogic']['LONG_IDENT_INTERVAL'];
        $inRgrDelay = $svxconfig['SimplexLogic']['RGR_SOUND_DELAY'];
        $inRgr = $svxconfig['SimplexLogic']['RGR_SOUND_ALWAYS'];
        $inFxGainNormal = $svxconfig['SimplexLogic']['FX_GAIN_NORMAL'];
        $inFxGainLow = $svxconfig['SimplexLogic']['FX_GAIN_LOW'];
        $inOnLineCmd = $svxconfig['SimplexLogic']['ONLINE_CMD'];
        $inOnLine = $svxconfig['SimplexLogic']['ONLINE'];
        
        ?>
<table>
        <tr>
        <th width = "380px">Simplex Logic</th>
        <th width = "100px">Action</th>
        </tr>
<tr>
<td>
        <table style="border-collapse: collapse; border: none;">
        <tr style="border: none;">
                <th width = "30%"></th>
                <th width = "70%"></th>
        </tr>
        <tr style="border: none;"> 
        <td style="border: none;">Default Language</td>
        <td style="border: none;"><input type="text" name="inSimplexDefaultLang" style="width:98%" value="<?php echo $inSimplexDefaultLang;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Callsign</td>
        <td style="border: none;"><input type="text" name="inSimplexCallsign" style="width:98%" value="<?php echo $inSimplexCallsign;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Modules</td>
        <td style="border: none;"><input type="text" name="inSimplexModules" style="width:98%" value="<?php echo $inSimplexModules;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Short Ident Interval </td>
        <td style="border: none;"><input type="text" name="inSimShortInterval" style="width:98%" value="<?php echo $inSimShortInterval;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Long Ident Interval</td>
        <td style="border: none;"><input type="text" name="inSimLongInterval" style="width:98%" value="<?php echo $inSimLongInterval;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Report CTCSS</td>
        <td style="border: none;"><input type="text" name="inReportCTCSS" style="width:98%" value="<?php echo $CTCSS;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Roger Sound Delay</td>
        <td style="border: none;"><input type="text" name="inRgrDelay" style="width:98%" value="<?php echo $inRgrDelay;?>">
        </td></tr>
        <tr style="border: none;"> 
        <td style="border: none;">Roger Sound</td>
        <td style="border: none;"><input type="text" name="inRgr" style="width:98%" value="<?php echo $inRgr;?>">
        </td></tr>
        </table>
</td>
<td>
        <button name="btnSave" type="submit" class="red" style="height:100px; width:105px; font-size:12px;">Save <br><br> & <br><br> ReLoad</button>
</td>
</tr>
</table>
