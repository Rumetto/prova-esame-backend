<?php

class AuthController
{
    public static function register()
    {
        $data = getJsonInput();

        $errors = validateRequired($data, ['nome', 'cognome', 'email', 'password']);
        if (!empty($errors)) {
            errorResponse('Dati non validi.', 422, $errors);
        }

        if (!validateEmail($data['email'])) {
            errorResponse('Email non valida.', 422);
        }

        if (strlen($data['password']) < 6) {
            errorResponse('La password deve contenere almeno 6 caratteri.', 422);
        }

        $userModel = new User();

        if ($userModel->findByEmail($data['email'])) {
            errorResponse('Email già registrata.', 409);
        }

        $ruolo = $data['ruolo'] ?? 'dipendente';
        if (!in_array($ruolo, ['dipendente', 'organizzatore'])) {
            $ruolo = 'dipendente';
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

        $user = $userModel->create(
            trim($data['nome']),
            trim($data['cognome']),
            trim($data['email']),
            $passwordHash,
            $ruolo
        );

        successResponse('Registrazione completata con successo.', $user, 201);
    }

    public static function login()
    {
        $data = getJsonInput();

        $errors = validateRequired($data, ['email', 'password']);
        if (!empty($errors)) {
            errorResponse('Dati non validi.', 422, $errors);
        }

        $userModel = new User();
        $user = $userModel->findByEmail($data['email']);

        if (!$user || !password_verify($data['password'], $user['password'])) {
            errorResponse('Credenziali non valide.', 401);
        }

        $token = generateJWT([
            'utente_id' => $user['utente_id'],
            'nome' => $user['nome'],
            'cognome' => $user['cognome'],
            'email' => $user['email'],
            'ruolo' => $user['ruolo']
        ]);

        successResponse('Login effettuato con successo.', [
            'token' => $token,
            'utente' => [
                'utente_id' => $user['utente_id'],
                'nome' => $user['nome'],
                'cognome' => $user['cognome'],
                'email' => $user['email'],
                'ruolo' => $user['ruolo']
            ]
        ]);
    }
}