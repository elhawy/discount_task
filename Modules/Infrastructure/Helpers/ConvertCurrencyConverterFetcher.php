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
        if (strtolower($this->from) == strtolower($this->to)) {
            return [];
        }
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
        $currencyToNameReg = '/<div class="to"><span class="green">(?<converted_qty>[0-9\s?]*,?\d{1,})<\/span> (?<converted_name>\w+ \w+)<\/div>/m';

        $status = preg_match($currencyToNameReg, $content, $matches);
        if ($status) {
            $currencyConverted = $matches['converted_name']??'';
            $convertedQty = $this->formatNumber($matches['converted_qty'] ?? 0);
        } else {
            $currencyConverted = "";
            $convertedQty = 0;

        }
        $currencyFromNameReg = '/<div class="from">.*>(?<from_qty>[0-9\s?]*,?\d{1,})<\/span> (?<from_name>\w+\s?\w*) =<\/div>/m';
        preg_match($currencyFromNameReg, $content, $matches);
        $currencyFrom = $matches['from_name']??'';
        $fromQty = $this->formatNumber($matches['from_qty'] ?? 0);

        $symbolRegex = '/<div class="bysymbol">(?<from_symbol>.*;)(?<from_qty>\d+,?\d*) = <span>(?<converted_symbol>.*;)(?<converted_qty>\d+,?\d+)<\/span><\/div>/m';
        $status = preg_match($symbolRegex, $content, $matches);
        $fromSymbol = html_entity_decode($matches["from_symbol"]??'');
        $convertedSymbol = html_entity_decode($matches["converted_symbol"]??'');
        
        $fromQty = empty($fromQty) ? $this->formatNumber($matches["from_qty"] ?? 0) :  $fromQty;
        
        if ($fromQty != $this->amount) {
            return [];
        }
        $convertedQty = (empty($convertedQty)) ? $this->formatNumber($matches["converted_qty"]) : $convertedQty;
        $ratio = $convertedQty/$fromQty;
        return dd([
            "ratio" => $ratio,
            "from" => [
                "value" => $fromQty,
                "symbol" => $fromSymbol,
                "name" => $currencyFrom,
            ],
            'converted' => [
                "value" => $convertedQty,
                "symbol" => $convertedSymbol,
                "name" => $currencyConverted,
            ],
        ]);
    }

    private function formatNumber($number)
    {
        return floatval(str_replace(',', '.', str_replace('.', '', str_replace(' ', '', $number))));
    }
}
