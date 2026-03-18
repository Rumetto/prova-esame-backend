<?php

$method = $_SERVER['REQUEST_METHOD'];
$path = getRequestUriPath();

if ($path === '/api/utenti/register' && $method === 'POST') {
    AuthController::register();
}

if ($path === '/api/utenti/login' && $method === 'POST') {
    AuthController::login();
}

if ($path === '/api/eventi' && $method === 'GET') {
    $currentUser = AuthMiddleware::handle();
    EventController::index($currentUser);
}

if ($path === '/api/eventi' && $method === 'POST') {
    $currentUser = AuthMiddleware::handle();
    EventController::store($currentUser);
}

if (preg_match('#^/api/eventi/(\d+)$#', $path, $matches) && $method === 'PUT') {
    $currentUser = AuthMiddleware::handle();
    EventController::update($currentUser, $matches[1]);
}

if (preg_match('#^/api/eventi/(\d+)$#', $path, $matches) && $method === 'DELETE') {
    $currentUser = AuthMiddleware::handle();
    EventController::destroy($currentUser, $matches[1]);
}

if ($path === '/api/eventi/miei' && $method === 'GET') {
    $currentUser = AuthMiddleware::handle();
    RegistrationController::myRegistrations($currentUser);
}

if (preg_match('#^/api/eventi/(\d+)/iscrizione$#', $path, $matches) && $method === 'POST') {
    $currentUser = AuthMiddleware::handle();
    RegistrationController::registerToEvent($currentUser, $matches[1]);
}

if (preg_match('#^/api/eventi/(\d+)/iscrizione$#', $path, $matches) && $method === 'DELETE') {
    $currentUser = AuthMiddleware::handle();
    RegistrationController::unregisterFromEvent($currentUser, $matches[1]);
}

if (preg_match('#^/api/eventi/(\d+)/checkin$#', $path, $matches) && $method === 'POST') {
    $currentUser = AuthMiddleware::handle();
    CheckinController::markCheckin($currentUser, $matches[1]);
}

if (preg_match('#^/api/eventi/(\d+)/partecipanti$#', $path, $matches) && $method === 'GET') {
    $currentUser = AuthMiddleware::handle();
    EventController::participants($currentUser, $matches[1]);
}

if ($path === '/api/statistiche/eventi-passati' && $method === 'GET') {
    $currentUser = AuthMiddleware::handle();
    StatsController::pastEventsStats($currentUser);
}

errorResponse('Endpoint non trovato.', 404);