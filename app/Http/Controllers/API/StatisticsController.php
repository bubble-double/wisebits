<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\WiseBits\Services\Statistics\DTO\UpdateDTO;
use App\WiseBits\Services\Statistics\StatisticService;
use Illuminate\Http\JsonResponse;

class StatisticsController extends AbstractApiController
{
    protected StatisticService $statisticService;

    public function __construct(StatisticService $statisticService)
    {
        $this->statisticService = $statisticService;
    }

    /**
     * @param string $cityCode
     *
     * @return JsonResponse
     */
    public function update(string $cityCode): JsonResponse
    {
        try {
            $updateDto = new UpdateDTO($cityCode);
            $this->statisticService->update($updateDto);
            return $this->createSuccessfulJsonResponse([], 201);
        } catch (\Throwable $t) {
            return $this->createFailedJsonResponse($t);
        }
    }

    /**
     * @return JsonResponse
     */
    public function get(): JsonResponse
    {
        try {
            $response = $this->statisticService->get();
            return $this->createSuccessfulJsonResponse($response);
        } catch (\Throwable $t) {
            return $this->createFailedJsonResponse($t);
        }
    }
}
