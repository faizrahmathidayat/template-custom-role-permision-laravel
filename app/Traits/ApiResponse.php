<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($message = 'Success', $data = [])
    {
        return response()->json(
                array(
                    'code' => 200,
                    'success' => true,
                    'message' => $message,
                    'data' => $data
                ), 200
            );
    }

    public function errorResponse($message = 'Error', $code = 500, $errors = [])
    {
        return response()->json(
            array(
                'code' => $code,
                'success' => false,
                'message' => $message,
                'errors' => $errors
            ), $code
        );
    }
}
