<?php

namespace App\Services\Api;

use App\Interfaces\ApiProjectServiceInterface;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;

class ApiProjectService implements ApiProjectServiceInterface
{

    const API_ENDPOINTS = [
        'index' => [
            //api.projects.index
            'url' => '/projects',
            'method' => 'GET',
        ],
        'show' => [
            //api.project.show
            'url' => '/project/{id}',
            'method' => 'GET',
        ],
        'store' => [
            //api.project.store
            'url' => '/project',
            'method' => 'POST',
        ],
        'update' => [
            //api.project.update
            'url' => '/project/{id}',
            'method' => 'PUT',
        ],
        'destroy' => [
            //api.project.destroy
            'url' => '/project/{id}',
            'method' => 'DELETE',
        ],
    ];
    protected GuzzleClient $client;
    protected mixed $token = null;
    protected mixed $apiBaseUrl;
    protected mixed $username;
    protected mixed $password;

    public function __construct()
    {
        $this->apiBaseUrl = env('API_URL');
        $this->username = env('API_USERNAME');
        $this->password = env('API_PASSWORD');

        // Guzzle client configuration
        $this->client = new GuzzleClient([
            'base_uri' => $this->apiBaseUrl,
            'timeout' => 5.0,
        ]);
    }

    /**
     * Authenticate with the API and store the token.
     *
     * @return void
     */
    private function authenticate(): void
    {
        try {
            $response = $this->client->post($this->apiBaseUrl . '/auth/login', [
                'form_params' => [
                    'email' => $this->username,
                    'password' => $this->password,
                ],
            ]);
            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['data']['access_token'])) {
                $this->token = $data['data']['access_token'];
                return;
            }

            return;
        } catch (GuzzleException $e) {
            $e->getMessage();
            return;
        }
    }

    /**
     * Get all projects.
     *
     * @return array
     */
    public function index(): array
    {
        return $this->request(
            self::API_ENDPOINTS['index']['method'],
            self::API_ENDPOINTS['index']['url']
        );
    }

    /**
     * Get a single resource by ID.
     *
     * @param int $id
     *
     * @return array
     */
    public function get(int $id): array
    {
        return $this->request(
            self::API_ENDPOINTS['show']['method'],
            str_replace('{id}', $id, self::API_ENDPOINTS['show']['url'])
        );
    }

    /**
     * Create a new resource.
     *
     * @param array $data
     *
     * @return array
     */
    public function create(array $data): array
    {
        return $this->request(
            self::API_ENDPOINTS['store']['method'],
            self::API_ENDPOINTS['store']['url'],
            $data
        );
    }

    /**
     * Update an existing resource by ID.
     *
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function update(int $id, array $data): array
    {
        return $this->request(
            self::API_ENDPOINTS['update']['method'],
            str_replace('{id}', $id, self::API_ENDPOINTS['update']['url']),
            $data
        );
    }

    /**
     * Delete a resource by ID.
     *
     * @param int $id
     *
     * @return array
     */
    public function delete(int $id): array
    {
        return $this->request(
            self::API_ENDPOINTS['destroy']['method'],
            str_replace('{id}', $id, self::API_ENDPOINTS['destroy']['url'])
        );
    }

    /**
     * Perform the actual API request.
     *
     * @param string $method
     * @param string $url
     * @param array $data
     *
     * @return array
     */
    protected function request(
        string $method,
        string $url,
        array  $data = []
    ): array
    {
        $url = $this->apiBaseUrl . $url;
        $this->authenticate();
        try {
            $options = [
                'headers' => $this->getHeaders(),
            ];

            if (!empty($data)) {
                if ($method === 'POST' || $method === 'PUT') {
                    $options['json'] = $data;
                }
            }
            $response = $this->client->request($method, $url, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            if ($e instanceof ServerException) {
                $response = $e->getResponse();
                $errorBody = $response ? $response->getBody()->getContents() : $e->getMessage();
                return ['error' => $errorBody];
            }
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get headers for API calls.
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
    }
}
