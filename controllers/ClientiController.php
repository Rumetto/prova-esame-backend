<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class ClientiController
{
    public static function getAll(): void
    {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->query('SELECT * FROM clienti ORDER BY ClienteID DESC');
        $clienti = $stmt->fetchAll();

        Response::json($clienti);
    }

    public static function getById(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('SELECT * FROM clienti WHERE ClienteID = ?');
        $stmt->execute([$id]);
        $cliente = $stmt->fetch();

        if (!$cliente) {
            Response::json(['message' => 'Cliente non trovato'], 404);
        }

        Response::json($cliente);
    }

    public static function create(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $nominativo = trim($data['Nominativo'] ?? '');
        $via = trim($data['Via'] ?? '');
        $comune = trim($data['Comune'] ?? '');
        $provincia = trim($data['Provincia'] ?? '');
        $telefono = trim($data['Telefono'] ?? '');
        $email = trim($data['Email'] ?? '');
        $note = trim($data['Note'] ?? '');

        if (!Validator::required($nominativo) || !Validator::required($via)) {
            Response::json(['message' => 'Nominativo e Via sono obbligatori'], 400);
        }

        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('
            INSERT INTO clienti (Nominativo, Via, Comune, Provincia, Telefono, Email, Note)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');

        $stmt->execute([
            $nominativo,
            $via,
            $comune,
            $provincia,
            $telefono,
            $email,
            $note
        ]);

        Response::json([
            'message' => 'Cliente creato con successo',
            'ClienteID' => (int)$conn->lastInsertId()
        ], 201);
    }

    public static function update(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        $check = $conn->prepare('SELECT * FROM clienti WHERE ClienteID = ?');
        $check->execute([$id]);
        $existing = $check->fetch();

        if (!$existing) {
            Response::json(['message' => 'Cliente non trovato'], 404);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $nominativo = trim($data['Nominativo'] ?? $existing['Nominativo']);
        $via = trim($data['Via'] ?? $existing['Via']);
        $comune = trim($data['Comune'] ?? $existing['Comune']);
        $provincia = trim($data['Provincia'] ?? $existing['Provincia']);
        $telefono = trim($data['Telefono'] ?? $existing['Telefono']);
        $email = trim($data['Email'] ?? $existing['Email']);
        $note = trim($data['Note'] ?? $existing['Note']);

        if (!Validator::required($nominativo) || !Validator::required($via)) {
            Response::json(['message' => 'Nominativo e Via sono obbligatori'], 400);
        }

        $stmt = $conn->prepare('
            UPDATE clienti
            SET Nominativo = ?, Via = ?, Comune = ?, Provincia = ?, Telefono = ?, Email = ?, Note = ?
            WHERE ClienteID = ?
        ');

        $stmt->execute([
            $nominativo,
            $via,
            $comune,
            $provincia,
            $telefono,
            $email,
            $note,
            $id
        ]);

        Response::json(['message' => 'Cliente aggiornato con successo']);
    }

    public static function delete(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        try {
            $stmt = $conn->prepare('DELETE FROM clienti WHERE ClienteID = ?');
            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                Response::json(['message' => 'Cliente non trovato'], 404);
            }

            Response::json(['message' => 'Cliente eliminato con successo']);
        } catch (PDOException $e) {
            Response::json([
                'message' => 'Impossibile eliminare il cliente: esistono consegne associate'
            ], 409);
        }
    }
}