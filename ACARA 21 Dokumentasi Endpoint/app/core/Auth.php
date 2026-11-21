<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Response.php';

class Auth
{
    private static string $secretKey = 'ACARA21_SECRET_KEY_GANTI_DENGAN_KUNCI_AMAN';

    public static function generateToken(array $payload): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];

        $headerEncoded = self::base64UrlEncode(
            json_encode($header)
        );

        $payload['iat'] = time();
        $payload['exp'] = time() + 3600;

        $payloadEncoded = self::base64UrlEncode(
            json_encode($payload)
        );

        $signature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            self::$secretKey,
            true
        );

        return $headerEncoded . '.' .
            $payloadEncoded . '.' .
            self::base64UrlEncode($signature);
    }

    public static function requireAuth(): array
    {
        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $authorization = $headers['Authorization']
            ?? $headers['authorization']
            ?? $_SERVER['HTTP_AUTHORIZATION']
            ?? '';

        if (!preg_match('/Bearer\s+(\S+)/', $authorization, $matches)) {
            Response::json(401, false, 'Token tidak ditemukan');
        }

        $token = $matches[1];
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            Response::json(401, false, 'Format token tidak valid');
        }

        [$header, $payload, $signature] = $parts;

        $expectedSignature = self::base64UrlEncode(
            hash_hmac(
                'sha256',
                $header . '.' . $payload,
                self::$secretKey,
                true
            )
        );

        if (!hash_equals($expectedSignature, $signature)) {
            Response::json(401, false, 'Token tidak valid');
        }

        $decodedPayload = json_decode(
            self::base64UrlDecode($payload),
            true
        );

        if (!is_array($decodedPayload)) {
            Response::json(401, false, 'Payload token tidak valid');
        }

        if (($decodedPayload['exp'] ?? 0) < time()) {
            Response::json(401, false, 'Token sudah kedaluwarsa');
        }

        return $decodedPayload;
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(
            strtr($data, '-_', '+/')
        ) ?: '';
    }
}
