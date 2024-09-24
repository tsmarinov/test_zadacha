<?php

namespace App\Services;

use App\Interfaces\ApiTaskServiceInterface;
use Exception;
use Illuminate\Support\Collection;

class TaskService
{
    protected ApiTaskServiceInterface $apiTaskService;

    public function __construct(ApiTaskServiceInterface $apiTaskService)
    {
        $this->apiTaskService = $apiTaskService;
    }

    /**
     * Get all tasks.
     *
     * @param int $projectId
     * @return Collection
     */
    public function getAllTasksByProjectId(int $projectId): Collection
    {
        $tasksData = $this->apiTaskService->index($projectId)['data'];
        $tasks = collect($tasksData)->map(function ($item) {
            return (object)$item;
        });
        return $tasks;
    }

    /**
     * Get a specific task by ID.
     *
     * @param int $id
     *
     * @return object
     */
    public function getTaskById(int $id): object
    {
        $taskData = $this->apiTaskService->get($id)['data'];
        return (object)$taskData;
    }

    /**
     * Validate and create a new task.
     *
     * @param array $data
     *
     * @return array
     *
     * @throws Exception
     */
    public function createTask(array $data): array
    {
        try {
            $data['project_id'] = (int) $data['project_id'];
            $result = $this->apiTaskService->create($data);
            return $result;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Validate and update a task.
     *
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function updateTask(int $id, array $data): array
    {
        return $this->apiTaskService->update($id, $data);
    }

    /**
     * Delete a task.
     *
     * @param int $id
     *
     * @return array
     */
    public function deleteTask(int $id): array
    {
        return $this->apiTaskService->delete($id);
    }
}
