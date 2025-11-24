<?php

/**
 * Отображает активность
 * @version 0.1.3
 */ ?>

<?php
// Подключаем функции для работы с активностью
require_once 'funct_debug_active.php';

// Получаем события локальной активности
$localOnlyRules = [
	[
		'sender' => 'Rx1',
		'action_start' => 'The squelch is OPEN',
		'action_end' => 'The squelch is CLOSED',
		'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*(The squelch is (OPEN|CLOSED))/',
		'is_single' => false
	],
	[
		'sender' => 'MultiTx',
		'action_start' => 'Turning transmitter ON',
		'action_end' => 'Turning transmitter OFF',
		'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*(Turning the transmitter (ON|OFF))/',
		'is_single' => false
	]
];

$localActivityEvents = debug_getLocalActivity(5, $localOnlyRules);

?>
<div id="LocalActivity">
	<div class="larger" style="vertical-align: bottom; font-weight:bold;text-align:left;margin-top:-12px; cursor: pointer;" title="Двойной клик для отладки">
		<?php echo getTranslation($lang, 'Local Activity'); ?>
	</div>

	<table style="word-wrap: break-word; white-space:normal;">
		<tbody>
			<tr>
				<th width="150px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Time'); ?><span><b><?php echo getTranslation($lang, 'Time'); ?></b></span></a></th>
				<th width="100px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Source'); ?><span><b><?php echo getTranslation($lang, 'Source'); ?></b></span></a></th>
				<th width="100px"><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Mode'); ?><span><b><?php echo getTranslation($lang, 'Mode'); ?></b></span></a></th>
				<th><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Target'); ?><span><b><?php echo getTranslation($lang, 'Target of transmission'); ?></b></span></a></th>
				<th><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Action'); ?><span><b><?php echo getTranslation($lang, 'Action'); ?></b></span></a></th>
				<th><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Callsign'); ?><span><b><?php echo getTranslation($lang, 'Callsign'); ?></b></span></a></th>
				<th><a class="tooltip" href="#"><?php echo getTranslation($lang, 'Duration'); ?><span><b><?php echo getTranslation($lang, 'Duration'); ?></b></span></a></th>
			</tr>
			<?php
			if (!empty($localActivityEvents)) {
				foreach ($localActivityEvents as $index => $event) {
					// Определяем источник сигнала
					$signalSource = "RX";
					if (strpos($event['sender'] ?? '', 'MultiTx') !== false) {
						$signalSource = "TX";
					}

					// Определяем цель передачи
					$target = $event['ID'] ?? '';
					if (empty($target)) {
						if (isset($SessionInfo['active_module']) && $SessionInfo['active_module'] !== "") {
							$target = $SessionInfo['active_module'];
						} elseif (isset($SessionInfo['active_logic'])) {
							$target = $SessionInfo['active_logic'] . ' : ' . (isset($tgselect) ? $tgselect : '');
						} else {
							$target = 'Local';
						}
					}

					// Определяем режим
					$mode = $event['DS'] ?? '';
					if (empty($mode)) {
						$mode = 'Local';
					}

					// Извлекаем Action - берем часть после последнего двоеточия из _debug_raw_line
					$action = '';
					$rawLine = $event['_debug_raw_line'] ?? '';
					if (!empty($rawLine)) {
						$lastColonPos = strrpos($rawLine, ':');
						if ($lastColonPos !== false) {
							$action = trim(substr($rawLine, $lastColonPos + 1));
							// Укорачиваем слишком длинные действия
							if (strlen($action) > 50) {
								$action = substr($action, 0, 47) . '...';
							}
						}
					}

					// Преобразуем время в формат DD.MM.YY HH:MM:SS
					$timestamp = $event['timestamp']['date']['iso'] ?? '';
					if (!empty($timestamp)) {
						try {
							$date = DateTime::createFromFormat('d M Y H:i:s.v', $timestamp);
							if ($date !== false) {
								// ВАЖНО: Преобразуем DateTime в строку
								$timestamp = $date->format('d.m.y H:i:s');
							}
						} catch (Exception $e) {
							// Оставляем оригинальный формат при ошибке
						}
					}

					// ОТЛАДОЧНАЯ ИНФОРМАЦИЯ ДЛЯ КАЖДОЙ СТРОКИ
					$debugInfo = '';
					if (isset($event['_debug_raw_line'])) {
						$debugInfo = ' title="' . htmlspecialchars($event['_debug_raw_line']) . '"';
					}

					echo '<tr' . $debugInfo . '>';
					echo '<td>' . $timestamp . '</td>';
					echo '<td>' . ($event['sender'] ?? '') . '</td>';
					echo '<td>' . $signalSource . '</td>';
					echo '<td>' . $target . '</td>';
					echo '<td>' . htmlspecialchars((string)$action) . '</td>';
					echo '<td>' . $mode . '</td>';
					echo '<td>' . ($event['payload'] ?? '') . '</td>'; // В Duration оставляем длительность
					echo '</tr>';
				}
			} else {
				// Если нет событий, показываем пустую строку
				echo '<tr><td colspan="7" style="text-align: center;">' . getTranslation($lang, 'No activity') . '</td></tr>';
			}
			?>
		</tbody>
	</table>


	<br>
</div>