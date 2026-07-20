<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHomepageRequest;
use App\Models\Homepage;
use App\Models\Image;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
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
                ['title', 'description', 'sort_order', 'is_active'],
            );

            $this->syncRepeatableItems(
                $homepage,
                'projects',
                $validated['projects'] ?? [],
                ['image_id', 'title', 'url', 'description', 'sort_order', 'is_active'],
            );

            $this->syncRepeatableItems(
                $homepage,
                'experiences',
                $validated['experiences'] ?? [],
                ['title', 'description', 'sort_order', 'is_active'],
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

            foreach ($homepage->expertiseCards as $card) {
                $clone->expertiseCards()->create($card->only(['title', 'description', 'sort_order', 'is_active']));
            }

            foreach ($homepage->projects as $project) {
                $clone->projects()->create($project->only(['image_id', 'title', 'url', 'description', 'sort_order', 'is_active']));
            }

            foreach ($homepage->experiences as $experience) {
                $clone->experiences()->create($experience->only(['title', 'description', 'sort_order', 'is_active']));
            }

            return $clone;
        });

        return redirect()
            ->route('admin.homepage.edit', $clone)
            ->with('status', 'Homepage version duplicated.');
    }

    private function createDefaultRows(Homepage $homepage): void
    {
        foreach (Homepage::defaultExpertiseCards() as $attributes) {
            $homepage->expertiseCards()->create($attributes);
        }

        foreach (Homepage::defaultProjects() as $attributes) {
            $homepage->projects()->create($attributes);
        }

        foreach (Homepage::defaultExperiences() as $attributes) {
            $homepage->experiences()->create($attributes);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, string>  $fields
     */
    private function syncRepeatableItems(Homepage $homepage, string $relation, array $rows, array $fields): void
    {
        $query = $homepage->{$relation}();
        $existingIds = $query->pluck('id')->map(fn (int $id): int => $id)->all();

        foreach ($rows as $row) {
            $id = filled($row['id'] ?? null) ? (int) $row['id'] : null;

            if ($this->booleanValue($row['remove'] ?? false)) {
                if ($id !== null && in_array($id, $existingIds, true)) {
                    $query->whereKey($id)->delete();
                }

                continue;
            }

            if (! $this->hasRepeatableContent($row)) {
                continue;
            }

            $payload = $this->repeatablePayload($row, $fields);

            if ($id !== null && in_array($id, $existingIds, true)) {
                /** @var Model $item */
                $item = $query->whereKey($id)->firstOrFail();
                $item->update($payload);

                continue;
            }

            $query->create($payload);
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function hasRepeatableContent(array $row): bool
    {
        return filled($row['id'] ?? null)
            || filled($row['title'] ?? null)
            || filled($row['url'] ?? null)
            || filled($row['description'] ?? null)
            || filled($row['image_id'] ?? null);
    }

    /**
     * @param  array<string, mixed>  $row
     * @param  array<int, string>  $fields
     * @return array<string, mixed>
     */
    private function repeatablePayload(array $row, array $fields): array
    {
        $payload = [];

        foreach ($fields as $field) {
            $payload[$field] = match ($field) {
                'is_active' => $this->booleanValue($row[$field] ?? false),
                'sort_order' => (int) ($row[$field] ?? 0),
                'image_id' => filled($row[$field] ?? null) ? (int) $row[$field] : null,
                default => $row[$field] ?? '',
            };
        }

        return $payload;
    }

    private function booleanValue(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}
