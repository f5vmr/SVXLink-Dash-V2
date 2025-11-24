<?php

/**
 * Функции отображения активности с указанием длительности
 * @file funct_debug_active.php
 * @date 2025-11-24
 * @version 0.1.3
 */

/**
 * Фильтрует строки журнала по заданным правилам
 */
function debug_filterLogLines($logLines, $rules, $limit = null)
{
	$events = [];
	$pendingStarts = [];

	foreach ($logLines as $line) {
		$matchedEvent = debug_matchLine($line, $rules);

		if (!$matchedEvent) {
			continue;
		}

		// Для одиночных событий создаем событие сразу
		if ($matchedEvent['is_single']) {
			$singleEvent = debug_createSingleEvent($matchedEvent);
			$events[] = $singleEvent;
		} else {
			// Для парных событий
			$key = debug_generateEventKey($matchedEvent);

			if ($matchedEvent['type'] === 'start') {
				$pendingStarts[$key] = $matchedEvent;
			} elseif ($matchedEvent['type'] === 'end') {
				if (isset($pendingStarts[$key])) {
					$startEvent = $pendingStarts[$key];
					$completeEvent = debug_createCompleteEvent($startEvent, $matchedEvent);
					$events[] = $completeEvent;
					unset($pendingStarts[$key]);
				} else {
					$incompleteEvent = debug_createIncompleteEvent($matchedEvent);
					$events[] = $incompleteEvent;
				}
			}
		}

		if ($limit && count($events) >= $limit) {
			break;
		}
	}

	return $events;
}

/**
 * Проверяет соответствие строки журнала правилам
 */
function debug_matchLine($line, $rules)
{
	foreach ($rules as $ruleIndex => $rule) {
		$event = debug_applyRule($line, $rule);
		if ($event) {
			$event['rule_index'] = $ruleIndex;
			return $event;
		}
	}
	return null;
}

/**
 * Применяет одно правило к строке журнала
 */
function debug_applyRule($line, $rule)
{
	// Проверяем базовые фильтры
	if (!debug_checkBasicFilters($line, $rule)) {
		return null;
	}

	// Применяем регулярное выражение
	$matches = [];
	if (isset($rule['rule']) && !empty($rule['rule'])) {
		if (!preg_match($rule['rule'], $line, $matches)) {
			return null;
		}
	} else {
		$matches = debug_parseLineSimple($line);
	}

	// Определяем тип события
	$eventType = debug_determineEventType($line, $rule);
	if (!$eventType) {
		return null;
	}

	// Извлекаем данные
	$extractedData = debug_extractEventData($matches, $rule, $line);

	// Определяем является ли событие одиночным
	$isSingle = isset($rule['is_single']) ? $rule['is_single'] : (isset($rule['action_start']) && isset($rule['action_end']) &&
			$rule['action_start'] === $rule['action_end']);

	return [
		'type' => $eventType,
		'is_single' => $isSingle,
		'timestamp' => [
			'raw' => $extractedData['timestamp'] ?? '',
			'unixtime' => debug_parseTimestamp($extractedData['timestamp'] ?? '')
		],
		'sender' => $extractedData['sender'] ?? '',
		'payload' => $extractedData['payload'] ?? '',
		'talkgroup' => $extractedData['talkgroup'] ?? '',
		'callsign' => $extractedData['callsign'] ?? '',
		'raw_line' => $line,
		'rule_id' => md5(serialize($rule))
	];
}

/**
 * Проверяет базовые фильтры
 */
function debug_checkBasicFilters($line, $rule)
{
	// Проверка sender
	if (isset($rule['sender']) && !empty($rule['sender'])) {
		if (strpos($line, $rule['sender']) === false) {
			return false;
		}
	}

	// Проверяем действия
	$hasStartAction = isset($rule['action_start']) && strpos($line, $rule['action_start']) !== false;
	$hasEndAction = isset($rule['action_end']) && strpos($line, $rule['action_end']) !== false;

	return $hasStartAction || $hasEndAction;
}

/**
 * Простое разбиение строки
 */
function debug_parseLineSimple($line)
{
	$parts = explode(':', $line, 3);

	if (count($parts) < 3) {
		return [];
	}

	return [
		0 => $line,
		1 => trim($parts[0]),
		2 => trim($parts[1]),
		3 => trim($parts[2])
	];
}

/**
 * Определяет тип события
 */
