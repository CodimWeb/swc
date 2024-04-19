<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result): JsonResponse
    {
        $response = [
            'error' => null,
            'result' => $result,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, int $code = 200): JsonResponse
    {
        $response = [
            'error' => false,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }
}
