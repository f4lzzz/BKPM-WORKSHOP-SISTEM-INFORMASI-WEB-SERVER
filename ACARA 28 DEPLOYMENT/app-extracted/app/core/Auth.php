
<?php

class Auth
{
    private static $secretKey = "ACARA25_SECRET_KEY_FAUL_2026";

    public static function generateToken($user)
    {
        $header = [
            "typ" => "JWT",
            "alg" => "HS256"
        ];

        $payload = [
            "id" => $user["id"],
            "username" => $user["username"],
            "role" => $user["role"],
            "iat" => time(),
            "exp" => time() + 3600
        ];

        $base64Header = self::base64UrlEncode(json_encode($header));
        $base64Payload = self::base64UrlEncode(json_encode($payload));

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

    public static function requireAuth()
    {
        $headers = function_exists("getallheaders") ? getallheaders() : [];

        $authorization = "";

        foreach ($headers as $name => $value) {
            if (strtolower($name) === "authorization") {
                $authorization = $value;
                break;
            }
        }

        if (empty($authorization)) {
            $authorization = $_SERVER["HTTP_AUTHORIZATION"]
                ?? $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]
                ?? "";
        }

        if (!preg_match('/^Bearer\s+(\S+)$/i', trim($authorization), $matches)) {
            Response::error(401, "Token tidak ditemukan");
        }

        $token = $matches[1];
        $parts = explode(".", $token);

        if (count($parts) !== 3) {
            Response::error(401, "Format token tidak valid");
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
            Response::error(401, "Token tidak valid");
        }

        $decodedPayload = json_decode(
            base64_decode(strtr($payload, "-_", "+/")),
            true
        );

        if (!$decodedPayload || !isset($decodedPayload["exp"])) {
            Response::error(401, "Payload token tidak valid");
        }

        if ($decodedPayload["exp"] < time()) {
            Response::error(401, "Token sudah kedaluwarsa");
        }

        return $decodedPayload;
    }

    public static function requireAdmin(): array
    {
        $payload = self::requireAuth();

        if (($payload["role"] ?? "") !== "admin") {
            Response::error(403, "Akses ditolak, hanya admin yang diizinkan");
        }

        return $payload;
    }
}
