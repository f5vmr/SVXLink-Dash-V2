<?php
/**
 * Функции отладки
 * @file funct_debug_active.php
 * @version 0.1.1
 * Длительность не показана
 * Незаконченные события отображаются неверно
 */
function debug_filterLogLines($logLines, $limit = null, $filters = null, $include = true)
{
	$result = [
		'result' => 'not processed',
		'events' => [],
	];

	if (empty($logLines)) {
		$result['result'] = 'empty_log_lines';
		return $result;
	}

	$pendingEvents = [];
	$pendingMonitors = [];

	foreach ($logLines as $line) {
		// Блок 1: Turning the transmitter ON/OFF
		if (strpos($line, 'Turning the transmitter ON') !== false) {
			$parsed = debug_parseLogLine($line);
			$pendingEvents['transmitter'] = [
				'type' => 'transmitter',
				'start_line' => $line,
				'start_time' => $parsed,
				'sender' => $parsed['sender'],
				'payload' => 'Turning the transmitter',
				'start_action' => 'ON'
			];

			// Добавляем событие "in progress"
			$event_data = [
				'start_time' => $parsed,
				'end_time' => '',
				'sender' => $parsed['sender'],
				'payload' => 'Turning the transmitter',
				'action' => 'ON', // Используем оригинальное действие для фильтрации
				'display_action' => 'in progress', // Отдельное поле для отображения
				'tg_info' => []
			];

			if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
				$result['events'][] = $event_data;
			}
		} elseif (strpos($line, 'Turning the transmitter OFF') !== false) {
			$parsed = debug_parseLogLine($line);

			if (isset($pendingEvents['transmitter'])) {
				$event = $pendingEvents['transmitter'];

				// Удаляем "in progress" и добавляем "start->stop"
				debug_removeSingleEvent($result['events'], $event['start_time'], 'ON');

				$event_data = [
					'start_time' => $event['start_time'],
					'end_time' => $parsed,
					'sender' => $event['sender'],
					'payload' => $event['payload'],
					'action' => 'ON->OFF',
					'display_action' => 'ON->OFF',
					'tg_info' => []
				];

				if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
					$result['events'][] = $event_data;
				}
				unset($pendingEvents['transmitter']);
			}
			// Одиночные OFF события не добавляем
		}

		// Блок 2: The squelch is OPEN/CLOSED
		if (strpos($line, 'The squelch is OPEN') !== false) {
			$parsed = debug_parseLogLine($line);
			$pendingEvents['squelch'] = [
				'type' => 'squelch',
				'start_line' => $line,
				'start_time' => $parsed,
				'sender' => $parsed['sender'],
				'payload' => 'The squelch is',
				'start_action' => 'OPEN'
			];

			// Добавляем событие "in progress"
			$event_data = [
				'start_time' => $parsed,
				'end_time' => '',
				'sender' => $parsed['sender'],
				'payload' => 'The squelch is',
				'action' => 'OPEN', // Используем оригинальное действие для фильтрации
				'display_action' => 'in progress', // Отдельное поле для отображения
				'tg_info' => []
			];

			if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
				$result['events'][] = $event_data;
			}
		} elseif (strpos($line, 'The squelch is CLOSED') !== false) {
			$parsed = debug_parseLogLine($line);

			if (isset($pendingEvents['squelch'])) {
				$event = $pendingEvents['squelch'];

				// Удаляем "in progress" и добавляем "start->stop"
				debug_removeSingleEvent($result['events'], $event['start_time'], 'OPEN');

				$event_data = [
					'start_time' => $event['start_time'],
					'end_time' => $parsed,
					'sender' => $event['sender'],
					'payload' => $event['payload'],
					'action' => 'OPEN->CLOSED',
					'display_action' => 'OPEN->CLOSED',
					'tg_info' => []
				];

				if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
					$result['events'][] = $event_data;
				}
				unset($pendingEvents['squelch']);
			}
			// Одиночные CLOSED события не добавляем
		}

		// Блок 3: Talker start on TG/Talker stop on TG
		if (strpos($line, 'Talker start on TG') !== false) {
			$parsed = debug_parseLogLine($line);
			$pendingEvents['talker'] = [
				'type' => 'talker',
				'start_line' => $line,
				'start_time' => $parsed,
				'sender' => $parsed['sender'],
				'payload' => 'Talker on TG',
				'start_action' => 'start',
				'tg_info' => $parsed['tg_info']
			];

			// Добавляем событие "in progress"
			$event_data = [
				'start_time' => $parsed,
				'end_time' => '',
				'sender' => $parsed['sender'],
				'payload' => 'Talker on TG',
				'action' => 'start', // Используем оригинальное действие для фильтрации
				'display_action' => 'in progress', // Отдельное поле для отображения
				'tg_info' => $parsed['tg_info']
			];

			if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
				$result['events'][] = $event_data;
			}
		} elseif (strpos($line, 'Talker stop on TG') !== false) {
			$parsed = debug_parseLogLine($line);

			if (isset($pendingEvents['talker'])) {
				$event = $pendingEvents['talker'];

				// Удаляем "in progress" и добавляем "start->stop"
				debug_removeSingleEvent($result['events'], $event['start_time'], 'start');

				$event_data = [
					'start_time' => $event['start_time'],
					'end_time' => $parsed,
					'sender' => $event['sender'],
					'payload' => $event['payload'],
					'action' => 'start->stop',
					'display_action' => 'start->stop',
					'tg_info' => $event['tg_info']
				];

				if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
					$result['events'][] = $event_data;
				}
				unset($pendingEvents['talker']);
			}
			// Одиночные stop события не добавляем
		}

		// Блок 3.1: Add/timeout/refresh temporary monitor for TG
		if (strpos($line, 'Add temporary monitor for TG') !== false) {
			$parsed = debug_parseLogLine($line);
			$tg_number = $parsed['tg_info']['tg_number'];

			// Добавляем событие "in progress"
			$event_data = [
				'start_time' => $parsed,
				'end_time' => '',
				'sender' => $parsed['sender'],
				'payload' => 'Temporary monitor for TG',
				'action' => 'monitor_add', // Используем оригинальное действие для фильтрации
				'display_action' => 'in progress', // Отдельное поле для отображения
				'tg_info' => $parsed['tg_info']
			];

			if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
				$result['events'][] = $event_data;
			}

			// Сохраняем в pendingMonitors для последующего завершения
			$pendingMonitors[$tg_number] = [
				'type' => 'monitor',
				'start_line' => $line,
				'start_time' => $parsed,
				'sender' => $parsed['sender'],
				'payload' => 'Temporary monitor for TG',
				'start_action' => 'monitor_add',
				'tg_info' => $parsed['tg_info']
			];
		} elseif (strpos($line, 'Refresh temporary monitor for TG') !== false) {
			$parsed = debug_parseLogLine($line);
			$tg_number = $parsed['tg_info']['tg_number'];

			if (isset($pendingMonitors[$tg_number])) {
				// Обновляем время начала на Refresh
				$pendingMonitors[$tg_number]['start_time'] = $parsed;
				$pendingMonitors[$tg_number]['start_line'] = $line;
				$pendingMonitors[$tg_number]['start_action'] = 'monitor_refresh';

				// Обновляем "in progress" событие с новым временем
				debug_removeSingleEvent($result['events'], $pendingMonitors[$tg_number]['start_time'], 'monitor_add');

				$event_data = [
					'start_time' => $parsed,
					'end_time' => '',
					'sender' => $parsed['sender'],
					'payload' => 'Temporary monitor for TG',
					'action' => 'monitor_refresh',
					'display_action' => 'in progress',
					'tg_info' => $parsed['tg_info']
				];

				if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
					$result['events'][] = $event_data;
				}
			} else {
				// Если нет записи о Add, создаем новый "in progress"
				$event_data = [
					'start_time' => $parsed,
					'end_time' => '',
					'sender' => $parsed['sender'],
					'payload' => 'Temporary monitor for TG',
					'action' => 'monitor_refresh',
					'display_action' => 'in progress',
					'tg_info' => $parsed['tg_info']
				];

				if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
					$result['events'][] = $event_data;
				}

				// Создаем pending запись для Refresh
				$pendingMonitors[$tg_number] = [
					'type' => 'monitor',
					'start_line' => $line,
					'start_time' => $parsed,
					'sender' => $parsed['sender'],
					'payload' => 'Temporary monitor for TG',
					'start_action' => 'monitor_refresh',
					'tg_info' => $parsed['tg_info']
				];
			}
		} elseif (strpos($line, 'Temporary monitor timeout for TG') !== false) {
			$parsed = debug_parseLogLine($line);
			$tg_number = $parsed['tg_info']['tg_number'];

			if (isset($pendingMonitors[$tg_number])) {
				$event = $pendingMonitors[$tg_number];

				// Удаляем "in progress" и добавляем завершенное событие
				debug_removeSingleEvent($result['events'], $event['start_time'], $event['start_action']);

				$event_data = [
					'start_time' => $event['start_time'],
					'end_time' => $parsed,
					'sender' => $event['sender'],
					'payload' => $event['payload'],
					'action' => $event['start_action'] . '->timeout',
					'display_action' => $event['start_action'] . '->timeout',
					'tg_info' => $event['tg_info']
				];

				if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
					$result['events'][] = $event_data;
				}
				unset($pendingMonitors[$tg_number]);
			}
			// Одиночные timeout события не добавляем
		}

		// Блок 4: Selecting TG (мгновенное событие)
		if (strpos($line, 'Selecting TG') !== false) {
			$parsed = debug_parseLogLine($line);
			$event_data = [
				'start_time' => $parsed,
				'end_time' => $parsed,
				'sender' => $parsed['sender'],
				'payload' => 'Selecting TG',
				'action' => 'Selecting TG',
				'display_action' => 'Selecting TG',
				'tg_info' => $parsed['tg_info']
			];

			if (debug_shouldIncludeEvent($event_data, $filters, $include)) {
				$result['events'][] = $event_data;
			}
		}
	}

	// Незавершенные события остаются как "in progress" (они уже добавлены)

	// Сортируем события по времени начала (самые свежие сначала)
	usort($result['events'], function ($a, $b) {
		$timeA = $a['start_time']['date']['unixtime'] ?? 0;
		$timeB = $b['start_time']['date']['unixtime'] ?? 0;

		// Сначала сравниваем по времени начала
		if ($timeA != $timeB) {
			return $timeB - $timeA; // по убыванию (свежие сначала)
		}

		// Если время начала одинаковое, сравниваем по времени окончания
		$endTimeA = $a['end_time']['date']['unixtime'] ?? 0;
		$endTimeB = $b['end_time']['date']['unixtime'] ?? 0;

		return $endTimeB - $endTimeA;
	});

	// Применяем limit если указан (берем первые $limit элементов)
	if ($limit !== null && $limit > 0) {
		$result['events'] = array_slice($result['events'], 0, $limit);
	}

	$result['result'] = 'success';
	return $result;
}

