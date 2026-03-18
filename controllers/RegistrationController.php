<?php

class RegistrationController
{
    public static function myRegistrations($currentUser)
    {
        RoleMiddleware::requireRole($currentUser, 'dipendente');

        $registrationModel = new Registration();
        $registrations = $registrationModel->getUserRegistrations($currentUser['utente_id']);

        successResponse('Iscrizioni personali recuperate con successo.', $registrations);
    }

    public static function registerToEvent($currentUser, $eventId)
    {
        RoleMiddleware::requireRole($currentUser, 'dipendente');

        $eventModel = new Event();
        $registrationModel = new Registration();

        $event = $eventModel->getById($eventId);

        if (!$event) {
            errorResponse('Evento non trovato.', 404);
        }

        if (strtotime($event['data_evento']) <= strtotime(date('Y-m-d 23:59:59'))) {
            errorResponse('Puoi iscriverti solo fino al giorno prima dell’evento.', 422);
        }

        if ($registrationModel->exists($currentUser['utente_id'], $eventId)) {
            errorResponse('Sei già iscritto a questo evento.', 409);
        }

        $registrationModel->create($currentUser['utente_id'], $eventId);

        successResponse('Iscrizione effettuata con successo.', null, 201);
    }

    public static function unregisterFromEvent($currentUser, $eventId)
    {
        RoleMiddleware::requireRole($currentUser, 'dipendente');

        $eventModel = new Event();
        $registrationModel = new Registration();

        $event = $eventModel->getById($eventId);

        if (!$event) {
            errorResponse('Evento non trovato.', 404);
        }

        if (strtotime($event['data_evento']) <= strtotime(date('Y-m-d 23:59:59'))) {
            errorResponse('Puoi annullare l’iscrizione solo fino al giorno prima dell’evento.', 422);
        }

        $registration = $registrationModel->exists($currentUser['utente_id'], $eventId);

        if (!$registration) {
            errorResponse('Iscrizione non trovata.', 404);
        }

        $registrationModel->deleteByUserAndEvent($currentUser['utente_id'], $eventId);

        successResponse('Iscrizione annullata con successo.');
    }
}