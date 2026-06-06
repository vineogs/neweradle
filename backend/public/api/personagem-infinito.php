<?php

header('Content-Type: application/json');

require_once '../../services/PersonagemService.php';
require_once '../../services/GameService.php';

$personagens = PersonagemService::listar();

echo json_encode(
    GameService::personagemAleatorio($personagens)
);
