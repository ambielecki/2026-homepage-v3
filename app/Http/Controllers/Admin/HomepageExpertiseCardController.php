<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveHomepageExpertiseCardRequest;
use App\Models\HomepageExpertiseCard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class HomepageExpertiseCardController extends Controller
{
    public function index(): View
    {
        return view('admin.expertise.index', [
            'expertiseCards' => HomepageExpertiseCard::query()
                ->withCount('homepages')
                ->latest()
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.expertise.create', [
            'expertiseCard' => new HomepageExpertiseCard,
        ]);
    }

    public function store(SaveHomepageExpertiseCardRequest $request): RedirectResponse
    {
        $expertiseCard = HomepageExpertiseCard::query()->create($request->validated());

        return redirect()
            ->route('admin.expertise.edit', $expertiseCard)
            ->with('status', 'Expertise card created.');
    }

    public function edit(HomepageExpertiseCard $expertise): View
    {
        return view('admin.expertise.edit', [
            'expertiseCard' => $expertise,
        ]);
    }

    public function update(SaveHomepageExpertiseCardRequest $request, HomepageExpertiseCard $expertise): RedirectResponse
    {
        $expertise->update($request->validated());

        return redirect()
            ->route('admin.expertise.edit', $expertise)
            ->with('status', 'Expertise card updated.');
    }
}
