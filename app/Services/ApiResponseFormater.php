<?php

namespace App\Services;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseFormater
{
    public static function format($data, string $message, bool $success): array
    {
        if ($data instanceof JsonResource) {
            return self::jsonFormat($data, $message, $success);
        }

        return self::arrayFormat($data, $message, $success);
    }

    protected static function arrayFormat($data, $message, $success): array
    {
        $formattedData = [
            'success' => $success,
            'message' => $message,
        ];

        if (!is_array($data))
            $data = $data->toArray();

        // for simple data
        if (!array_key_exists('data', $data)) {
            $data = ['data' => $data];
        }

        return array_merge($formattedData, $data);
    }

    protected static function jsonFormat($data, $message, $success): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];
    }
}
