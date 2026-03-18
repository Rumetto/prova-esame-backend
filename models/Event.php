<?php

class Event
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function getAllUpcoming()
    {
        $stmt = $this->pdo->query("
            SELECT evento_id, titolo, data_evento, descrizione
            FROM eventi
            ORDER BY data_evento ASC
        ");

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT evento_id, titolo, data_evento, descrizione
            FROM eventi
            WHERE evento_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($titolo, $dataEvento, $descrizione)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO eventi (titolo, data_evento, descrizione)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$titolo, $dataEvento, $descrizione]);

        return $this->getById($this->pdo->lastInsertId());
    }

    public function update($id, $titolo, $dataEvento, $descrizione)
    {
        $stmt = $this->pdo->prepare("
            UPDATE eventi
            SET titolo = ?, data_evento = ?, descrizione = ?
            WHERE evento_id = ?
        ");
        $stmt->execute([$titolo, $dataEvento, $descrizione, $id]);

        return $this->getById($id);
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM eventi WHERE evento_id = ?");
        return $stmt->execute([$id]);
    }

    public function getPastStats($dal = null, $al = null)
    {
        $sql = "
            SELECT 
                e.evento_id,
                e.titolo,
                e.data_evento,
                COUNT(i.iscrizione_id) AS totale_iscritti,
                SUM(CASE WHEN i.checkin_effettuato = 1 THEN 1 ELSE 0 END) AS totale_checkin,
                CASE
                    WHEN COUNT(i.iscrizione_id) = 0 THEN 0
                    ELSE ROUND((SUM(CASE WHEN i.checkin_effettuato = 1 THEN 1 ELSE 0 END) / COUNT(i.iscrizione_id)) * 100, 2)
                END AS percentuale_partecipazione
            FROM eventi e
            LEFT JOIN iscrizioni i ON e.evento_id = i.evento_id
            WHERE e.data_evento < NOW()
        ";

        $params = [];

        if ($dal) {
            $sql .= " AND DATE(e.data_evento) >= ?";
            $params[] = $dal;
        }

        if ($al) {
            $sql .= " AND DATE(e.data_evento) <= ?";
            $params[] = $al;
        }

        $sql .= " GROUP BY e.evento_id ORDER BY e.data_evento DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getParticipantsByEvent($eventId)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                i.iscrizione_id,
                i.checkin_effettuato,
                i.ora_checkin,
                u.utente_id,
                u.nome,
                u.cognome,
                u.email
            FROM iscrizioni i
            INNER JOIN utenti u ON i.utente_id = u.utente_id
            WHERE i.evento_id = ?
            ORDER BY u.cognome, u.nome
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }
}