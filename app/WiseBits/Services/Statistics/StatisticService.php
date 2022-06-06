<?php

declare(strict_types=1);

namespace App\WiseBits\Services\Statistics;

use App\WiseBits\Helpers\ObjectHelper;
use App\WiseBits\Services\Common\Exceptions\ValidatorException;
use App\WiseBits\Services\Statistics\DTO\UpdateDTO;
use App\WiseBits\Services\Statistics\Exceptions\StatisticException;
use Predis\Client;
use Predis\Transaction\MultiExec;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class StatisticService
{
    protected Client $redisClient;
    protected ValidatorInterface $validator;
    protected LoggerInterface $logger;

    public function __construct(Client $redisClient, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->redisClient = $redisClient;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param UpdateDTO $updateDto
     *
     * @return void
     *
     * @throws StatisticException
     */
    public function update(UpdateDTO $updateDto): void
    {
        try {
            $this->validate($updateDto);
            $cityCode = $updateDto->getCityCode();
            $statisticHashKey = $this->getStatisticHashKey();
            $this->updateStatistics($statisticHashKey, $cityCode);
        } catch (\Throwable $t) {
            $userMessage = 'Не удалось обновить статистику';
            $this->handleException($t, __CLASS__, __FUNCTION__, $userMessage);
        }
    }

    /**
     * @return array
     *
     * @throws StatisticException
     */
    public function get(): array
    {
        try {
            $statisticHashKey = $this->getStatisticHashKey();
            // Get all statistic values
            $staticsResults = $this->redisClient->hgetall($statisticHashKey);
            return $staticsResults ?? [];
        } catch (\Throwable $t) {
            $userMessage = 'Не удалось получить статистику';
            $this->handleException($t, __CLASS__, __FUNCTION__, $userMessage);
        }
    }

    /**
     * @param object $object
     *
     * @return void
     *
     * @throws ValidatorException
     */
    protected function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if (0 !== count($violations)) {
            throw new ValidatorException($violations);
        }
    }

    /**
     * @return string
     */
    protected function getStatisticHashKey(): string
    {
        return 'city_codes_statistics';
    }

    /**
     * @param string $statisticHashKey
     * @param string $cityCode
     *
     * @return void
     */
    protected function updateStatistics(string $statisticHashKey, string $cityCode): void
    {
        $this->redisClient->transaction(
            static function(MultiExec $mtx) use ($statisticHashKey, $cityCode) {
                // Set 0 if not exists
                $mtx->hsetnx($statisticHashKey, $cityCode, 0);
                $mtx->hincrby($statisticHashKey, $cityCode, 1);
            }
        );
    }

    /**
     * @param \Throwable $t
     * @param string $class
     * @param string $methodName
     * @param string $userMessage
     *
     * @return void
     *
     * @throws StatisticException
     */
    protected function handleException(
        \Throwable $t,
        string $class,
        string $methodName,
        string $userMessage
    ): void {
        $className = ObjectHelper::getClassNameWithoutNamespace($class);
        $errorMessage = sprintf(
            '%s::%s(). %s. ErrorMessage: %s',
            $className,
            $methodName,
            $userMessage,
            $t->getMessage()
        );

        $this->logger->error($errorMessage);
        throw new StatisticException($errorMessage, $userMessage, $t);
    }
}
