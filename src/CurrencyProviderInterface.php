<?php

namespace CommissionCalculator;

interface CurrencyProviderInterface
{
    /**
     * @param $currency
     * @return mixed
     */
    public function getExchangeRate($currency);
}