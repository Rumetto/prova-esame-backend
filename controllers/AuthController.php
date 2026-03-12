<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';
require_once __DIR__ . '/../utils/Auth.php';

class AuthController
{
    public static function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!Validator::required($email) || !Validator::required($password)) {
            Response::json(['message' => 'Email e password sono obbligatorie'], 400);
        }

        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('SELECT * FROM utenti WHERE Email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['Password'])) {
            Response::json(['message' => 'Credenziali non valide'], 401);
        }

        $token = Auth::generateToken($user);

        Response::json([
            'message' => 'Login effettuato con successo',
            'token' => $token,
            'user' => [
                'UtenteID' => (int)$user['UtenteID'],
                'Email' => $user['Email'],
                'Admin' => (int)$user['Admin']
            ]
        ]);
    }
}