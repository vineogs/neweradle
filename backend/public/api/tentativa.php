<?php

session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

require_once '../../services/GameService.php';
require_once '../../services/PersonagemService.php';

$input = json_decode(file_get_contents("php://input"), true);

$modo = $input['modo'] ?? null;
$personagemId = $input['personagemId'] ?? null;
$date = $input['date'] ?? date('Y-m-d');

$personagens = PersonagemService::listar();

$secreto = null;

if ($modo === 'diario' || $modo === 'replay') {

    $file = __DIR__ . '/../../data/daily.json';
    $data = json_decode(file_get_contents($file), true);

    if (!isset($data[$date])) {
        echo json_encode(["error" => "daily_not_generated"]);
        exit;
    }

    $secretoId = $data[$date]['personagemId'];

    foreach ($personagens as $p) {
        if ($p['id'] == $secretoId) {
            $secreto = $p;
            break;
        }
    }
} elseif ($modo === 'infinito') {
    if (!isset($_SESSION['infinito_secreto'])) {
        $secreto = GameService::personagemAleatorio($personagens);
        $_SESSION['infinito_secreto'] = $secreto['id'];
    } else {
        $secretoId = $_SESSION['infinito_secreto'];

        foreach ($personagens as $p) {
            if ($p['id'] == $secretoId) {
                $secreto = $p;
                break;
            }
        }
    }
}

$tentativa = null;

foreach ($personagens as $p) {
    if ($p['id'] == $personagemId) {
        $tentativa = $p;
        break;
    }
}

if (!$secreto || !$tentativa) {
    echo json_encode([
        "error" => "invalid game state"
    ]);
    exit;
}

$dicas = GameService::gerarDicas($tentativa, $secreto);

$acertou = $tentativa['id'] == $secreto['id'];

if ($modo === 'infinito' && $acertou) {
    unset($_SESSION['infinito_secreto']);
}

echo json_encode([
    "acertou" => $acertou,
    "tentativa" => $tentativa,
    "dicas" => $dicas
]);
