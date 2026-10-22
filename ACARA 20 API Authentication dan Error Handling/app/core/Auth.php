<?php

class Auth
{
    private const SECRET = 'kunci_rahasia_workshop_2026';

    private static function base64url(string $data): string
    {
        return rtrim(
            strtr(base64_encode($data), '+/', '-_'),
            '='
        );
    }

    public static function generateToken(array $payload): string
    {
        $header = self::base64url(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]));

        $payload['exp'] = time() + 3600;

        $payloadEncoded = self::base64url(
            json_encode($payload)
        );

        $signature = self::base64url(
            hash_hmac(
                'sha256',
                "$header.$payloadEncoded",
                self::SECRET,
                true
            )
        );

        return "$header.$payloadEncoded.$signature";
    }

    public static function validateToken(string $token)
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parts;

        $validSignature = self::base64url(
            hash_hmac(
                'sha256',
                "$header.$payload",
                self::SECRET,
                true
            )
        );

        if (!hash_equals($validSignature, $signature)) {
            return false;
        }

        $decodedPayload = base64_decode(
            strtr($payload, '-_', '+/')
        );

        $data = json_decode($decodedPayload, true);

        if (!is_array($data) || !isset($data['exp'])) {
            return false;
        }

        if ($data['exp'] <= time()) {
            return false;
        }

        return $data;
    }

    public static function requireAuth(): array
{
    $authHeader = '';

    if (function_exists('getallheaders')) {
        $headers = getallheaders();

        foreach ($headers as $name => $value) {
            if (strtolower($name) === 'authorization') {
                $authHeader = $value;
                break;
            }
        }
    }

    if ($authHeader === '') {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION']
            ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
            ?? '';
    }

    if (!preg_match('/^Bearer\s+(\S+)$/i', trim($authHeader), $matches)) {
        Response::json(401, false, 'Token tidak ditemukan');
    }

    $payload = self::validateToken($matches[1]);

    if ($payload === false) {
        Response::json(401, false, 'Token tidak valid atau kedaluwarsa');
    }

    return $payload;
}
}
