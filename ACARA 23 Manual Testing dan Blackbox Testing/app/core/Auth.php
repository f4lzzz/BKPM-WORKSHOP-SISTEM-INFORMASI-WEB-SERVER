<?php

class Auth
{
    private static $secretKey = "ACARA23_SECRET_KEY";

    public static function generateToken($user)
    {
        $header = json_encode([
            "typ" => "JWT",
            "alg" => "HS256"
        ]);

        $payload = json_encode([
            "id" => $user["id"],
            "username" => $user["username"],
            "role" => $user["role"],
            "iat" => time(),
            "exp" => time() + 3600
        ]);

        $base64Header = self::base64UrlEncode($header);
        $base64Payload = self::base64UrlEncode($payload);

        $signature = hash_hmac(
            "sha256",
            $base64Header . "." . $base64Payload,
            self::$secretKey,
            true
        );

        $base64Signature = self::base64UrlEncode($signature);

        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }

    private static function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");
    }

    public static function validateToken()
    {
        $headers = getallheaders();
        $authHeader = $headers["Authorization"] ?? "";

        if (!preg_match("/Bearer\s+(\S+)/", $authHeader, $matches)) {
            Response::error("Token tidak ditemukan", 401);
        }

        $token = $matches[1];
        $parts = explode(".", $token);

        if (count($parts) !== 3) {
            Response::error("Format token tidak valid", 401);
        }

        [$header, $payload, $signature] = $parts;

        $expectedSignature = self::base64UrlEncode(
            hash_hmac(
                "sha256",
                $header . "." . $payload,
                self::$secretKey,
                true
            )
        );

        if (!hash_equals($expectedSignature, $signature)) {
            Response::error("Token tidak valid", 401);
        }

        $decodedPayload = json_decode(
            base64_decode(strtr($payload, "-_", "+/")),
            true
        );

        if (!$decodedPayload || !isset($decodedPayload["exp"])) {
            Response::error("Payload token tidak valid", 401);
        }

        if ($decodedPayload["exp"] < time()) {
            Response::error("Token sudah kedaluwarsa", 401);
        }

        return $decodedPayload;
    }
}