function debug_determineEventType($line, $rule)
{
	// Для одиночных событий всегда возвращаем 'single'
	$isSingle = isset($rule['is_single']) ? $rule['is_single'] : (isset($rule['action_start']) && isset($rule['action_end']) &&
			$rule['action_start'] === $rule['action_end']);

	if ($isSingle) {
		return 'single';
	}

	if (isset($rule['action_start']) && strpos($line, $rule['action_start']) !== false) {
		return 'start';
	}

	if (isset($rule['action_end']) && strpos($line, $rule['action_end']) !== false) {
		return 'end';
	}

	return null;
}

/**
 * Извлекает данные события
 */
function debug_extractEventData($matches, $rule, $line)
{
	$data = [];

	// Для Selecting TG событий
	if (strpos($line, 'Selecting TG #') !== false && count($matches) >= 4) {
		$data['timestamp'] = $matches[1] ?? '';
		$data['sender'] = $matches[2] ?? '';
		$data['payload'] = $matches[0] ?? $line;
		$data['talkgroup'] = $matches[3] ?? '';
		$data['callsign'] = '';
	}
	// Для Talker событий
	elseif (strpos($line, 'Talker') !== false && count($matches) >= 6) {
		$data['timestamp'] = $matches[1] ?? '';
		$data['sender'] = $matches[2] ?? '';
		$data['payload'] = $matches[0] ?? $line;
		$data['talkgroup'] = $matches[4] ?? '';
		$data['callsign'] = $matches[5] ?? '';
	}
	// Для Squelch событий
	elseif (strpos($line, 'The squelch is') !== false && count($matches) >= 4) {
		$data['timestamp'] = $matches[1] ?? '';
		$data['sender'] = $matches[2] ?? '';
		$data['payload'] = $matches[3] ?? $line;
		$data['talkgroup'] = '';
		$data['callsign'] = '';
	}
	// Для Transmitter событий
	elseif (strpos($line, 'Turning the transmitter') !== false && count($matches) >= 4) {
		$data['timestamp'] = $matches[1] ?? '';
		$data['sender'] = $matches[2] ?? '';
		$data['payload'] = $matches[3] ?? $line;
		$data['talkgroup'] = '';
		$data['callsign'] = '';
	}
	// Общий случай
	elseif (count($matches) >= 4) {
		$data['timestamp'] = $matches[1] ?? '';
		$data['sender'] = $matches[2] ?? '';
		$data['payload'] = $matches[3] ?? $line;
	} else {
		// Резервный метод
		$parts = explode(':', $line, 3);
		if (count($parts) >= 3) {
			$data['timestamp'] = trim($parts[0]);
			$data['sender'] = trim($parts[1]);
			$data['payload'] = trim($parts[2]);
		} else {
			$data['timestamp'] = '';
			$data['sender'] = '';
			$data['payload'] = $line;
		}
	}

	return $data;
}

/**
 * Парсит timestamp в unixtime
 */
function debug_parseTimestamp($timestampStr)
{
	if (empty($timestampStr)) {
		return 0;
	}

	try {
		$date = DateTime::createFromFormat('d M Y H:i:s.v', $timestampStr);

		if ($date === false) {
			$date = DateTime::createFromFormat('d M Y H:i:s', $timestampStr);
		}

		if ($date !== false) {
			return (float) $date->format('U.u');
		}

		$timestamp = strtotime($timestampStr);
		if ($timestamp !== false) {
			return (float) $timestamp;
		}
	} catch (Exception $e) {
		// В случае ошибки возвращаем 0
	}

	return 0;
}

/**
 * Генерирует ключ для сопоставления
 */
function debug_generateEventKey($event)
{
	return $event['sender'] . '|' . $event['talkgroup'] . '|' . $event['callsign'];
}

/**
 * Создает одиночное событие
 */
function debug_createSingleEvent($event)
{
	return [
		'start_time' => $event['timestamp'],
		'end_time' => $event['timestamp'],
		'duration' => 0,
		'duration_formatted' => '0.000 сек.',
		'sender' => $event['sender'],
		'payload' => $event['payload'],
		'talkgroup' => $event['talkgroup'],
		'callsign' => $event['callsign'],
		'raw_start_line' => $event['raw_line'],
		'raw_end_line' => $event['raw_line'],
		'status' => 'single'
	];
}

/**
 * Создает неполное событие
 */
