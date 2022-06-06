<?php

namespace Tests\Unit\app\WiseBits\Services;

use App\WiseBits\Services\Statistics\DTO\UpdateDTO;
use App\WiseBits\Services\Statistics\StatisticService;
use PHPUnit\Framework\MockObject\MockObject;
use Predis\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Tests\TestCase;

class StatisticServiceTest extends TestCase
{
    /** @var Client|MockObject */
    protected $redisClient;

    /** @var ValidatorInterface|MockObject */
    protected $validator;

    /** @var LoggerInterface|MockObject */
    protected $logger;

    protected ?StatisticService $statisticService = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->redisClient = $this->createMock(Client::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->statisticService = new StatisticService($this->redisClient, $this->validator, $this->logger);
    }

    public function testUpdate(): void
    {
        $this->validator->method('validate')->willReturn([]);

        $this->validator->expects(self::once())->method('validate');
        $this->redisClient->expects(self::once())->method('transaction');

        $updateDTO = new UpdateDTO('ru');
        $this->statisticService->update($updateDTO);
    }
}
