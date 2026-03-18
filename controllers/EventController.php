<?php

class EventController
{
    public static function index($currentUser)
    {
        if ($currentUser['ruolo'] !== 'dipendente' && $currentUser['ruolo'] !== 'organizzatore') {
            errorResponse('Ruolo non autorizzato.', 403);
        }

        $eventModel = new Event();
        $events = $eventModel->getAllUpcoming();

        successResponse('Elenco eventi recuperato con successo.', $events);
    }

    public static function store($currentUser)
    {
        RoleMiddleware::requireRole($currentUser, 'organizzatore');

        $data = getJsonInput();
        $errors = validateRequired($data, ['titolo', 'data_evento', 'descrizione']);

        if (!empty($errors)) {
            errorResponse('Dati non validi.', 422, $errors);
        }

        if (!validateDateYmdHisOrYmd($data['data_evento'])) {
            errorResponse('Formato data non valido. Usa Y-m-d o Y-m-d H:i:s.', 422);
        }

        $dataEvento = date('Y-m-d', strtotime($data['data_evento']));
        $today = date('Y-m-d');

        if ($dataEvento < $today) {
            errorResponse("La data dell'evento non può essere nel passato.", 422);
        }

        $eventModel = new Event();
        $event = $eventModel->create(
            trim($data['titolo']),
            $data['data_evento'],
            trim($data['descrizione'])
        );

        successResponse('Evento creato con successo.', $event, 201);
    }

    public static function update($currentUser, $id)
    {
        RoleMiddleware::requireRole($currentUser, 'organizzatore');

        $eventModel = new Event();
        $existingEvent = $eventModel->getById($id);

        if (!$existingEvent) {
            errorResponse('Evento non trovato.', 404);
        }

        $data = getJsonInput();
        $errors = validateRequired($data, ['titolo', 'data_evento', 'descrizione']);

        if (!empty($errors)) {
            errorResponse('Dati non validi.', 422, $errors);
        }

        if (!validateDateYmdHisOrYmd($data['data_evento'])) {
            errorResponse('Formato data non valido. Usa Y-m-d o Y-m-d H:i:s.', 422);
        }

        $dataEvento = date('Y-m-d', strtotime($data['data_evento']));
        $today = date('Y-m-d');

        if ($dataEvento < $today) {
            errorResponse("La data dell'evento non può essere nel passato.", 422);
        }

        $updatedEvent = $eventModel->update(
            $id,
            trim($data['titolo']),
            $data['data_evento'],
            trim($data['descrizione'])
        );

        successResponse('Evento aggiornato con successo.', $updatedEvent);
    }

    public static function destroy($currentUser, $id)
    {
        RoleMiddleware::requireRole($currentUser, 'organizzatore');

        $eventModel = new Event();
        $existingEvent = $eventModel->getById($id);

        if (!$existingEvent) {
            errorResponse('Evento non trovato.', 404);
        }

        $eventModel->delete($id);

        successResponse('Evento eliminato con successo.');
    }

    public static function participants($currentUser, $id)
    {
        RoleMiddleware::requireRole($currentUser, 'organizzatore');

        $eventModel = new Event();
        $event = $eventModel->getById($id);

        if (!$event) {
            errorResponse('Evento non trovato.', 404);
        }

        $participants = $eventModel->getParticipantsByEvent($id);

        successResponse('Partecipanti recuperati con successo.', [
            'evento' => $event,
            'partecipanti' => $participants
        ]);
    }
}