// Вспомогательная функция для удаления одиночного события
function debug_removeSingleEvent(&$events, $start_time, $action)
{
	foreach ($events as $key => $event) {
		$eventStartTime = $event['start_time']['date']['unixtime'] ?? 0;
		$searchStartTime = $start_time['date']['unixtime'] ?? 0;

		if (
			$eventStartTime === $searchStartTime &&
			$event['action'] === $action &&
			empty($event['end_time'])
		) {
			unset($events[$key]);
			// Прерываем после первого найденного совпадения
			break;
		}
	}
	// Переиндексируем массив
	$events = array_values($events);
}

function debug_parseLogLine($log_line)
{
	$result = [
		'timestamp' => '',
		'sender' => '',
		'payload' => '',
		'action' => '',
		'tg_info' => [],
		'date' => [
			'raw' => '',        // "24 Nov 2025 10:00:08.882"
			'unixtime' => '',   // 1763942408882
			'datestamp' => '',  // "24.11.2025"  
			'timestamp' => ''   // "10:00:08"
		]
	];

	// Извлекаем временную метку
	if (preg_match('/^([^:]+ \d+:\d+:\d+\.\d+): /', $log_line, $matches)) {
		$result['timestamp'] = trim($matches[1]);

		// Парсим временную метку в упрощенный формат
		$result['date'] = debug_parseTimestamp($result['timestamp']);
	}

	// Извлекаем отправителя - после timestamp и до следующего ": "
	$remaining = substr($log_line, strlen($result['timestamp']) + 2); // +2 для ": "
	if (preg_match('/^([^:]+): /', $remaining, $matches)) {
		$result['sender'] = trim($matches[1]);

		// Извлекаем payload - все после sender
		$result['payload'] = trim(substr($remaining, strlen($result['sender']) + 2)); // +2 для ": "
	} else {
		// Если нет sender, payload - все после timestamp
		$result['payload'] = trim($remaining);
	}

	// Анализируем содержимое для определения действия и TG информации
	if (strpos($result['payload'], 'Turning the transmitter ON') !== false) {
		$result['action'] = 'ON';
	} elseif (strpos($result['payload'], 'Turning the transmitter OFF') !== false) {
		$result['action'] = 'OFF';
	} elseif (strpos($result['payload'], 'The squelch is OPEN') !== false) {
		$result['action'] = 'OPEN';
	} elseif (strpos($result['payload'], 'The squelch is CLOSED') !== false) {
		$result['action'] = 'CLOSED';
	} elseif (strpos($result['payload'], 'Talker start on TG') !== false) {
		$result['action'] = 'start';
		if (preg_match('/TG (#\d+): (.+)$/', $result['payload'], $matches)) {
			$result['tg_info'] = [
				'tg_number' => $matches[1],
				'talker' => $matches[2]
			];
		}
	} elseif (strpos($result['payload'], 'Talker stop on TG') !== false) {
		$result['action'] = 'stop';
		if (preg_match('/TG (#\d+): (.+)$/', $result['payload'], $matches)) {
			$result['tg_info'] = [
				'tg_number' => $matches[1],
				'talker' => $matches[2]
			];
		}
	} elseif (strpos($result['payload'], 'Selecting TG') !== false) {
		$result['action'] = 'instant';
		if (preg_match('/Selecting TG (#\d+)/', $result['payload'], $matches)) {
			$result['tg_info'] = [
				'tg_number' => $matches[1]
			];
		}
	} elseif (strpos($result['payload'], 'Add temporary monitor for TG') !== false) {
		$result['action'] = 'monitor_add';
		if (preg_match('/TG (#\d+)/', $result['payload'], $matches)) {
			$result['tg_info'] = [
				'tg_number' => $matches[1]
			];
		}
	} elseif (strpos($result['payload'], 'Refresh temporary monitor for TG') !== false) {
		$result['action'] = 'monitor_refresh';
		if (preg_match('/TG (#\d+)/', $result['payload'], $matches)) {
			$result['tg_info'] = [
				'tg_number' => $matches[1]
			];
		}
	} elseif (strpos($result['payload'], 'Temporary monitor timeout for TG') !== false) {
		$result['action'] = 'monitor_timeout';
		if (preg_match('/TG (#\d+)/', $result['payload'], $matches)) {
			$result['tg_info'] = [
				'tg_number' => $matches[1]
			];
		}
	}

	return $result;
}

