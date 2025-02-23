<?php

namespace Tests\Unit;

use App\Console\Commands\Services\CodeServices\CountryCodeBinService;
use Tests\TestCase;

class CountryCodeBinServiceTest extends TestCase
{
    private array $binProvider;

    protected function setUp(): void
    {
        $this->binProvider = [
            ['45717360', 'DK'],
            ['516793', 'US'],
            ['45417360', 'JPY'],
            ['12345678', 'FR'],
        ];
    }


    /**
     * @dataProvider isEuCountryProvider
     */
    public function testIsEuCountry(string $bin, string $mockedCountry, bool $expectedResult): void
    {
        $mockService = $this->getMockBuilder(CountryCodeBinService::class)
            ->onlyMethods(['getCountryCode'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockService->method('getCountryCode')
            ->willReturnMap($this->binProvider);
        $isEu = $mockService->isEuCountry($bin);
        $this->assertEquals($expectedResult, $isEu);
    }

    public static function isEuCountryProvider(): array
    {
        return [
            ['516793', 'US', false],
            ['45717360', 'DK', true],
            ['45417360', 'JPY', false],
            ['12345678', 'FR', true],
        ];
    }
}
