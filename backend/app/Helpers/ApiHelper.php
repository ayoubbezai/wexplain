<?php

namespace App\Helpers;

class ApiHelper
{
    public static function success(string $messageKey, $data = [], int $status = 200)
    {
        return response()->json([
            'success' => true,
            'message_key' => $messageKey,
            'data' => $data,
        ], $status);
    }

    public static function apiError(string $messageKey, $errors = [], int $status = 400)
    {
        return response()->json([
            'success' => false,
            'message_key' => $messageKey,
            'errors' => $errors,
        ], $status);
    }
}
