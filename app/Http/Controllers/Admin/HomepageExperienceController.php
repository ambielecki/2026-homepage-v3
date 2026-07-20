<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveHomepageExperienceRequest;
use App\Models\HomepageExperience;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomepageExperienceController extends Controller
{
    public function index(): View
    {
        return view('admin.experiences.index', [
            'experiences' => HomepageExperience::query()
                ->withCount('homepages')
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.experiences.create', [
            'experience' => new HomepageExperience,
        ]);
    }

    public function store(SaveHomepageExperienceRequest $request): RedirectResponse
    {
        $experience = HomepageExperience::query()->create($request->validated());

        return redirect()
            ->route('admin.experiences.edit', $experience)
            ->with('status', 'Experience created.');
    }

    public function edit(HomepageExperience $experience): View
    {
        return view('admin.experiences.edit', [
            'experience' => $experience,
        ]);
    }

    public function update(SaveHomepageExperienceRequest $request, HomepageExperience $experience): RedirectResponse
    {
        $experience->update($request->validated());

        return redirect()
            ->route('admin.experiences.edit', $experience)
            ->with('status', 'Experience updated.');
    }
}
