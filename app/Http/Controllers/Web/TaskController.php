<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\TaskUpdateRequest;
use App\Services\ProjectService;
use App\Services\TaskService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TaskController extends Controller
{
    protected TaskService $taskService;
    protected ProjectService $projectService;

    /**
     * Inject TaskService, ProjectService through the constructor.
     *
     * @param TaskService $taskService
     * @param ProjectService $projectService
     */
    public function __construct(
        TaskService    $taskService,
        ProjectService $projectService
    )
    {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the projects and their tasks.
     *
     * @param int $projectId
     *
     * @return View
     */
    public function index(int $projectId): View
    {
        $project = $this->projectService->getProjectById($projectId);
        $tasks = $this->taskService->getAllTasksByProjectId($projectId);
        return view('tasks.index', compact('project', 'tasks'));
    }

    /**
     * Display the form for editing the specified project.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $task = $this->taskService->getTaskById($id);
        return view('tasks.edit', compact('task'));
    }


    /**
     * Update the specified project in storage.
     *
     * @param TaskUpdateRequest $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(TaskUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $data = $request->validated();
            $this->taskService->updateTask($id, $data);
            return redirect()->route('tasks.index', ['projectId' => $data['project_id']]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Remove the specified project from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $this->taskService->deleteTask($id);
        return redirect()->back();
    }

    /**
     * Display the form for creating a new project.
     *
     * @param int $projectId
     * @return View
     */
    public function new(int $projectId): View
    {
        $project = $this->projectService->getProjectById($projectId);
        if (!$project) {
            return view('tasks.new', compact('projectId'))->withErrors(['projectId' => 'Project not found.']);
        }
        return view('tasks.new', compact('projectId'));
    }

    /**
     * Store a newly created project in storage.
     *
     * @param TaskUpdateRequest $request
     *
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function store(TaskUpdateRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $result = $this->taskService->createTask($data);
            if (isset($result['error'])) {
                throw new Exception($result['error']);
            }
            return redirect()->route('tasks.index', ['projectId' => $data['project_id']]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

}
