<?php

require_once __DIR__ . '/../utils/Response.php';

class AdminMiddleware
{
    public static function handle(array $user): void
    {
        if (!isset($user['admin']) || (int)$user['admin'] !== 1) {
            Response::json(['message' => 'Accesso negato: solo amministratore'], 403);
        }
    }
}