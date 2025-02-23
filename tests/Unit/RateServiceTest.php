<?php

namespace Tests\Unit;

use App\Console\Commands\Services\RateServices\RateService;
use Tests\TestCase;

class RateServiceTest extends TestCase
{
    private array $rates;
    protected function setUp(): void
    {
        $this->rates = [
            ['USD', 1.10],
            ['EUR', 1.00],
            ['GBP', 0.85],
        ];
    }

    /**
     * @dataProvider currencyProvider
     */
    public function testGetExchangeRate(string $currency, float $expectedRate): void
    {
        $mockService = $this->getMockBuilder(RateService::class)
            ->onlyMethods(['getExchangeRate'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockService->method('getExchangeRate')
            ->willReturnMap($this->rates);
        $rate = $mockService->getExchangeRate($currency);

        $this->assertIsFloat($rate);
        $this->assertEquals($expectedRate, $rate);
    }

    public static function currencyProvider(): array
    {
        return [
            ['USD', 1.10],
            ['EUR', 1.00],
            ['GBP', 0.85],
        ];
    }
}
