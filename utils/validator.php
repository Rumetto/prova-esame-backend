<?php

function validateRequired($data, $fields)
{
    $errors = [];

    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim((string)$data[$field]) === '') {
            $errors[$field] = "Il campo {$field} è obbligatorio.";
        }
    }

    return $errors;
}

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validateDateYmdHisOrYmd($date)
{
    $formats = ['Y-m-d', 'Y-m-d H:i:s'];

    foreach ($formats as $format) {
        $d = DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) === $date) {
            return true;
        }
    }

    return false;
}