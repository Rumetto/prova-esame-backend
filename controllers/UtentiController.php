<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class UtentiController
{
    public static function getAll(): void
    {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->query('
            SELECT UtenteID, Email, Admin
            FROM utenti
            ORDER BY UtenteID DESC
        ');

        Response::json($stmt->fetchAll());
    }

    public static function create(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $email = trim($data['Email'] ?? '');
        $password = $data['Password'] ?? '';
        $admin = isset($data['Admin']) ? (int)$data['Admin'] : 0;

        if (!Validator::required($email) || !Validator::validEmail($email)) {
            Response::json(['message' => 'Email non valida o mancante'], 400);
        }

        if (!Validator::required($password)) {
            Response::json(['message' => 'Password obbligatoria'], 400);
        }

        if (!in_array($admin, [0, 1], true)) {
            Response::json(['message' => 'Il campo Admin deve essere 0 o 1'], 400);
        }

        $db = new Database();
        $conn = $db->connect();

        $check = $conn->prepare('SELECT UtenteID FROM utenti WHERE Email = ?');
        $check->execute([$email]);

        if ($check->fetch()) {
            Response::json(['message' => 'Email già utilizzata'], 409);
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('
            INSERT INTO utenti (Email, Password, Admin)
            VALUES (?, ?, ?)
        ');

        $stmt->execute([$email, $hashedPassword, $admin]);

        Response::json([
            'message' => 'Utente creato con successo',
            'UtenteID' => (int)$conn->lastInsertId()
        ], 201);
    }

    public static function update(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        $check = $conn->prepare('SELECT * FROM utenti WHERE UtenteID = ?');
        $check->execute([$id]);
        $existing = $check->fetch();

        if (!$existing) {
            Response::json(['message' => 'Utente non trovato'], 404);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $email = trim($data['Email'] ?? $existing['Email']);
        $password = $data['Password'] ?? null;
        $admin = isset($data['Admin']) ? (int)$data['Admin'] : (int)$existing['Admin'];

        if (!Validator::required($email) || !Validator::validEmail($email)) {
            Response::json(['message' => 'Email non valida o mancante'], 400);
        }

        if (!in_array($admin, [0, 1], true)) {
            Response::json(['message' => 'Il campo Admin deve essere 0 o 1'], 400);
        }

        $checkEmail = $conn->prepare('SELECT UtenteID FROM utenti WHERE Email = ? AND UtenteID <> ?');
        $checkEmail->execute([$email, $id]);

        if ($checkEmail->fetch()) {
            Response::json(['message' => 'Email già utilizzata'], 409);
        }

        if ($password !== null && trim($password) !== '') {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare('
                UPDATE utenti
                SET Email = ?, Password = ?, Admin = ?
                WHERE UtenteID = ?
            ');

            $stmt->execute([$email, $hashedPassword, $admin, $id]);
        } else {
            $stmt = $conn->prepare('
                UPDATE utenti
                SET Email = ?, Admin = ?
                WHERE UtenteID = ?
            ');

            $stmt->execute([$email, $admin, $id]);
        }

        Response::json(['message' => 'Utente aggiornato con successo']);
    }

    public static function delete(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('DELETE FROM utenti WHERE UtenteID = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            Response::json(['message' => 'Utente non trovato'], 404);
        }

        Response::json(['message' => 'Utente eliminato con successo']);
    }
}