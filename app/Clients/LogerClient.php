<?php

namespace App\Clients;

use Throwable;
use Illuminate\Support\Facades\Http;
use App\Exceptions\NotifyClientException;
use Illuminate\Http\Client\Response;

class LogerClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://eo6fp4lbe938s73.m.pipedream.net';
    }

    /** @return void */
    public function log(string $log): void
    {
        $url = $this->baseUrl;

        $this->makeRequest('POST', $url, ['log' => $log], ['Content-Type' => 'application/json']);
    }

    /**
     * @param string $method (GET, POST, PUT, DELETE).
     * @param string $url
     * @param array $data The data to be sent in the request body (optional).
     * @param array $headers Additional headers for the request (optional).
     *
     * @return Response
     *
     * @throws NotifyClientException
     */
    private function makeRequest(string $method, string $url, array $data = [], array $headers = []): Response
    {
        try {
            $response = Http::withHeaders($headers)->{$method}($url, $data);
            return $response;
        } catch (Throwable $e) {
            throw NotifyClientException::fromRequest($e);
        }
    }
}
