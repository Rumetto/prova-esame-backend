<?php

require_once __DIR__ . '/../utils/Auth.php';
require_once __DIR__ . '/../utils/Response.php';

class AuthMiddleware
{
    public static function handle(): array
    {
        $token = Auth::getBearerToken();

        if (!$token) {
            Response::json(['message' => 'Token mancante'], 401);
        }

        $payload = Auth::verifyToken($token);

        if (!$payload) {
            Response::json(['message' => 'Token non valido o scaduto'], 401);
        }

        return $payload;
    }
}