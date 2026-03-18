<?php

class CheckinController
{
    public static function markCheckin($currentUser, $eventId)
    {
        RoleMiddleware::requireRole($currentUser, 'organizzatore');

        $data = getJsonInput();
        $errors = validateRequired($data, ['iscrizione_id']);

        if (!empty($errors)) {
            errorResponse('Dati non validi.', 422, $errors);
        }

        $eventModel = new Event();
        $registrationModel = new Registration();

        $event = $eventModel->getById($eventId);
        if (!$event) {
            errorResponse('Evento non trovato.', 404);
        }

        $registration = $registrationModel->getById($data['iscrizione_id']);
        if (!$registration) {
            errorResponse('Iscrizione non trovata.', 404);
        }

        if ((int)$registration['evento_id'] !== (int)$eventId) {
            errorResponse('L’iscrizione non appartiene a questo evento.', 422);
        }

        if ((int)$registration['checkin_effettuato'] === 1) {
            errorResponse('Check-in già effettuato.', 409);
        }

        $today = date('Y-m-d');
        $eventDate = date('Y-m-d', strtotime($event['data_evento']));

        if ($eventDate > $today) {
            errorResponse(
                'Puoi registrare il check-in solo il giorno dell’evento o dopo che l’evento è avvenuto.',
                422
            );
        }

        $registrationModel->setCheckin($data['iscrizione_id']);

        successResponse('Check-in registrato con successo.');
    }
}