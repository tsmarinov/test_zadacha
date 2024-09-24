<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rest\TaskStoreRequest;
use App\Http\Requests\Rest\TaskUpdateRequest;
use App\Models\Project;
use App\Models\Task;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * Generate a consistent JSON response.
     *
     * @param int $code
     * @param null $data
     * @param array $validationErrors
     * @param string|null $error
     *
     * @return JsonResponse
     */
    protected function jsonResponse(
        int    $code,
               $data = null,
        array  $validationErrors = [],
        string $error = null
    ): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'data' => $data,
            'validation_errors' => $validationErrors,
            'error' => $error
        ]);
    }

    /**
     * Display a listing of the projects.
     *
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function index(int $projectId): JsonResponse
    {
        try {
            // Check if the project exists
            Project::findOrFail($projectId);
            // Get tasks for the project
            $tasks = Task::where('project_id', $projectId)->get();
            return $this->jsonResponse(0, $tasks);
        } catch (ModelNotFoundException) {
            return $this->jsonResponse(-1, null, [], 'Project not found.');
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }

    /**
     * Store a newly created project in storage.
     *
     * @param int $projectId
     * @param TaskStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(int $projectId, TaskStoreRequest $request): JsonResponse
    {
        try {
            // Check if the project exists
            Project::findOrFail($projectId);

            $validatedData = $request->validated();
            $validatedData['project_id'] = $projectId;
            $task = Task::create($validatedData);
            return $this->jsonResponse(0, $task);
        } catch (ModelNotFoundException) {
            return $this->jsonResponse(-1, null, [], 'Project not found.');
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }

    /**
     * Display the specified project.
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $project = Task::findOrFail($id);
            return $this->jsonResponse(0, $project);
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }

    /**
     * Update the specified project in storage.
     *
     * @param TaskUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(TaskUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $project = Task::findOrFail($id);
            $project->update($validatedData);
            return $this->jsonResponse(0, $project);
        } catch (ValidationException $e) {
            return $this->jsonResponse(-1, null, $e->errors());
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }

    /**
     * Remove the specified project from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $project = Task::findOrFail($id);
            $project->delete();
            return $this->jsonResponse(0, null, [], "Task: $id deleted successfully.");
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }
}
