<?php

namespace App\Clients;

use Throwable;
use Illuminate\Support\Facades\Http;
use App\Exceptions\NotifyClientException;
use Illuminate\Http\Client\Response;

class NotifyClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://util.devi.tools/api/v1';
    }

    /**
     * @return void
     * 
     * @throws NotifyClientException
     */
    public function notify(): void
    {
        $url = $this->baseUrl . '/notify';

        $response = $this->makeRequest('GET', $url);

        if ($response->status() == 504) {
            throw NotifyClientException::timeOut();
        }
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
