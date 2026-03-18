<?php

class StatsController
{
    public static function pastEventsStats($currentUser)
    {
        RoleMiddleware::requireRole($currentUser, 'organizzatore');

        $dal = $_GET['dal'] ?? null;
        $al = $_GET['al'] ?? null;

        if ($dal && !validateDateYmdHisOrYmd($dal)) {
            errorResponse('Formato data "dal" non valido.', 422);
        }

        if ($al && !validateDateYmdHisOrYmd($al)) {
            errorResponse('Formato data "al" non valido.', 422);
        }

        $eventModel = new Event();
        $stats = $eventModel->getPastStats($dal, $al);

        successResponse('Statistiche eventi passati recuperate con successo.', $stats);
    }
}