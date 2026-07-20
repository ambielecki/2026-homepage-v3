<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHomepageRequest;
use App\Models\Homepage;
use App\Models\HomepageExperience;
use App\Models\HomepageExpertiseCard;
use App\Models\HomepageProject;
use App\Models\Image;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    public function index(): View
    {
        $homepages = Homepage::query()
            ->withCount(['expertiseCards', 'projects', 'experiences'])
            ->latest()
            ->get();

        return view('admin.homepage.index', [
            'homepages' => $homepages,
        ]);
    }

    public function store(): RedirectResponse
    {
        $homepage = DB::transaction(function (): Homepage {
            $homepage = Homepage::query()->create(Homepage::defaultAttributes(sprintf('Homepage draft %s', now()->format('Y-m-d H:i'))));

            $this->createDefaultRows($homepage);

            return $homepage;
        });

        return redirect()
            ->route('admin.homepage.edit', $homepage)
            ->with('status', 'Homepage draft created.');
    }

    public function edit(Homepage $homepage): View
    {
        $homepage->load(['expertiseCards', 'projects.image', 'experiences']);

        $images = Image::query()
            ->latest()
            ->get();

        return view('admin.homepage.edit', [
            'homepage' => $homepage,
            'expertiseCards' => HomepageExpertiseCard::query()->orderBy('title')->get(),
            'projects' => HomepageProject::query()->with('image')->orderBy('title')->get(),
            'experiences' => HomepageExperience::query()->orderBy('title')->get(),
            'images' => $images,
        ]);
    }

    public function preview(Homepage $homepage): View
    {
        $homepage->load([
            'heroImage',
            'activeExpertiseCards',
            'activeProjects.image',
            'activeExperiences',
        ]);

        return view('homepage', [
            'homepage' => $homepage,
            'isPreview' => true,
        ]);
    }

    public function update(UpdateHomepageRequest $request, Homepage $homepage): RedirectResponse
    {
        $validated = $request->validated();

        $newHomepage = DB::transaction(function () use ($validated): Homepage {
            $homepage = Homepage::query()->create([
                ...Arr::except($validated, ['expertise_cards', 'projects', 'experiences']),
                'is_active' => false,
            ]);

            $this->syncRepeatableItems(
                $homepage,
                'expertiseCards',
                $validated['expertise_cards'] ?? [],
            );

            $this->syncRepeatableItems(
                $homepage,
                'projects',
                $validated['projects'] ?? [],
            );

            $this->syncRepeatableItems(
                $homepage,
                'experiences',
                $validated['experiences'] ?? [],
            );

            return $homepage;
        });

        return redirect()
            ->route('admin.homepage.edit', $newHomepage)
            ->with('status', 'Homepage saved as a new draft version.');
    }

    public function activate(Homepage $homepage): RedirectResponse
    {
        DB::transaction(function () use ($homepage): void {
            Homepage::query()
                ->whereKeyNot($homepage->id)
                ->update(['is_active' => false]);

            $homepage->update(['is_active' => true]);
        });

        return redirect()
            ->route('admin.homepage.index')
            ->with('status', 'Homepage version activated.');
    }

    public function duplicate(Homepage $homepage): RedirectResponse
    {
        $homepage->load(['expertiseCards', 'projects', 'experiences']);

        $clone = DB::transaction(function () use ($homepage): Homepage {
            $clone = $homepage->replicate();
            $clone->name = sprintf('%s copy', $homepage->name);
            $clone->is_active = false;
            $clone->save();

            $this->copyAssignments($homepage, $clone, 'expertiseCards');
            $this->copyAssignments($homepage, $clone, 'projects');
            $this->copyAssignments($homepage, $clone, 'experiences');

            return $clone;
        });

        return redirect()
            ->route('admin.homepage.edit', $clone)
            ->with('status', 'Homepage version duplicated.');
    }

    public function destroy(Homepage $homepage): RedirectResponse
    {
        if ($homepage->is_active) {
            return redirect()
                ->route('admin.homepage.index')
                ->with('status', 'The active homepage version cannot be deleted. Activate another version first.');
        }

        $homepage->delete();

        return redirect()
            ->route('admin.homepage.index')
            ->with('status', 'Homepage version deleted.');
    }

    private function createDefaultRows(Homepage $homepage): void
    {
        foreach (Homepage::defaultExpertiseCards() as $attributes) {
            $card = HomepageExpertiseCard::query()->create(Arr::except($attributes, ['sort_order', 'is_active']));
            $homepage->expertiseCards()->attach($card, Arr::only($attributes, ['sort_order', 'is_active']));
        }

        foreach (Homepage::defaultProjects() as $attributes) {
            $project = HomepageProject::query()->create(Arr::except($attributes, ['sort_order', 'is_active']));
            $homepage->projects()->attach($project, Arr::only($attributes, ['sort_order', 'is_active']));
        }

        foreach (Homepage::defaultExperiences() as $attributes) {
            $experience = HomepageExperience::query()->create(Arr::except($attributes, ['sort_order', 'is_active']));
            $homepage->experiences()->attach($experience, Arr::only($attributes, ['sort_order', 'is_active']));
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function syncRepeatableItems(Homepage $homepage, string $relation, array $rows): void
    {
        $assignments = [];

        foreach ($rows as $row) {
            if (blank($row['id'] ?? null)) {
                continue;
            }

            $assignments[(int) $row['id']] = [
                'sort_order' => (int) ($row['sort_order'] ?? 0),
                'is_active' => $this->booleanValue($row['is_active'] ?? false),
            ];
        }

        $homepage->{$relation}()->sync($assignments);
    }

    private function copyAssignments(Homepage $source, Homepage $target, string $relation): void
    {
        $assignments = [];

        foreach ($source->{$relation} as $item) {
            $assignments[$item->id] = [
                'sort_order' => $item->pivot->sort_order,
                'is_active' => $item->pivot->is_active,
            ];
        }

        $target->{$relation}()->sync($assignments);
    }

    private function booleanValue(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
