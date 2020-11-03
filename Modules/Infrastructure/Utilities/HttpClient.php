<?php

namespace Modules\Infrastructure\Utilities;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class HttpClient
{

    public function send(string $url, $method = 'POST', array $payload = [], $headers = []): Response
    {
        return app(Client::class)->request($method, $url, ["headers" => $headers, "json" => $payload]);
    }
}
