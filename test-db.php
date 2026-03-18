<?php

require_once __DIR__ . '/config/database.php';

try {
    $pdo = getPDO();
    echo "Connessione riuscita al database!";
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
}