<?php

class Response
{
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $data,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    public static function success($message, $data = null, $statusCode = 200)
    {
        self::json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message, $statusCode = 400)
    {
        self::json([
            'status' => false,
            'message' => $message
        ], $statusCode);
    }
}
