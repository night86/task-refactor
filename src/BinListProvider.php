<?php

namespace CommissionCalculator;

use CommissionCalculator\BinProviderInterface;

class BinListProvider implements BinProviderInterface
{

    private string $url;
    private $authToken;

    /**
     * @param string $url
     * @param $authToken
     */
    public function __construct(string $url = 'https://lookup.binlist.net/', $authToken = null)
    {
        $this->url = $url;
        $this->authToken = $authToken;
    }

    /**
     * @param $bin
     * @return mixed
     */
    public function getBinInfo($bin)
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

        $result = json_decode(file_get_contents($this->url . $bin, false, $context), true);

        if (!$result) {
            throw new Exception("Error fetching BIN information.");
        }

        return $result;
    }
}