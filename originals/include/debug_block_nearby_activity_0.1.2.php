<?php

/** Рабочая версия выводит только последние qty_result
 * @file debug_block_nearby_activity.php
 * @brief блок Nearby Activity
 * @details Используются файлы с префиксом debug_
 * @author vladimir@tsurkanenko.ru
 * @version 0.1.2
 * @date 2021-11-23
 * 
 */

include_once dirname(__DIR__) . "/include/config.php";
include_once dirname(__DIR__) . "/include/tools.php";
include_once dirname(__DIR__) . "/include/functions.php";
include_once dirname(__DIR__) . "/include/funct_debug.php";
include_once dirname(__DIR__) . "/include/funct_debug_active.php";
include_once dirname(__DIR__) . "/include/funct_debug_modules_status.php";
include_once dirname(__DIR__) . "/include/funct_release.php";

$nearby_log = array_reverse(getLog("", 18, false));
$nearby_actions = debug_filterLogLines($nearby_log);
?>
<div id="NearbyActivity">
	<div class="larger" style="vertical-align: bottom; font-weight:bold;text-align:left;margin-top:-12px;"><?php echo getTranslation($lang, 'Nearby Activity'); ?></div>
	<table style="word-wrap: break-word; white-space:normal;">
		<tbody>
			<tr>
				<th width="100px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Time'); ?><span><b><?php echo getTranslation($lang, 'Time'); ?></b></span></a></th>
				<th width="200px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Signal Source'); ?><span><b><?php echo getTranslation($lang, 'Mode'); ?></b></span></a></th>
				<th width="200px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Target'); ?><span><b><?php echo getTranslation($lang, 'Target of transmission'); ?></b></span></a></th>
				<th width="200px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Mode'); ?><span><b><?php echo getTranslation($lang, 'Transmitted Mode'); ?></b></span></a></th>
				<th width="300px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Frequency'); ?><span><b><?php echo getTranslation($lang, 'Frequency'); ?></b></span></a></th>
				<th><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Action'); ?><span><b><?php echo getTranslation($lang, 'Action'); ?></b></span></a></th>
			</tr>
			<?php

			foreach ($nearby_log as $key => $value) {
				$parsedLine = parseLogLine($value);
				// Обрезаем строку до 
				$variable = isset($parsedLine['payload']) ? $parsedLine['payload'] : '';
				$payload = $variable ? (strlen($variable) > 150 ? substr($variable, 0, 150) . '...' : $variable) : '';

				if (isset($parsedLine['error'])) {
					if ($parsedLine['error'] !== '') {
						continue;
					};
				};

				if (isset($parsedLine['sender'])) {
					if (isset($parsedLine['ON'])) {
						$parsedLine['sender'] = $parsedLine['ON'];
					};
				};
				if (isset($parsedLine['ON'])) {
					$parsedLine['sender'] = $parsedLine['ON'];
				}


				echo '<tr><td>' .
					(isset($parsedLine['timestamp']['date']['iso']) ? $parsedLine['timestamp']['date']['iso'] : '') . '</td><td>' .

					(isset($parsedLine['sender']) ? $parsedLine['sender'] : '') . '</td><td>' .

					(isset($parsedLine['BC']) ? $parsedLine['BC'] : '') . '</td><td>' .
					(isset($parsedLine['ID']) ? $parsedLine['ID'] : '') . '</td><td>' .
					(isset($parsedLine['DS']) ? $parsedLine['DS'] : '') . '</td><td align="left">' .
					(isset($parsedLine['payload']) ? $payload : '') . '</td></tr>';
			}; ?>
		</tbody>
	</table>
	<br>
	<script></script>
</div>