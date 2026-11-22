<?php

class Response
{
    public static function success($message, $data = null, $statusCode = 200)
    {
        http_response_code($statusCode);

        echo json_encode([
            "success" => true,
            "message" => $message,
            "data" => $data
        ], JSON_PRETTY_PRINT);

        exit;
    }

    public static function error($message, $statusCode = 400)
    {
        http_response_code($statusCode);

        echo json_encode([
            "success" => false,
            "message" => $message
        ], JSON_PRETTY_PRINT);

        exit;
    }
}
