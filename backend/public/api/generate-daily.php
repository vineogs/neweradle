<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../services/PersonagemService.php';
require_once __DIR__ . '/../../services/GameService.php';

$file = __DIR__ . '/../../data/daily.json';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$data = json_decode(file_get_contents($file), true);

if (!is_array($data)) {
    $data = [];
}

date_default_timezone_set('America/Sao_Paulo');

$today = date('Y-m-d');

if (!isset($data[$today])) {

    $personagens = PersonagemService::listar();
    $secreto = GameService::personagemDoDia($personagens);

    $data[$today] = [
        'personagemId' => $secreto['id']
    ];

    $saved = file_put_contents(
        $file,
        json_encode($data, JSON_PRETTY_PRINT)
    );

    if ($saved === false) {
        echo json_encode([
            'error' => 'could_not_write_file'
        ]);
        exit;
    }
}

echo json_encode([
    'date' => $today,
    'personagemId' => $data[$today]['personagemId']
]);
