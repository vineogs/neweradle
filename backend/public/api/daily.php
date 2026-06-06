<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$file = __DIR__ . '/../../data/daily.json';

if (!file_exists($file)) {
    echo json_encode([
        'error' => 'file_not_found'
    ]);
    exit;
}

$data = json_decode(file_get_contents($file), true);

if (!is_array($data)) {
    $data = [];
}

$date = $_GET['date'] ?? date('Y-m-d');

if (!isset($data[$date])) {
    echo json_encode([
        'date' => $date,
        'status' => 'not_ready'
    ]);
    exit;
}

$result = [];

foreach ($data as $date => $info) {
    $result[$date] = [
        'exists' => true
    ];
}

echo json_encode($result);
