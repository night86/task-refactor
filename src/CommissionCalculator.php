<?php

namespace CommissionCalculator;

/**
 * -- USAGE EXAMPLE --
 * $currencyProvider = new ExchangeRatesApiProvider();
 * $binProvider = new BinListProvider();
 * $commissionCalculator = new CommissionCalculator($currencyProvider, $binProvider);
 * $commissionCalculator->calculate($argv[1]);
 */
class CommissionCalculator
{
    private CurrencyProviderInterface $currencyProvider;
    private BinProviderInterface $binProvider;
    private $commissionCeiling;

    /**
     * @param CurrencyProviderInterface $currencyProvider
     * @param BinProviderInterface $binProvider
     * @param $commissionCeiling
     */
    public function __construct(CurrencyProviderInterface $currencyProvider, BinProviderInterface $binProvider, $commissionCeiling = 0.01)
    {
        $this->currencyProvider = $currencyProvider;
        $this->binProvider = $binProvider;
        $this->commissionCeiling = $commissionCeiling;
    }

    /**
     * @param $file
     * @return void
     */
    public function calculate($file)
    {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $data = json_decode($line, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Invalid JSON: " . json_last_error_msg());
            }

            $binInfo = $this->binProvider->getBinInfo($data['bin']);
            $isEu = $this->isEu($binInfo['country']['alpha2']);

            $rate = $this->currencyProvider->getExchangeRate($data['currency']);
            $amountFixed = $this->convertAmount($data['amount'], $data['currency'], $rate);

            $commission = $amountFixed * ($isEu ? 0.01 : 0.02);
            $commission = $this->applyCommissionCeiling($commission);

            echo number_format($commission, 2, '.', '') . "\n";
        }
    }

    /**
     * @param $countryCode
     * @return bool
     */
    private function isEu($countryCode): bool
    {
        $euCountries = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'];
        return in_array($countryCode, $euCountries);
    }

    /**
     * @param $amount
     * @param $currency
     * @param $rate
     * @return float|int|mixed
     */
    private function convertAmount($amount, $currency, $rate)
    {
        return $currency === 'EUR' || $rate == 0 ? $amount : $amount / $rate;
    }

    /**
     * @param $commission
     * @return float|int
     */
    private function applyCommissionCeiling($commission)
    {
        return ceil($commission * 100) / 100;
    }
}