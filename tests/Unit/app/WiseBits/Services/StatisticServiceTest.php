<?php

namespace Tests\Unit\app\WiseBits\Services;

use App\WiseBits\Services\Statistics\DTO\UpdateDTO;
use App\WiseBits\Services\Statistics\Exceptions\StatisticException;
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

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->redisClient = $this
            ->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->addMethods(['hgetall']) // virtual method
            ->onlyMethods(['transaction'])
            ->getMock();
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->validator->method('validate')->willReturn([]);

        $this->statisticService = new StatisticService($this->redisClient, $this->validator, $this->logger);
    }

    /**
     * @throws StatisticException
     */
    public function testUpdate(): void
    {
        $this->validator->expects(self::once())->method('validate');
        $this->redisClient->expects(self::once())->method('transaction');

        $updateDTO = new UpdateDTO('ru');
        $this->statisticService->update($updateDTO);
    }

    /**
     * @dataProvider statisticsDataProvider
     *
     * @param array $statisticsData
     *
     * @throws StatisticException
     */
    public function testGet(array $statisticsData): void
    {
        $this->redisClient->method('hgetall')->willReturn($statisticsData);
        $this->redisClient->expects(self::once())->method('hgetall');

        $response = $this->statisticService->get();
        self::assertEquals($statisticsData, $response);
    }

    /**
     * @return array
     */
    public function statisticsDataProvider(): array
    {
        return
            [
                [
                    [],
                ],
            [
                [
                    [
                        'ru' => '5',
                        'fr' => '10',
                    ]
                ]
            ]
        ];
    }
}
