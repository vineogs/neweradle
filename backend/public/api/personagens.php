<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../../services/PersonagemService.php';

$data = PersonagemService::listar();

echo json_encode($data);
exit;
