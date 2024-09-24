<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ProjectUpdateRequest;
use App\Services\ProjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProjectController extends Controller
{
    protected ProjectService $projectService;

    /**
     * Inject ProjectService through the constructor.
     *
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the projects.
     *
     * @return View
     */
    public function index(): View
    {
        $projects = $this->projectService->getAllProjects();
        return view('projects.index', compact('projects'));
    }

    /**
     * Display the form for editing the specified project.
     *
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $project = $this->projectService->getProjectById($id);
        return view('projects.edit', compact('project'));
    }


    /**
     * Update the specified project in storage.
     *
     * @param ProjectUpdateRequest $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function update(ProjectUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            $data = $request->validated();
            $this->projectService->updateProject($id, $data);
            return redirect()->route('projects.index');
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
        $this->projectService->deleteProject($id);
        return redirect()->route('projects.index');
    }

    /**
     * Display the form for creating a new project.
     *
     * @return View
     */
    public function new(): View
    {
        return view('projects.new');
    }

    /**
     * Store a newly created project in storage.
     *
     * @param ProjectUpdateRequest $request
     *
     * @return RedirectResponse
     */
    public function store(ProjectUpdateRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            $this->projectService->createProject($data);
            return redirect()->route('projects.index');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }
}
