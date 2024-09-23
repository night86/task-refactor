<?php

use CommissionCalculator\BinProviderInterface;
use CommissionCalculator\CommissionCalculator;
use CommissionCalculator\CurrencyProviderInterface;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorTest extends TestCase
{
    public function testCommissionCalculation()
    {
        $currencyProviderMock = $this->createMock(CurrencyProviderInterface::class);
        $currencyProviderMock->method('getExchangeRate')->willReturn(1.2);

        $binProviderMock = $this->createMock(BinProviderInterface::class);
        $binProviderMock->method('getBinInfo')->willReturn(['country' => ['alpha2' => 'DE']]);

        $calculator = new CommissionCalculator($currencyProviderMock, $binProviderMock);

        $this->expectOutputString("0.01\n");
        $calculator->calculate(__DIR__ . '/test_data.txt');
    }

    public function testNonEuCommission()
    {
        $currencyProviderMock = $this->createMock(CurrencyProviderInterface::class);
        $currencyProviderMock->method('getExchangeRate')->willReturn(1.2);

        $binProviderMock = $this->createMock(BinProviderInterface::class);
        $binProviderMock->method('getBinInfo')->willReturn(['country' => ['alpha2' => 'US']]);

        $calculator = new CommissionCalculator($currencyProviderMock, $binProviderMock);

        $this->expectOutputString("0.02\n");
        $calculator->calculate(__DIR__ . '/test_data.txt');
    }
}
