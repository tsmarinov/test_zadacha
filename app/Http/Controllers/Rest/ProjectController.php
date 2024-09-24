<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rest\ProjectStoreRequest;
use App\Http\Requests\Rest\ProjectUpdateRequest;
use App\Models\Project;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    /**
     * Generate a consistent JSON response.
     *
     * @param int $code
     * @param mixed|null $data
     * @param array $validationErrors
     * @param string|null $error
     *
     * @return JsonResponse
     */
    protected function jsonResponse(
        int    $code,
        mixed  $data = null,
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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $projects = Project::orderBy('id', 'DESC')->get();
            return $this->jsonResponse(0, $projects);
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }

    /**
     * Store a newly created project in storage.
     *
     * @param ProjectStoreRequest $request
     *
     * @return JsonResponse
     */
    public function store(ProjectStoreRequest $request): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $project = Project::create($validatedData);
            return $this->jsonResponse(0, $project);
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
            $project = Project::findOrFail($id);
            return $this->jsonResponse(0, $project);
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }

    /**
     * Update the specified project in storage.
     *
     * @param ProjectUpdateRequest $request
     * @param int $id
     *
     * @return JsonResponse
     */
    public function update(ProjectUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $validatedData = $request->validated();
            $project = Project::findOrFail($id);
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
     *
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            return $this->jsonResponse(0, null, [], "Project: $id deleted successfully.");
        } catch (Exception $e) {
            return $this->jsonResponse(-1, null, [], $e->getMessage());
        }
    }
}
