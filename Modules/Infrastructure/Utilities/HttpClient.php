<?php

namespace Modules\Infrastructure\Utilities;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class HttpClient
{

    public function send(string $url, array $payload, $method = 'POST', $headers = null): Response
    {
        return app(Client::class)->request($method, $url, ["headers" => $headers, "json" => $payload]);
    }
}
