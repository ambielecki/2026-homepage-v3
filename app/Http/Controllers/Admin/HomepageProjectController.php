<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveHomepageProjectRequest;
use App\Models\HomepageProject;
use App\Models\Image;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomepageProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => HomepageProject::query()
                ->with('image')
                ->withCount('homepages')
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.create', [
            'project' => new HomepageProject,
            'images' => Image::query()->latest()->get(),
        ]);
    }

    public function store(SaveHomepageProjectRequest $request): RedirectResponse
    {
        $project = HomepageProject::query()->create($request->validated());

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Project created.');
    }

    public function edit(HomepageProject $project): View
    {
        return view('admin.projects.edit', [
            'project' => $project->load('image'),
            'images' => Image::query()->latest()->get(),
        ]);
    }

    public function update(SaveHomepageProjectRequest $request, HomepageProject $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('status', 'Project updated.');
    }
}
