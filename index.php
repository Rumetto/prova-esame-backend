<?php

// Permette al frontend React di accedere alle API
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Gestione richieste preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Informazioni base API
$response = [
    "name" => "Corriere API",
    "version" => "1.0",
    "status" => "running",
    "timestamp" => date("Y-m-d H:i:s")
];

echo json_encode($response);