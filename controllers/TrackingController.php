<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/Response.php';

class TrackingController
{
    public static function track(): void
    {
        $chiaveConsegna = $_GET['chiaveConsegna'] ?? null;
        $dataRitiro = $_GET['dataRitiro'] ?? null;

        if (!$chiaveConsegna || !$dataRitiro) {
            Response::json([
                'message' => 'chiaveConsegna e dataRitiro sono obbligatori'
            ], 400);
        }

        $db = new Database();
        $conn = $db->connect();

        $stmt = $conn->prepare('
            SELECT Stato, DataRitiro, DataConsegna
            FROM consegne
            WHERE ChiaveConsegna = ? AND DataRitiro = ?
            LIMIT 1
        ');
        $stmt->execute([$chiaveConsegna, $dataRitiro]);
        $consegna = $stmt->fetch();

        if (!$consegna) {
            Response::json(['message' => 'Consegna non trovata'], 404);
        }

        Response::json([
            'statoConsegna' => $consegna['Stato'],
            'dataRitiro' => $consegna['DataRitiro'],
            'dataConsegna' => $consegna['DataConsegna']
        ]);
    }
}