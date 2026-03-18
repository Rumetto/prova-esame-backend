<?php

function base64UrlEncode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data)
{
    return base64_decode(strtr($data, '-_', '+/'));
}

function jwtSecret()
{
    return env('JWT_SECRET', 'supersegreto_esame_2026');
}

function generateJWT($payload, $expiresInSeconds = 86400)
{
    $header = [
        'alg' => 'HS256',
        'typ' => 'JWT'
    ];

    $now = time();
    $payload['iat'] = $now;
    $payload['exp'] = $now + $expiresInSeconds;

    $headerEncoded = base64UrlEncode(json_encode($header));
    $payloadEncoded = base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, jwtSecret(), true);
    $signatureEncoded = base64UrlEncode($signature);

    return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
}

function verifyJWT($token)
{
    $parts = explode('.', $token);

    if (count($parts) !== 3) {
        return false;
    }

    [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

    $expectedSignature = base64UrlEncode(
        hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, jwtSecret(), true)
    );

    if (!hash_equals($expectedSignature, $signatureEncoded)) {
        return false;
    }

    $payload = json_decode(base64UrlDecode($payloadEncoded), true);

    if (!$payload || !isset($payload['exp']) || time() > $payload['exp']) {
        return false;
    }

    return $payload;
}