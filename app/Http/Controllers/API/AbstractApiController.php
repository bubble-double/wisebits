<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class AbstractApiController extends Controller
{
    /**
     * @param array $response
     * @param int $httpStatusCode
     *
     * @return JsonResponse
     */
    protected function createSuccessfulJsonResponse(array $response = [], int $httpStatusCode = 200): JsonResponse
    {
        return response()->json($response, $httpStatusCode, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @param \Throwable $t
     * @param int $httpStatusCode
     *
     * @return JsonResponse
     */
    protected function createFailedJsonResponse(\Throwable $t, int $httpStatusCode = 400): JsonResponse
    {
        $errorMessage = method_exists($t, 'getUserMessage') ? $t->getUserMessage() : 'Inside error';
        $data = [
            'status' => $httpStatusCode,
            'error' => $errorMessage,
        ];
        return response()->json($data, $httpStatusCode, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