function debug_parseTimestamp($timestamp)
{
	$result = [
		'raw' => $timestamp,
		'unixtime' => '',
		'datestamp' => '',
		'timestamp' => ''
	];

	// Парсим формат с помощью date_parse
	$parsed = date_parse($timestamp);

	if ($parsed && $parsed['error_count'] === 0) {
		$year = $parsed['year'];
		$month = str_pad($parsed['month'], 2, '0', STR_PAD_LEFT);
		$day = str_pad($parsed['day'], 2, '0', STR_PAD_LEFT);
		$hour = str_pad($parsed['hour'], 2, '0', STR_PAD_LEFT);
		$minute = str_pad($parsed['minute'], 2, '0', STR_PAD_LEFT);
		$second = str_pad($parsed['second'], 2, '0', STR_PAD_LEFT);
		$millisecond = str_pad($parsed['fraction'] * 1000, 3, '0', STR_PAD_LEFT);

		// Создаем Unix timestamp в миллисекундах
		$unixtime_seconds = mktime($hour, $minute, $second, $month, $day, $year);
		$unixtime_ms = $unixtime_seconds * 1000 + intval($millisecond);

		$result = [
			'raw' => $timestamp,
			'unixtime' => $unixtime_ms,
			'datestamp' => $day . '.' . $month . '.' . $year, // "24.11.2025"
			'timestamp' => $hour . ':' . $minute . ':' . $second // "10:00:08"
		];
	}

	return $result;
}

