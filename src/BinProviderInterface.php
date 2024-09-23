<?php
namespace CommissionCalculator;

interface BinProviderInterface
{
    /**
     * @param $bin
     * @return mixed
     */
    public function getBinInfo($bin);
}