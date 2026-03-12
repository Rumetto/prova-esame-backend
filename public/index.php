<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/ClientiController.php';
require_once __DIR__ . '/../controllers/ConsegneController.php';
require_once __DIR__ . '/../controllers/UtentiController.php';
require_once __DIR__ . '/../controllers/TrackingController.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';
require_once __DIR__ . '/../middleware/AdminMiddleware.php';
require_once __DIR__ . '/../utils/Response.php';

header('Access-Control-Allow-Origin: ' . FRONTEND_URL);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

if ($uri === '') {
    $uri = '/';
}

/*
|--------------------------------------------------------------------------
| ROUTE PUBBLICHE
|--------------------------------------------------------------------------
*/

if ($method === 'POST' && $uri === '/auth/login') {
    AuthController::login();
}

if ($method === 'GET' && $uri === '/tracking') {
    TrackingController::track();
}

/*
|--------------------------------------------------------------------------
| ROUTE PROTETTE
|--------------------------------------------------------------------------
*/

$user = AuthMiddleware::handle();

/*
|--------------------------------------------------------------------------
| CLIENTI
|--------------------------------------------------------------------------
*/

if ($method === 'GET' && $uri === '/clienti') {
    ClientiController::getAll();
}

if ($method === 'GET' && preg_match('#^/clienti/(\d+)$#', $uri, $matches)) {
    ClientiController::getById((int)$matches[1]);
}

if ($method === 'POST' && $uri === '/clienti') {
    ClientiController::create();
}

if ($method === 'PUT' && preg_match('#^/clienti/(\d+)$#', $uri, $matches)) {
    ClientiController::update((int)$matches[1]);
}

if ($method === 'DELETE' && preg_match('#^/clienti/(\d+)$#', $uri, $matches)) {
    ClientiController::delete((int)$matches[1]);
}

/*
|--------------------------------------------------------------------------
| CONSEGNE
|--------------------------------------------------------------------------
*/

if ($method === 'GET' && $uri === '/consegne') {
    ConsegneController::getAll();
}

if ($method === 'GET' && preg_match('#^/consegne/(\d+)$#', $uri, $matches)) {
    ConsegneController::getById((int)$matches[1]);
}

if ($method === 'POST' && $uri === '/consegne') {
    ConsegneController::create();
}

if ($method === 'PUT' && preg_match('#^/consegne/(\d+)$#', $uri, $matches)) {
    ConsegneController::update((int)$matches[1]);
}

if ($method === 'DELETE' && preg_match('#^/consegne/(\d+)$#', $uri, $matches)) {
    ConsegneController::delete((int)$matches[1]);
}

/*
|--------------------------------------------------------------------------
| UTENTI - SOLO ADMIN
|--------------------------------------------------------------------------
*/

if ($uri === '/utenti') {
    AdminMiddleware::handle($user);

    if ($method === 'GET') {
        UtentiController::getAll();
    }

    if ($method === 'POST') {
        UtentiController::create();
    }
}

if (preg_match('#^/utenti/(\d+)$#', $uri, $matches)) {
    AdminMiddleware::handle($user);

    if ($method === 'PUT') {
        UtentiController::update((int)$matches[1]);
    }

    if ($method === 'DELETE') {
        UtentiController::delete((int)$matches[1]);
    }
}

Response::json(['message' => 'Rotta non trovata'], 404);