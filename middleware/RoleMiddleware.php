<?php

class RoleMiddleware
{
    public static function requireRole($user, $role)
    {
        if (($user['ruolo'] ?? null) !== $role) {
            errorResponse('Accesso negato: permessi insufficienti.', 403);
        }
    }
}