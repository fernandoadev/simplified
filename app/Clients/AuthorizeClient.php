<?php

namespace App\Clients;

use Throwable;
use Illuminate\Support\Facades\Http;
use App\Exceptions\AuthorizeClientException;

class AuthorizeClient
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://util.devi.tools/api/v2';
    }

    /**
     * @return bool
     *
     * @throws AuthorizeClientException
     */
    public function getAuthorize(): bool
    {
        $url = $this->baseUrl . '/authorize';

        $response = $this->makeRequest('GET', $url);

        if (empty($response['data']['authorization'])) {
            throw AuthorizeClientException::notAuthorize();
        }

        return true;
    }

    /**
     * @param string $method (GET, POST, PUT, DELETE).
     * @param string $url
     * @param array $data The data to be sent in the request body (optional).
     * @param array $headers Additional headers for the request (optional).
     *
     * @return array
     *
     * @throws AuthorizeClientException
     */
    private function makeRequest(string $method, string $url, array $data = [], array $headers = []): array
    {
        try {
            $response = Http::withHeaders($headers)->{$method}($url, $data);
            return $response->json();
        } catch (Throwable $e) {
            throw AuthorizeClientException::fromRequest($e);
        }
    }
}
