<?php

namespace App\Services;

use App\Interfaces\ApiProjectServiceInterface;
use Illuminate\Support\Collection;

class ProjectService
{
    protected ApiProjectServiceInterface $apiProjectService;

    public function __construct(ApiProjectServiceInterface $apiProjectService)
    {
        $this->apiProjectService = $apiProjectService;
    }

    /**
     * Get all projects.
     *
     * @return Collection
     */
    public function getAllProjects(): Collection
    {
        $projectsData = $this->apiProjectService->index()['data'];
        $projects = collect($projectsData)->map(function ($item) {
            return (object)$item;
        });
        return $projects;
    }

    /**
     * Get a specific project by ID.
     *
     * @param int $id
     *
     * @return object|null
     */
    public function getProjectById(int $id): ?object
    {
        $projectData = $this->apiProjectService->get($id)['data'] ?? null;
        return $projectData ? (object)$projectData : null;
    }

    /**
     * Validate and create a new project.
     *
     * @param array $data
     *
     * @return array
     */
    public function createProject(array $data): array
    {
        return $this->apiProjectService->create($data);
    }

    /**
     * Validate and update a project.
     *
     * @param int $id
     * @param array $data
     *
     * @return array
     */
    public function updateProject(int $id, array $data): array
    {
        return $this->apiProjectService->update($id, $data);
    }

    /**
     * Delete a project.
     *
     * @param int $id
     *
     * @return array
     */
    public function deleteProject(int $id): array
    {
        return $this->apiProjectService->delete($id);
    }
}
