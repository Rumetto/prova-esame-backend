# Prova Esame Backend

Backend PHP REST per la gestione di:
- autenticazione utenti
- eventi formativi
- iscrizioni
- check-in
- statistiche eventi passati

## Requisiti
- PHP 8+
- MySQL
- Apache (o server compatibile con .htaccess)

## Setup
1. Creare il database eseguendo:
   - `database/schema.sql`
   - `database/seed.sql`

2. Configurare `.env`

3. Avviare il backend in locale

## Endpoint principali
- POST `/api/utenti/register`
- POST `/api/utenti/login`
- GET `/api/eventi`
- GET `/api/eventi/miei`
- POST `/api/eventi/{id}/iscrizione`
- DELETE `/api/eventi/{id}/iscrizione`
- POST `/api/eventi`
- PUT `/api/eventi/{id}`
- DELETE `/api/eventi/{id}`
- GET `/api/eventi/{id}/partecipanti`
- POST `/api/eventi/{id}/checkin`
- GET `/api/statistiche/eventi-passati`

## Test API
Importare `docs/postman_collection.json` in Postman.