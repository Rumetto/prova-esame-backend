<?php

class Registration
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function exists($utenteId, $eventoId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM iscrizioni
            WHERE utente_id = ? AND evento_id = ?
        ");
        $stmt->execute([$utenteId, $eventoId]);
        return $stmt->fetch();
    }

    public function create($utenteId, $eventoId)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO iscrizioni (utente_id, evento_id, checkin_effettuato, ora_checkin)
            VALUES (?, ?, 0, NULL)
        ");
        $stmt->execute([$utenteId, $eventoId]);

        return $this->pdo->lastInsertId();
    }

    public function deleteByUserAndEvent($utenteId, $eventoId)
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM iscrizioni
            WHERE utente_id = ? AND evento_id = ?
        ");
        return $stmt->execute([$utenteId, $eventoId]);
    }

    public function getUserRegistrations($utenteId)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                i.iscrizione_id,
                i.checkin_effettuato,
                i.ora_checkin,
                e.evento_id,
                e.titolo,
                e.data_evento,
                e.descrizione
            FROM iscrizioni i
            INNER JOIN eventi e ON i.evento_id = e.evento_id
            WHERE i.utente_id = ?
            ORDER BY e.data_evento ASC
        ");
        $stmt->execute([$utenteId]);
        return $stmt->fetchAll();
    }

    public function getById($iscrizioneId)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM iscrizioni WHERE iscrizione_id = ?
        ");
        $stmt->execute([$iscrizioneId]);
        return $stmt->fetch();
    }

    public function setCheckin($iscrizioneId)
    {
        $stmt = $this->pdo->prepare("
            UPDATE iscrizioni
            SET checkin_effettuato = 1, ora_checkin = NOW()
            WHERE iscrizione_id = ?
        ");
        return $stmt->execute([$iscrizioneId]);
    }
}