<?php

namespace App\Services\Api;

use App\Interfaces\ApiTaskServiceInterface;
use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;

class ApiTaskService implements ApiTaskServiceInterface
{

    const API_ENDPOINTS = [
        'index' => [
            //api.tasks.index
            'url' => '/project/{projectId}/tasks',
            'method' => 'GET',
        ],
        'show' => [
            //api.task.show
            'url' => '/task/{id}',
            'method' => 'GET',
        ],
        'store' => [
            //api.task.store
            'url' => '/project/{projectId}/task',
            'method' => 'POST',
        ],
        'update' => [
            //api.task.update
            'url' => '/task/{id}',
            'method' => 'PUT',
        ],
        'destroy' => [
            //api.task.destroy
            'url' => '/task/{id}',
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
     * Get all tasks.
     *
     * @param int $projectId
     *
     * @return array
     */
    public function index(int $projectId): array
    {
        return $this->request(
            self::API_ENDPOINTS['index']['method'],
            str_replace('{projectId}', $projectId, self::API_ENDPOINTS['index']['url'])
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
     *
     * @throws Exception
     */
    public function create(array $data): array
    {
        $project_id = $data['project_id'];
        unset($data['project_id']);
        $result = $this->request(
            self::API_ENDPOINTS['store']['method'],
            str_replace('{projectId}', $project_id, self::API_ENDPOINTS['store']['url']),
            $data
        );

        if (isset($result['error'])) {
            throw new Exception($result['error']);
        }
        return $result;
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