function debug_shouldIncludeEvent($event, $filters, $include)
{
	// Если фильтры не заданы - включаем все события
	if ($filters === null || empty($filters)) {
		return true;
	}

	// Проверяем каждое правило фильтра
	foreach ($filters as $filter) {
		$sender_match = !isset($filter['sender']) || $filter['sender'] === $event['sender'];
		$action_match = !isset($filter['action']) || $filter['action'] === $event['action'];

		if ($sender_match && $action_match) {
			// Событие соответствует фильтру
			return $include; // true - включать, false - исключать
		}
	}

	// Событие не соответствует ни одному фильтру
	return !$include; // true - включать, false - исключать
}

function debug_formatDuration($milliseconds)
{
	$seconds = floor($milliseconds / 1000);
	$ms = $milliseconds % 1000;

	if ($seconds < 60) {
		return $seconds . '.' . str_pad($ms, 3, '0', STR_PAD_LEFT) . 's';
	} elseif ($seconds < 3600) {
		$minutes = floor($seconds / 60);
		$seconds = $seconds % 60;
		return $minutes . 'm ' . $seconds . '.' . str_pad($ms, 3, '0', STR_PAD_LEFT) . 's';
	} else {
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds % 3600) / 60);
		$seconds = $seconds % 60;
		return $hours . 'h ' . $minutes . 'm ' . $seconds . 's';
	}
}

function debug_formatStartTime($date)
{
	if (isset($date['day']) && isset($date['month']) && isset($date['hour']) && isset($date['minute']) && isset($date['second'])) {
		return $date['day'] . ' ' . $date['month'] . ' ' . $date['hour'] . ':' . $date['minute'] . ':' . $date['second'];
	}
	return '';
}