function debug_createIncompleteEvent($endEvent)
{
	$currentTime = microtime(true);
	$duration = $currentTime - $endEvent['timestamp']['unixtime'];

	return [
		'start_time' => null,
		'end_time' => $endEvent['timestamp'],
		'duration' => $duration,
		'duration_formatted' => sprintf('%.3f сек. (активно)', $duration),
		'sender' => $endEvent['sender'],
		'payload' => $endEvent['payload'],
		'talkgroup' => $endEvent['talkgroup'],
		'callsign' => $endEvent['callsign'],
		'raw_start_line' => null,
		'raw_end_line' => $endEvent['raw_line'],
		'status' => 'incomplete_end_only'
	];
}

/**
 * Создает полное событие
 */
function debug_createCompleteEvent($startEvent, $endEvent)
{
	$duration = $endEvent['timestamp']['unixtime'] - $startEvent['timestamp']['unixtime'];

	return [
		'start_time' => $startEvent['timestamp'],
		'end_time' => $endEvent['timestamp'],
		'duration' => $duration,
		'duration_formatted' => sprintf('%.3f сек.', $duration),
		'sender' => $startEvent['sender'],
		'payload' => $startEvent['payload'],
		'talkgroup' => $startEvent['talkgroup'],
		'callsign' => $startEvent['callsign'],
		'raw_start_line' => $startEvent['raw_line'],
		'raw_end_line' => $endEvent['raw_line'],
		'status' => 'complete'
	];
}

/**
 * Получает события активности для отображения
 */
function debug_getLocalActivity($limit = 10)
{
	// Правила для фильтрации событий активности
	$activityRules = [
		// Правило 1: Talker события (парные)
		[
			'sender' => 'ReflectorLogic',
			'action_start' => 'Talker start on TG #',
			'action_end' => 'Talker stop on TG #',
			'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*Talker (start|stop) on TG #(\d+):\s*([^\s]+)/',
			'is_single' => false
		],
		// Правило 2: Selecting TG события (одиночные)
		[
			'sender' => 'ReflectorLogic',
			'action_start' => 'Selecting TG #',
			'action_end' => 'Selecting TG #',
			'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*Selecting TG #(\d+)/',
			'is_single' => true
		],
		// Правило 3: Squelch события (парные)
		// [
		// 	'sender' => 'Rx1',
		// 	'action_start' => 'The squelch is OPEN',
		// 	'action_end' => 'The squelch is CLOSED',
		// 	'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*(The squelch is (OPEN|CLOSED))/',
		// 	'is_single' => false
		// ],
		// Правило 4: Transmitter события (парные)
		[
			'sender' => 'MultiTx',
			'action_start' => 'Turning the transmitter ON',
			'action_end' => 'Turning the transmitter OFF',
			'rule' => '/^([\d\w\s\.:]+):\s*(\w+):\s*(Turning the transmitter (ON|OFF))/',
			'is_single' => false
		]
	];

	// Получаем журнал
	$logLines = getLog("", 2000, false);

	// Фильтруем пустые строки
	$logLines = array_filter($logLines, function ($line) {
		return !empty(trim($line));
	});

	// Обрабатываем ВСЕ события из лога
	$allEvents = debug_filterLogLines($logLines, $activityRules, null);

	// СОРТИРУЕМ события по времени (самые свежие сначала)
	usort($allEvents, function ($a, $b) {
		$timeA = $a['end_time']['unixtime'] ?? $a['start_time']['unixtime'] ?? 0;
		$timeB = $b['end_time']['unixtime'] ?? $b['start_time']['unixtime'] ?? 0;

		return $timeB <=> $timeA;
	});

	// Берем только нужное количество самых свежих событий
	$events = array_slice($allEvents, 0, $limit);

	// Форматируем для отображения
	$result = array_map(function ($event) {
		return [
			'timestamp' => [
				'date' => [
					'iso' => $event['start_time']['raw'] ?? $event['end_time']['raw'] ?? ''
				]
			],
			'sender' => $event['sender'],
			'BC' => 'RF',
			'ID' => $event['talkgroup'] ? 'TG #' . $event['talkgroup'] : '',
			'DS' => $event['callsign'] ?: 'Local',
			'payload' => $event['duration_formatted'],
			'status' => $event['status'],
			// ОТЛАДОЧНАЯ ИНФОРМАЦИЯ
			'_debug_raw_line' => $event['raw_start_line'] ?? $event['raw_line'] ?? '',
			'_debug_event_type' => $event['status']
		];
	}, $events);

	return $result;
}
