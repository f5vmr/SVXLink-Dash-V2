<?php

/**
 * Отображает активность
 * @version 0.1.3
 */ ?>

<?php
// Подключаем функции для работы с активностью
require_once 'funct_debug_active.php';

// Получаем события локальной активности

$myRules = [
	[
		'sender' => 'ReflectorLogic',
		'action_start' => 'Talker start on TG #',
		'action_end' => 'Talker stop on TG #',
		'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*Talker (start|stop) on TG #(\d+):\s*([^\s]+)/',
		'is_single' => false
	]
];

$squelchOnlyRules = [
	[
		'sender' => 'Rx1',
		'action_start' => 'The squelch is OPEN',
		'action_end' => 'The squelch is CLOSED',
		'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*(The squelch is (OPEN|CLOSED))/',
		'is_single' => false
	]
];

$localActivityEvents = debug_getLocalActivity(10, $squelchOnlyRules);

// $localActivityEvents = debug_getLocalActivity(15, $myRules);



//$localActivityEvents = debug_getLocalActivity(15);

?>
<div id="LocalActivity">
	<div class="larger" style="vertical-align: bottom; font-weight:bold;text-align:left;margin-top:-12px; cursor: pointer;" title="Двойной клик для отладки">
		<?php echo getTranslation($lang, 'Local Activity'); ?>
	</div>

	<!-- ОТЛАДОЧНАЯ ИНФОРМАЦИЯ -->
	<div style="background: #f0f0f0; padding: 10px; margin-bottom: 10px; border-radius: 5px; font-size: 12px;">
		<strong>Отладка:</strong> Найдено <?php echo count($localActivityEvents); ?> событий
		<?php if (!empty($localActivityEvents)): ?>
			<br>Типы событий:
			<?php
			$types = [];
			foreach ($localActivityEvents as $event) {
				$types[] = $event['_debug_event_type'] ?? 'unknown';
			}
			echo implode(', ', array_unique($types));
			?>
		<?php endif; ?>
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
					$signalSource = "RF";
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
					echo '<td>' . htmlspecialchars($action) . '</td>';
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

	<!-- ДОПОЛНИТЕЛЬНАЯ ОТЛАДКА - ВИДИМАЯ КНОПКА -->
	<?php if (!empty($localActivityEvents)): ?>
		<div style="text-align: center; margin: 10px 0;">
			<button onclick="toggleDebug()" style="padding: 5px 10px; font-size: 12px; cursor: pointer;">
				📋 Показать отладочную информацию
			</button>
		</div>

		<div style="padding: 10px; margin-top: 10px; border-radius: 5px; display: none;" id="debugDetails">
			<strong>Подробная отладка:</strong>
			<button onclick="toggleDebug()" style="float: right; padding: 2px 5px; font-size: 10px;">Скрыть</button>
			<div style="clear: both;"></div>
			<?php foreach ($localActivityEvents as $index => $event): ?>
				<div style="margin: 5px 0; padding: 5px; border-bottom: 1px solid #ddd;">
					<strong>Событие <?php echo $index + 1; ?> (<?php echo $event['_debug_event_type'] ?? 'unknown'; ?>):</strong><br>
					<strong>Строка лога:</strong> <code style="padding: 2px 4px;"><?php echo htmlspecialchars($event['_debug_raw_line'] ?? ''); ?></code><br>
					<strong>Action:</strong> <?php echo htmlspecialchars($action); ?><br>
					<strong>Отправитель:</strong> <?php echo $event['sender'] ?? ''; ?> |
					<strong>TG:</strong> <?php echo $event['ID'] ?? ''; ?> |
					<strong>Callsign:</strong> <?php echo $event['DS'] ?? ''; ?> |
					<strong>Длительность:</strong> <?php echo $event['payload'] ?? ''; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<script>
			function toggleDebug() {
				var debugDiv = document.getElementById('debugDetails');
				var button = document.querySelector('button[onclick="toggleDebug()"]');
				if (debugDiv.style.display === 'none') {
					debugDiv.style.display = 'block';
					button.textContent = '📋 Скрыть отладочную информацию';
				} else {
					debugDiv.style.display = 'none';
					button.textContent = '📋 Показать отладочную информацию';
				}
			}

			// Двойной клик на заголовке тоже показывает/скрывает отладку
			document.querySelector('.larger').addEventListener('dblclick', toggleDebug);
		</script>
	<?php endif; ?>

	<br>
</div>