<?php

class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM utenti WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT utente_id, nome, cognome, email, ruolo FROM utenti WHERE utente_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($nome, $cognome, $email, $passwordHash, $ruolo = 'dipendente')
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO utenti (nome, cognome, email, password, ruolo)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([$nome, $cognome, $email, $passwordHash, $ruolo]);

        return $this->findById($this->pdo->lastInsertId());
    }
}