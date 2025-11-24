<?php

/**
 * Функции для работы с активностью
 * @file funct_debug_active.php
 * @date 2025-11-24
 * @version 0.1.2
 * Логика отображения активности
 * 
 * Поиск активности
 * Функция получает параметры 
 * log - файл журнала
 * filters - массив фильтров
 * limit - максимальное количество событий
 * 
 * filters - массив фильтров
 *  - sender - имя отправителя
 *  - action_start, action_end
 *  - rule - правил разбора (регулярное выражение)
 * Например: 
 * Строка в журнале
 * "24 Nov 2025 11:30:07.294: ReflectorLogic: Talker stop on TG #77: R2ADU-1"
 * $filters = [
 *  ['sender' => 'ReflectorLogic'],
 *  ['action_start' => 'Talker start on TG #'],
 *  ['action_end' => 'Talker stop on TG #'],
 *  ['rule' => тут regexp которое вернет несколько частей строки - '24 Nov 2025 11:30:07.294', 'ReflectorLogic', tart|stop, '77', 'R2ADU-1'],
 * ];
 * Работа функции заключается в переборе всех строк журнала и поиском совпадений с фильтрами,
 * после чего формированием массива пар событий (начало-конец).
 * Для выполнения логики (напр. для получения даты) предпочтительно использовать готовые функции php
 */

