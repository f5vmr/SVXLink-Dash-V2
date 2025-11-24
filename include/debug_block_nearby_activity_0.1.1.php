<?php

/** Рабочая версия выводит только последние qty_result
 * @file debug_block_nearby_activity.php
 * @brief блок Nearby Activity
 * @details Используются файлы с префиксом debug_
 * @author vladimir@tsurkanenko.ru
 * @version 0.1.1
 * @date 2021-11-23
 */

include_once dirname(__DIR__) . "/include/config.php";
include_once dirname(__DIR__) . "/include/tools.php";
include_once dirname(__DIR__) . "/include/functions.php";
include_once dirname(__DIR__) . "/include/funct_debug.php";
include_once dirname(__DIR__) . "/include/funct_debug_active.php";
include_once dirname(__DIR__) . "/include/funct_debug_modules_status.php";
include_once dirname(__DIR__) . "/include/funct_release.php";

$_ord_log = array_reverse(getLog("", 550, false));
$filters = [['sender' => 'ReflectorLogic']];
$nearby_actions = debug_filterLogLines($_ord_log, 30, $filters, true);
?>
<div id="NearbyActivity">
	<div class="larger" style="vertical-align: bottom; font-weight:bold;text-align:left;margin-top:-12px;"><?php echo getTranslation($lang, 'Nearby Activity'); ?></div>
	<table style="word-wrap: break-word; white-space:normal;">
		<tbody>
			<tr>
				<th width="100px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Date'); ?><span><b><?php echo getTranslation($lang, 'Date'); ?></b></span></a></th>
				<th width="80px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Time'); ?><span><b><?php echo getTranslation($lang, 'Time'); ?></b></span></a></th>
				<th width="100px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Duration'); ?><span><b><?php echo getTranslation($lang, 'Duration'); ?></b></span></a></th>
				<th width="150px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Sender'); ?><span><b><?php echo getTranslation($lang, 'Sender'); ?></b></span></a></th>
				<th width="200px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Payload'); ?><span><b><?php echo getTranslation($lang, 'Event Type'); ?></b></span></a></th>
				<th width="150px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Action'); ?><span><b><?php echo getTranslation($lang, 'Action'); ?></b></span></a></th>
				<th width="200px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'TG Info'); ?><span><b><?php echo getTranslation($lang, 'Talk Group Information'); ?></b></span></a></th>
			</tr>
			<?php
			foreach ($nearby_actions['events'] as $event) {
				// Форматируем TG информацию для отображения
				$tg_display = '';
				if (!empty($event['tg_info'])) {
					if (isset($event['tg_info']['tg_number']) && isset($event['tg_info']['talker'])) {
						$tg_display = $event['tg_info']['tg_number'] . ' - ' . $event['tg_info']['talker'];
					} elseif (isset($event['tg_info']['tg_number'])) {
						$tg_display = $event['tg_info']['tg_number'];
					}
				}

				// Безопасное получение даты и времени
				$datestamp = '';
				$timestamp = '';
				$start_unixtime = 0;

				if (
					isset($event['start_time']) && !empty($event['start_time']) &&
					is_array($event['start_time']) && isset($event['start_time']['date'])
				) {
					$start_date = $event['start_time']['date'];
					$datestamp = $start_date['datestamp'] ?? '';
					$timestamp = $start_date['timestamp'] ?? '';
					$start_unixtime = $start_date['unixtime'] ?? 0;
				}

				// Вычисляем продолжительность
				$duration = '';
				if (
					isset($event['end_time']) && !empty($event['end_time']) &&
					is_array($event['end_time']) && isset($event['end_time']['date'])
				) {
					$end_unixtime = $event['end_time']['date']['unixtime'] ?? 0;

					if ($start_unixtime > 0 && $end_unixtime > 0 && $end_unixtime > $start_unixtime) {
						$duration_ms = $end_unixtime - $start_unixtime;
						$duration = debug_formatDuration($duration_ms);
					}
				}

				echo '<tr>'
					. '<td>' . $datestamp . '</td>'
					. '<td>' . $timestamp . '</td>'
					. '<td>' . $duration . '</td>'
					. '<td>' . (isset($event['sender']) ? $event['sender'] : '') . '</td>'
					. '<td>' . (isset($event['payload']) ? $event['payload'] : '') . '</td>'
					. '<td>' . (isset($event['action']) ? $event['action'] : '') . '</td>'
					. '<td>' . $tg_display . '</td>'
					. '</tr>';
			}

			// Если событий нет, показываем сообщение
			if (empty($nearby_actions['events'])) {
				echo '<tr><td colspan="7" style="text-align: center;">'
					. getTranslation($lang, 'No events found')
					. ' (' . $nearby_actions['result'] . ')'
					. '</td></tr>';
			}
			?>
		</tbody>
	</table>
	<br>
	<script></script>
</div>