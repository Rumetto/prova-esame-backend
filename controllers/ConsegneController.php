<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../utils/Validator.php';

class ConsegneController
{
    public static function getAll(): void
    {
        $db = new Database();
        $conn = $db->connect();

        $stato = $_GET['stato'] ?? null;
        $cliente = $_GET['cliente'] ?? null;

        $sql = '
            SELECT 
                c.ConsegnaID,
                c.ClienteID,
                cl.Nominativo,
                c.DataRitiro,
                c.DataConsegna,
                c.Stato,
                c.ChiaveConsegna
            FROM consegne c
            JOIN clienti cl ON c.ClienteID = cl.ClienteID
            WHERE 1=1
        ';

        $params = [];

        if ($stato !== null && $stato !== '') {
            $sql .= ' AND c.Stato = ?';
            $params[] = $stato;
        }

        if ($cliente !== null && $cliente !== '') {
            $sql .= ' AND c.ClienteID = ?';
            $params[] = $cliente;
        }

        $sql .= ' ORDER BY c.ConsegnaID DESC';

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        Response::json($stmt->fetchAll());
    }

    public static function getById(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('
            SELECT 
                c.ConsegnaID,
                c.ClienteID,
                cl.Nominativo,
                c.DataRitiro,
                c.DataConsegna,
                c.Stato,
                c.ChiaveConsegna
            FROM consegne c
            JOIN clienti cl ON c.ClienteID = cl.ClienteID
            WHERE c.ConsegnaID = ?
        ');
        $stmt->execute([$id]);
        $consegna = $stmt->fetch();

        if (!$consegna) {
            Response::json(['message' => 'Consegna non trovata'], 404);
        }

        Response::json($consegna);
    }

    public static function create(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $clienteID = $data['ClienteID'] ?? null;
        $dataRitiro = $data['DataRitiro'] ?? null;
        $dataConsegna = $data['DataConsegna'] ?? null;
        $stato = $data['Stato'] ?? null;
        $chiaveConsegna = trim($data['ChiaveConsegna'] ?? '');

        if (!$clienteID || !$dataRitiro || !Validator::required($chiaveConsegna)) {
            Response::json([
                'message' => 'ClienteID, DataRitiro e ChiaveConsegna sono obbligatori'
            ], 400);
        }

        if (!Validator::validStatoConsegna($stato)) {
            Response::json(['message' => 'Stato consegna non valido'], 400);
        }

        $db = new Database();
        $conn = $db->connect();

        $checkCliente = $conn->prepare('SELECT ClienteID FROM clienti WHERE ClienteID = ?');
        $checkCliente->execute([$clienteID]);

        if (!$checkCliente->fetch()) {
            Response::json(['message' => 'Cliente associato non trovato'], 400);
        }

        try {
            $stmt = $conn->prepare('
                INSERT INTO consegne (ClienteID, DataRitiro, DataConsegna, Stato, ChiaveConsegna)
                VALUES (?, ?, ?, ?, ?)
            ');

            $stmt->execute([
                $clienteID,
                $dataRitiro,
                $dataConsegna ?: null,
                $stato,
                $chiaveConsegna
            ]);

            Response::json([
                'message' => 'Consegna creata con successo',
                'ConsegnaID' => (int)$conn->lastInsertId()
            ], 201);
        } catch (PDOException $e) {
            Response::json([
                'message' => 'ChiaveConsegna già esistente o dati non validi'
            ], 409);
        }
    }

    public static function update(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        $check = $conn->prepare('SELECT * FROM consegne WHERE ConsegnaID = ?');
        $check->execute([$id]);
        $existing = $check->fetch();

        if (!$existing) {
            Response::json(['message' => 'Consegna non trovata'], 404);
        }

        $data = json_decode(file_get_contents('php://input'), true);

        $clienteID = $data['ClienteID'] ?? $existing['ClienteID'];
        $dataRitiro = $data['DataRitiro'] ?? $existing['DataRitiro'];
        $dataConsegna = array_key_exists('DataConsegna', $data) ? $data['DataConsegna'] : $existing['DataConsegna'];
        $stato = $data['Stato'] ?? $existing['Stato'];
        $chiaveConsegna = trim($data['ChiaveConsegna'] ?? $existing['ChiaveConsegna']);

        if (!$clienteID || !$dataRitiro || !Validator::required($chiaveConsegna)) {
            Response::json([
                'message' => 'ClienteID, DataRitiro e ChiaveConsegna sono obbligatori'
            ], 400);
        }

        if (!Validator::validStatoConsegna($stato)) {
            Response::json(['message' => 'Stato consegna non valido'], 400);
        }

        $checkCliente = $conn->prepare('SELECT ClienteID FROM clienti WHERE ClienteID = ?');
        $checkCliente->execute([$clienteID]);

        if (!$checkCliente->fetch()) {
            Response::json(['message' => 'Cliente associato non trovato'], 400);
        }

        try {
            $stmt = $conn->prepare('
                UPDATE consegne
                SET ClienteID = ?, DataRitiro = ?, DataConsegna = ?, Stato = ?, ChiaveConsegna = ?
                WHERE ConsegnaID = ?
            ');

            $stmt->execute([
                $clienteID,
                $dataRitiro,
                $dataConsegna ?: null,
                $stato,
                $chiaveConsegna,
                $id
            ]);

            Response::json(['message' => 'Consegna aggiornata con successo']);
        } catch (PDOException $e) {
            Response::json([
                'message' => 'ChiaveConsegna già esistente o dati non validi'
            ], 409);
        }
    }

    public static function delete(int $id): void
    {
        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('DELETE FROM consegne WHERE ConsegnaID = ?');
        $stmt->execute([$id]);

        if ($stmt->rowCount() === 0) {
            Response::json(['message' => 'Consegna non trovata'], 404);
        }

        Response::json(['message' => 'Consegna eliminata con successo']);
    }
}