/** 
Пример Журнал событий
24 Nov 2025 11:30:11.203: ReflectorLogic: Talker start on TG #77: R2ADU-1
24 Nov 2025 11:30:11.203: ### В разговорной группе #77 передает R2ADU-1
24 Nov 2025 11:30:15.896: Rx1: The squelch is CLOSED
24 Nov 2025 11:30:15.896: ReflectorLogic: Talker stop on TG #77: R2ADU-1

$rules[0] = [
	'sender' => 'ReflectorLogic',
	'action_start' => 'Talker start on TG #',
	'action_end' => 'Talker stop on TG #',
	'rule' => '...'
] 

result = debug_filterLogLines($logLines, $rules , $limit = null)
--->
result[0] = [
	'start_time' => $timestamp['raw' = '24 Nov 2025 11:30:11.203', 'unixtime'= 1763.....]
	'end_time' => $timestamp['raw' = '24 Nov 2025 11:30:15.896', 'unixtime'= 1763.....]
	'duration' => 'end_time' - 'start_time' в unixtime',
	'sender' => 'ReflectorLogic',
	'payload' => 'Talker start on TG #77: R2ADU-1',
	'talkgroup' => '77',
	'scallsign' => 'R2ADU-1',
]

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
            
            // Сразу добавляем событие как "in progress"
            $event_data = [
                'start_time' => $parsed,
                'end_time' => '',
                'sender' => $parsed['sender'],
                'payload' => 'Turning the transmitter',
                'action' => 'in progress', // Просто используем "in progress"
                'tg_info' => []
            ];

            $result['events'][] = $event_data;
        } elseif (strpos($line, 'Turning the transmitter OFF') !== false) {
            $parsed = debug_parseLogLine($line);
            
            if (isset($pendingEvents['transmitter'])) {
                $event = $pendingEvents['transmitter'];
                
                // Находим и заменяем "in progress" на завершенное событие
                foreach ($result['events'] as $key => $existing_event) {
                    if ($existing_event['action'] === 'in progress' && 
                        $existing_event['start_time']['date']['unixtime'] === $event['start_time']['date']['unixtime'] &&
                        $existing_event['sender'] === $event['sender']) {
                        
                        // Заменяем на завершенное событие
                        $result['events'][$key] = [
                            'start_time' => $event['start_time'],
                            'end_time' => $parsed,
                            'sender' => $event['sender'],
                            'payload' => $event['payload'],
                            'action' => 'ON->OFF',
                            'tg_info' => []
                        ];
                        break;
                    }
                }
                unset($pendingEvents['transmitter']);
            }
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
            
            // Сразу добавляем событие как "in progress"
            $event_data = [
                'start_time' => $parsed,
                'end_time' => '',
                'sender' => $parsed['sender'],
                'payload' => 'The squelch is',
                'action' => 'in progress',
                'tg_info' => []
            ];

            $result['events'][] = $event_data;
        } elseif (strpos($line, 'The squelch is CLOSED') !== false) {
            $parsed = debug_parseLogLine($line);
            
            if (isset($pendingEvents['squelch'])) {
                $event = $pendingEvents['squelch'];
                
                // Находим и заменяем "in progress" на завершенное событие
                foreach ($result['events'] as $key => $existing_event) {
                    if ($existing_event['action'] === 'in progress' && 
                        $existing_event['start_time']['date']['unixtime'] === $event['start_time']['date']['unixtime'] &&
                        $existing_event['sender'] === $event['sender']) {
                        
                        // Заменяем на завершенное событие
                        $result['events'][$key] = [
                            'start_time' => $event['start_time'],
                            'end_time' => $parsed,
                            'sender' => $event['sender'],
                            'payload' => $event['payload'],
                            'action' => 'OPEN->CLOSED',
                            'tg_info' => []
                        ];
                        break;
                    }
                }
                unset($pendingEvents['squelch']);
            }
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
            
            // Сразу добавляем событие как "in progress"
            $event_data = [
                'start_time' => $parsed,
                'end_time' => '',
                'sender' => $parsed['sender'],
                'payload' => 'Talker on TG',
                'action' => 'in progress',
                'tg_info' => $parsed['tg_info']
            ];

            $result['events'][] = $event_data;
        } elseif (strpos($line, 'Talker stop on TG') !== false) {
            $parsed = debug_parseLogLine($line);
            
            if (isset($pendingEvents['talker'])) {
                $event = $pendingEvents['talker'];
                
                // Находим и заменяем "in progress" на завершенное событие
                foreach ($result['events'] as $key => $existing_event) {
                    if ($existing_event['action'] === 'in progress' && 
                        $existing_event['start_time']['date']['unixtime'] === $event['start_time']['date']['unixtime'] &&
                        $existing_event['sender'] === $event['sender'] &&
                        isset($existing_event['tg_info']['tg_number']) && 
                        $existing_event['tg_info']['tg_number'] === $event['tg_info']['tg_number']) {
                        
                        // Заменяем на завершенное событие
                        $result['events'][$key] = [
                            'start_time' => $event['start_time'],
                            'end_time' => $parsed,
                            'sender' => $event['sender'],
                            'payload' => $event['payload'],
                            'action' => 'start->stop',
                            'tg_info' => $event['tg_info']
                        ];
                        break;
                    }
                }
                unset($pendingEvents['talker']);
            }
        }

        // Блок 3.1: Add/timeout/refresh temporary monitor for TG
        if (strpos($line, 'Add temporary monitor for TG') !== false) {
            $parsed = debug_parseLogLine($line);
            $tg_number = $parsed['tg_info']['tg_number'];
            
            // Сразу добавляем событие как "in progress"
            $event_data = [
                'start_time' => $parsed,
                'end_time' => '',
                'sender' => $parsed['sender'],
                'payload' => 'Temporary monitor for TG',
                'action' => 'in progress',
                'tg_info' => $parsed['tg_info']
            ];

            $result['events'][] = $event_data;

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
                $old_start_time = $pendingMonitors[$tg_number]['start_time'];
                $pendingMonitors[$tg_number]['start_time'] = $parsed;
                $pendingMonitors[$tg_number]['start_line'] = $line;
                $pendingMonitors[$tg_number]['start_action'] = 'monitor_refresh';

                // Обновляем "in progress" событие с новым временем
                foreach ($result['events'] as $key => $existing_event) {
                    if ($existing_event['action'] === 'in progress' && 
                        $existing_event['start_time']['date']['unixtime'] === $old_start_time['date']['unixtime'] &&
                        $existing_event['sender'] === $parsed['sender'] &&
                        isset($existing_event['tg_info']['tg_number']) && 
                        $existing_event['tg_info']['tg_number'] === $tg_number) {
                        
                        // Обновляем время начала
                        $result['events'][$key]['start_time'] = $parsed;
                        break;
                    }
                }
            } else {
                // Если нет записи о Add, создаем новый "in progress"
                $event_data = [
                    'start_time' => $parsed,
                    'end_time' => '',
                    'sender' => $parsed['sender'],
                    'payload' => 'Temporary monitor for TG',
                    'action' => 'in progress',
                    'tg_info' => $parsed['tg_info']
                ];

                $result['events'][] = $event_data;

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

                // Находим и заменяем "in progress" на завершенное событие
                foreach ($result['events'] as $key => $existing_event) {
                    if ($existing_event['action'] === 'in progress' && 
                        $existing_event['start_time']['date']['unixtime'] === $event['start_time']['date']['unixtime'] &&
                        $existing_event['sender'] === $event['sender'] &&
                        isset($existing_event['tg_info']['tg_number']) && 
                        $existing_event['tg_info']['tg_number'] === $tg_number) {
                        
                        // Заменяем на завершенное событие
                        $result['events'][$key] = [
                            'start_time' => $event['start_time'],
                            'end_time' => $parsed,
                            'sender' => $event['sender'],
                            'payload' => $event['payload'],
                            'action' => $event['start_action'] . '->timeout',
                            'tg_info' => $event['tg_info']
                        ];
                        break;
                    }
                }
                unset($pendingMonitors[$tg_number]);
            }
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
                'tg_info' => $parsed['tg_info']
            ];

            $result['events'][] = $event_data;
        }
    }

    // Сортируем события по времени начала (самые свежие сначала)
    usort($result['events'], function($a, $b) {
        $timeA = $a['start_time']['date']['unixtime'] ?? 0;
        $timeB = $b['start_time']['date']['unixtime'] ?? 0;
        
        return $timeB - $timeA; // по убыванию (свежие сначала)
    });

    // Применяем фильтры после всей обработки
    if ($filters !== null && !empty($filters)) {
        $filtered_events = [];
        foreach ($result['events'] as $event) {
            if (debug_shouldIncludeEvent($event, $filters, $include)) {
                $filtered_events[] = $event;
            }
        }
        $result['events'] = $filtered_events;
    }

    // Применяем limit если указан (берем первые $limit элементов)
    if ($limit !== null && $limit > 0) {
        $result['events'] = array_slice($result['events'], 0, $limit);
    }

    $result['result'] = 'success';
    return $result;
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
        $result['action'] = 'Selecting TG';
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
