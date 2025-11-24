<?php

/**
 * @date 2021-11-23
 * @version 0.1.3
 * @note Мониторится может несколько групп
 * @description Отображение блока разговорных групп рефлектора
 * @author vladimir@tsurkanenko.ru
 * 
 */
?>
<div class="mode_flex">
    <div class="mode_flex row">
        <div class="mode_flex column">
            <div class="divTableHead"><?php echo getTranslation($lang, 'Monitored Talkgroups'); ?></div>
        </div>
        <div class="mode_flex row">
            <div class="mode_flex row">
                <?php

                // TODO переписать логику, цветовое выделение.
                // Мониторящиеся группы
                if (isset($SessionInfo['logic']['ReflectorLogic']['talkgroups']['monitoring'])) {
                    foreach ($SessionInfo['logic']['ReflectorLogic']['talkgroups']['monitoring'] as $_tg) {
                        $_format_cell = $_tg == $SessionInfo['logic']['ReflectorLogic']['talkgroups']['default'] ? "active-mode-cell" : "disabled-mode-cell";
                        echo '<div class="mode_flex column ' . $_format_cell . ' " title="' . $_tg . ' " style="border: .5px solid #3c3f47;">' . $_tg . '</div>';
                    };
                };

                // активная группа
                if ($SessionInfo['logic']['ReflectorLogic']['talkgroups']['selected'] != '0') {
                    if ($SessionInfo['logic']['ReflectorLogic']['talkgroups']['selected'] != $SessionInfo['logic']['ReflectorLogic']['talkgroups']['default']) {
                        echo '<div class="mode_flex column active-mode-cell" title="" style="border: .5px solid #3c3f47;">';
                        echo $SessionInfo['logic']['ReflectorLogic']['talkgroups']['selected'];
                        echo '</div>';
                    }
                };

                // Временно мониторящиеся группа
                if (isset($SessionInfo['logic']['ReflectorLogic']['talkgroups']['temp_monitoring'])) {
					foreach ($SessionInfo['logic']['ReflectorLogic']['talkgroups']['temp_monitoring'] as $_mtg) {
						$_format_cell = $_mtg == $SessionInfo['logic']['ReflectorLogic']['talkgroups']['selected'] ? "active-mode-cell" : "paused-mode-cell";
						echo '<div class="mode_flex column ' . $_format_cell . ' " title="' . $_mtg . ' " style="border: .5px solid #3c3f47;">' . $_mtg . '</div>';
					};
                    // echo '<div class="mode_flex column paused-mode-cell" title="" style="border: .5px solid #3c3f47;">';
                    // echo $SessionInfo['logic']['ReflectorLogic']['talkgroups']['temp_monitoring'];
                    // echo '</div>';
                };

                ?>
            </div>
        </div>
    </div>
</div>