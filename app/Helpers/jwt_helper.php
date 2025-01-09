<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generate_jwt($payload, $secret_key, $expiration_time = 3600) {
    $issued_at = time();
    $expire_at = $issued_at + $expiration_time;

    $payload['iat'] = $issued_at;
    $payload['exp'] = $expire_at;

    return JWT::encode($payload, $secret_key, 'HS256');
}

function validate_jwt($token, $secret_key) {
    try {
        return JWT::decode($token, new Key($secret_key, 'HS256'));
    } catch (Exception $e) {
        return false;
    }
}


