<?php

class Auth
{
    private static $secretKey = 'ACARA22_SECRET_KEY';

    public static function generateToken($user)
    {
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        $payload = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'iat' => time(),
            'exp' => time() + 3600
        ];

        $headerEncoded = self::base64UrlEncode(
            json_encode($header)
        );

        $payloadEncoded = self::base64UrlEncode(
            json_encode($payload)
        );

        $signature = hash_hmac(
            'sha256',
            $headerEncoded . '.' . $payloadEncoded,
            self::$secretKey,
            true
        );

        $signatureEncoded = self::base64UrlEncode($signature);

        return $headerEncoded . '.' .
               $payloadEncoded . '.' .
               $signatureEncoded;
    }

    public static function validateToken()
    {
        $headers = getallheaders();

        $authorization = $headers['Authorization']
            ?? $headers['authorization']
            ?? '';

        if (!preg_match('/Bearer\s+(\S+)/', $authorization, $matches)) {
            Response::error('Token tidak ditemukan', 401);
        }

        $token = $matches[1];
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            Response::error('Format token tidak valid', 401);
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
            Response::error('Token tidak valid', 401);
        }

        $decodedPayload = json_decode(
            self::base64UrlDecode($payload),
            true
        );

        if (!$decodedPayload || !isset($decodedPayload['exp'])) {
            Response::error('Payload token tidak valid', 401);
        }

        if ($decodedPayload['exp'] < time()) {
            Response::error('Token sudah kedaluwarsa', 401);
        }

        return $decodedPayload;
    }

    private static function base64UrlEncode($data)
    {
        return rtrim(
            strtr(base64_encode($data), '+/', '-_'),
            '='
        );
    }

    private static function base64UrlDecode($data)
    {
        return base64_decode(
            strtr($data, '-_', '+/')
        );
    }
}
