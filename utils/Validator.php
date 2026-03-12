<?php

require_once __DIR__ . '/../config/config.php';

class Validator
{
    public static function required(?string $value): bool
    {
        return isset($value) && trim($value) !== '';
    }

    public static function validEmail(?string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validStatoConsegna(?string $stato): bool
    {
        return in_array($stato, STATI_CONSEGNA, true);
    }
}