
<?php

class Response
{
    public static function json($status, $success, $message, $data = null)
    {
        http_response_code($status);
        header("Content-Type: application/json; charset=UTF-8");

        echo json_encode([
            "success" => $success,
            "message" => $message,
            "data" => $data
        ], JSON_PRETTY_PRINT);

        exit;
    }

    public static function success($message, $data = null)
    {
        self::json(200, true, $message, $data);
    }

    public static function error($status, $message)
    {
        self::json($status, false, $message);
    }
}