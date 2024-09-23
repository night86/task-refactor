## USAGE 
```
composer install 

$currencyProvider = new ExchangeRatesApiProvider();
$binProvider = new BinListProvider();
$commissionCalculator = new CommissionCalculator($currencyProvider, $binProvider);
$commissionCalculator->calculate($argv[1]);
```