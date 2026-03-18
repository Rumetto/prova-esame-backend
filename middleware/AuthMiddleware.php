<?php

class AuthMiddleware
{
    public static function handle()
    {
        $token = getBearerToken();

        if (!$token) {
            errorResponse('Token mancante.', 401);
        }

        $payload = verifyJWT($token);

        if (!$payload) {
            errorResponse('Token non valido o scaduto.', 401);
        }

        return $payload;
    }
}