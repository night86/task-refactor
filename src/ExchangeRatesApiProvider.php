<?php

namespace CommissionCalculator;

use CommissionCalculator\CurrencyProviderInterface;


class ExchangeRatesApiProvider implements CurrencyProviderInterface
{

    private string $url;
    private $authToken;

    /**
     * @param string $url
     * @param $authToken
     */
    public function __construct(string $url = 'https://api.exchangeratesapi.io/latest', $authToken = null)
    {
        $this->url = $url;
        $this->authToken = $authToken;
    }

    /**
     * @param $currency
     * @return int|mixed
     */
    public function getExchangeRate($currency)
    {
        $headers = [];
        if ($this->authToken) {
            $headers[] = "Authorization: Bearer {$this->authToken}";
        }

        $context = stream_context_create([
            'http' => [
                'header' => $headers
            ]
        ]);

        $data = json_decode(file_get_contents($this->url, false, $context), true);

        return $data['rates'][$currency] ?? 0;
    }
}