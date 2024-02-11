<?php

function generarToken($usuario_id) {
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode(['usuario_id' => $usuario_id, 'exp' => time() + 60 * 60]); // Token expira en 1 hora

    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

    $firma = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, 'tu_clave_secreta', true);
    $base64UrlFirma = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($firma));

    return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlFirma;
}

?>