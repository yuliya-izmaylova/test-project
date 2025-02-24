<?php

namespace Tests\Unit;

use App\Console\Commands\Services\CodeServices\CountryCodeBinService;
use App\Console\Commands\Services\FeeCalculationService;
use App\Console\Commands\Services\RateServices\RateService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class CalculateFeeTest extends TestCase
{
    private MockObject $countryCodeService;
    private MockObject $rateService;

    protected function setUp(): void
    {
        $this->countryCodeService = $this->createMock(CountryCodeBinService::class);
        $this->rateService = $this->createMock(RateService::class);
    }
    /**
     * @dataProvider feeProvider
     */
    public function testCalculateFee(float $amount, string $bin, bool $isEu, float $expectedFee): void
    {
        $service =  new FeeCalculationService($this->countryCodeService, $this->rateService);
        $this->countryCodeService->method('isEuCountry')->with($bin)->willReturn($isEu);

        $fee = $service->calculateFee($amount, $bin);

        $this->assertEquals($expectedFee, $fee);
    }

    public static function  feeProvider(): array
    {
        return [
            [100.00, '45717360', true, 1.00],
            [100.00, '40000000', false, 2.00],
            [200.00, '45717360', true, 2.00],
            [200.00, '40000000', false, 4.00],
        ];
    }

    /**
     * @dataProvider conversionProvider
     */
    public function testConvertToEuro(float $amount, string $currency, float $exchangeRate, float $expectedResult): void
    {
        $this->rateService->method('getExchangeRate')->with($currency)->willReturn($exchangeRate);

        $service =  new FeeCalculationService($this->countryCodeService, $this->rateService);
        $convertedAmount = $service->convertToEuro($amount, $currency);

        $this->assertEquals(round($expectedResult, 2), round($convertedAmount, 2));
    }

    public static function conversionProvider(): array
    {
        return [
            [100.00, 'EUR', 1.00, 100.00],
            [100.00, 'USD', 1.10, 90.91],
            [100.00, 'GBP', 0.85, 117.65],
            [100.00, 'JPY', 150.00, 0.67],
        ];
    }

    /**
     * @dataProvider parseRowProvider
     */
    public function testParseRow(string $dataString, ?array $expectedResult): void
    {
        $service =  new FeeCalculationService($this->countryCodeService, $this->rateService);
        $parsedResult = $service->parseRow($dataString);

        $this->assertEquals($expectedResult, $parsedResult);
    }

    public static function parseRowProvider(): array
    {
        return [
            ['{"bin":"45717360","amount":"100.00","currency":"EUR"}', ['45717360', '100.00', 'EUR']],
            ['{"bin":"40000000","amount":"50.00","currency":"USD"}', ['40000000', '50.00', 'USD']],
            ['{"bin":"50000000","amount":"75.50","currency":"GBP"}', ['50000000', '75.50', 'GBP']],
            ['invalid json string', null],
            ['{"bin":"45717360","amount":"100.00"}', null],
        ];
    }
}
