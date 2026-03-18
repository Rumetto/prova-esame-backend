<?php

function jsonResponse($data, int $status = 200)
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function successResponse($message, $data = null, int $status = 200)
{
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ], $status);
}

function errorResponse($message, int $status = 400, $errors = null)
{
    jsonResponse([
        'success' => false,
        'message' => $message,
        'errors' => $errors
    ], $status);
}