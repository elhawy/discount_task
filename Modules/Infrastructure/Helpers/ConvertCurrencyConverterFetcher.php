<?php

namespace Modules\Infrastructure\Helpers;

use Modules\Infrastructure\Utilities\HttpClient;

class ConvertCurrencyConverterFetcher
{
    protected $from = '';
    protected $to = '';
    protected $amount = 0;
    private $url = '';

    public function __construct(float $amount, string $to, string $from = 'USD')
    {
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
        $this->buildURL();
    }

    protected function buildURL()
    {
        $this->url = "https://" . strtolower($this->from) . ".mconvert.net/" . strtolower($this->to) . "/" . $this->amount;
    }
    public function convert(): array
    {
        $httpClient = app(HttpClient::class);
        try {
            $response = $httpClient->send($this->url, 'GET');
            $statusCode = $response->getStatusCode();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return [];
        } catch (\Exception $e) {
            return [];
        }
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        if (empty($content)) {
            return [];
        }
        return $this->extractConverted($content);
    }

    protected function extractConverted(string $content)
    {
        $currencyToNameReg = '/<div class="to"><span class="green">*.(?<converted_qty>\d+,?\d+)<\/span> (?<converted_name>\w+ \w+)<\/div>/m';

        $status = preg_match($currencyToNameReg, $content, $matches);
        if ($status) {
            $currencyConverted = $matches['converted_name'];
            $convertedQty = $matches['converted_qty'];
        } else {
            $currencyConverted = "";
            $convertedQty = 0;

        }

        $currencyFromNameReg = '/<div class="from">.*>(?<from_qty>\d+,?\d*)<\/span> (?<from_name>\w+\s?\w*) =<\/div>/mi';
        preg_match($currencyFromNameReg, $content, $matches);
        $currencyFrom = $matches['from_name'];

        $symbolRegex = '/<div class="bysymbol">(?<from_symbol>.*;)(?<from_qty>\d+,?\d*) = <span>(?<to_symbol>.*;)(?<to_qty>\d+,?\d+)<\/span><\/div>/m';
        $status = preg_match($symbolRegex, $content, $matches);
        $fromSymbol = html_entity_decode($matches["from_symbol"]);
        $fromQty = $matches["from_qty"];
        $convertedSymbol = html_entity_decode($matches["to_symbol"]);
        $convertedQty = $matches["to_qty"];

        return [
            "converted" => [
                "value" => $convertedQty,
                "symbol" => $convertedSymbol,
                "name" => $currencyConverted,
            ],
            'from' => [
                "value" => $fromQty,
                "symbol" => $fromSymbol,
                "name" => $currencyFrom,
            ],
        ];
    }
}
