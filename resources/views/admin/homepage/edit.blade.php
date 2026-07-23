@extends('layouts.admin')

@section('title', 'Edit Homepage')

@section('content')
    @php
        $selectedHeroImageId = old('hero_image_id', $homepage->hero_image_id);
        $selectedHeroImage = $selectedHeroImageId ? $images->firstWhere('id', (int) $selectedHeroImageId) : null;

        $assignmentRows = function ($items, $assignedItems, string $group, string $editRoute) {
            $oldRows = old($group);
            $oldRowsById = is_array($oldRows)
                ? collect($oldRows)
                    ->filter(fn ($row) => is_array($row) && filled($row['id'] ?? null))
                    ->keyBy(fn ($row) => (int) $row['id'])
                : collect();
            $assignedById = $assignedItems->keyBy('id');

            return $items
                ->map(function ($item, int $index) use ($assignedById, $editRoute, $oldRowsById): array {
                    $oldRow = $oldRowsById->get($item->id);
                    $hasOldRow = is_array($oldRow);
                    $assigned = $assignedById->get($item->id);

                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                        'edit_url' => route($editRoute, $item),
                        'sort_order' => $hasOldRow ? (int) ($oldRow['sort_order'] ?? 0) : (int) ($assigned?->pivot?->sort_order ?? $index + 1),
                        'is_active' => $hasOldRow ? array_key_exists('is_active', $oldRow) : (bool) ($assigned?->pivot?->is_active ?? false),
                    ];
                })
                ->sortBy('sort_order')
                ->values();
        };

        $expertiseRows = $assignmentRows($expertiseCards, $homepage->expertiseCards, 'expertise_cards', 'admin.expertise.edit');
        $projectRows = $assignmentRows($projects, $homepage->projects, 'projects', 'admin.projects.edit');
        $experienceRows = $assignmentRows($experiences, $homepage->experiences, 'experiences', 'admin.experiences.edit');
    @endphp

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <p class="mb-4 text-sm font-semibold uppercase text-primary">Homepage CMS</p>
                <h1 class="text-3xl font-semibold leading-tight sm:text-4xl">Edit homepage</h1>
                <p class="mt-5 text-base leading-7 text-base-content/70">
                    Editing {{ $homepage->name }}.
                    @if ($homepage->is_active)
                        This version is currently public.
                    @else
                        Activate it when the content is ready.
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a class="btn" href="{{ route('admin.homepage.index') }}">Back</a>
                <a class="btn" href="{{ route('admin.homepage.preview', $homepage) }}" target="_blank" rel="noopener">Preview</a>
                <form method="POST" action="{{ route('admin.homepage.duplicate', $homepage) }}">
                    @csrf
                    <button class="btn" type="submit">Duplicate</button>
                </form>
                @unless ($homepage->is_active)
                    <form method="POST" action="{{ route('admin.homepage.activate', $homepage) }}">
                        @csrf
                        <button class="btn btn-primary" type="submit">Activate</button>
                    </form>
                @endunless
            </div>
        </div>

        @if (session('status'))
            <div role="alert" class="alert alert-success mt-8">
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <form class="mt-10 space-y-8" method="POST" action="{{ route('admin.homepage.update', $homepage) }}">
            @csrf
            @method('PUT')

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Version</h2>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Version name</legend>
                        <input class="input w-full @error('name') input-error @enderror" type="text" name="name" value="{{ old('name', $homepage->name) }}" required>
                        @error('name')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">SEO</h2>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Meta title</legend>
                        <input class="input w-full @error('meta_title') input-error @enderror" type="text" name="meta_title" value="{{ old('meta_title', $homepage->meta_title) }}" maxlength="70">
                        <p class="label">Optional. Falls back to Andrew Bielecki | {{ $homepage->hero_headline }}.</p>
                        @error('meta_title')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Meta description</legend>
                        <textarea class="textarea min-h-24 w-full @error('meta_description') textarea-error @enderror" name="meta_description" maxlength="160">{{ old('meta_description', $homepage->meta_description) }}</textarea>
                        <p class="label">Optional. Falls back to a plain-text summary of the hero description.</p>
                        @error('meta_description')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Privacy and analytics</h2>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Privacy contact email</legend>
                        <input class="input w-full @error('privacy_contact_email') input-error @enderror" type="email" name="privacy_contact_email" value="{{ old('privacy_contact_email', $homepage->privacy_contact_email) }}" maxlength="254">
                        <p class="label">Optional. The active version's address appears in the public privacy notice for questions and data requests.</p>
                        @error('privacy_contact_email')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Hero section</h2>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Headline</legend>
                            <input class="input w-full @error('hero_headline') input-error @enderror" type="text" name="hero_headline" value="{{ old('hero_headline', $homepage->hero_headline) }}" required>
                            @error('hero_headline')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Image</legend>
                            <input id="hero_image_id" type="hidden" name="hero_image_id" value="{{ $selectedHeroImageId }}">
                            <div class="flex flex-col gap-3 rounded-box border border-base-300 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <span id="hero_image_label" class="text-sm text-base-content/70">
                                    {{ $selectedHeroImage?->alt_text ?? 'Use placeholder panel' }}
                                </span>
                                <button class="btn" type="button" data-image-picker-open data-target-input="hero_image_id" data-target-label="hero_image_label" data-header-only="1" data-placeholder="Use placeholder panel">
                                    Select image
                                </button>
                            </div>
                            @error('hero_image_id')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Title</legend>
                        <input class="input w-full @error('hero_title') input-error @enderror" type="text" name="hero_title" value="{{ old('hero_title', $homepage->hero_title) }}" required>
                        @error('hero_title')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Description</legend>
                        <textarea class="textarea min-h-32 w-full @error('hero_description') textarea-error @enderror" name="hero_description" required data-rich-text>{{ old('hero_description', $homepage->hero_description) }}</textarea>
                        @error('hero_description')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Expertise section</h2>

                    <label class="flex items-start justify-between gap-4 rounded-box border border-base-300 p-4">
                        <span>
                            <span class="block font-medium">Show Expertise section</span>
                            <span class="mt-1 block text-sm text-base-content/70">Controls whether this section appears on the public homepage and previews.</span>
                        </span>
                        <input type="hidden" name="show_expertise_section" value="0">
                        <input class="toggle" type="checkbox" name="show_expertise_section" value="1" @checked(old('show_expertise_section', $homepage->show_expertise_section))>
                    </label>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Headline</legend>
                            <input class="input w-full @error('expertise_headline') input-error @enderror" type="text" name="expertise_headline" value="{{ old('expertise_headline', $homepage->expertise_headline) }}" required>
                            @error('expertise_headline')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Title</legend>
                            <input class="input w-full @error('expertise_title') input-error @enderror" type="text" name="expertise_title" value="{{ old('expertise_title', $homepage->expertise_title) }}" required>
                            @error('expertise_title')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>

                    @include('admin.homepage.partials.assignment-table', [
                        'title' => 'Expertise',
                        'group' => 'expertise_cards',
                        'rows' => $expertiseRows,
                        'manageRoute' => route('admin.expertise.index'),
                        'emptyText' => 'Create expertise cards before assigning them to this version.',
                    ])
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Hobby projects section</h2>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Headline</legend>
                            <input class="input w-full @error('projects_headline') input-error @enderror" type="text" name="projects_headline" value="{{ old('projects_headline', $homepage->projects_headline) }}" required>
                            @error('projects_headline')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Title</legend>
                            <input class="input w-full @error('projects_title') input-error @enderror" type="text" name="projects_title" value="{{ old('projects_title', $homepage->projects_title) }}" required>
                            @error('projects_title')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Description</legend>
                        <textarea class="textarea min-h-28 w-full @error('projects_description') textarea-error @enderror" name="projects_description" required data-rich-text>{{ old('projects_description', $homepage->projects_description) }}</textarea>
                        @error('projects_description')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    @include('admin.homepage.partials.assignment-table', [
                        'title' => 'Projects',
                        'group' => 'projects',
                        'rows' => $projectRows,
                        'manageRoute' => route('admin.projects.index'),
                        'emptyText' => 'Create projects before assigning them to this version.',
                    ])
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Experience section</h2>

                    <label class="flex items-start justify-between gap-4 rounded-box border border-base-300 p-4">
                        <span>
                            <span class="block font-medium">Show Experience section</span>
                            <span class="mt-1 block text-sm text-base-content/70">Controls whether this section appears on the public homepage and previews.</span>
                        </span>
                        <input type="hidden" name="show_experience_section" value="0">
                        <input class="toggle" type="checkbox" name="show_experience_section" value="1" @checked(old('show_experience_section', $homepage->show_experience_section))>
                    </label>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Headline</legend>
                            <input class="input w-full @error('experience_headline') input-error @enderror" type="text" name="experience_headline" value="{{ old('experience_headline', $homepage->experience_headline) }}" required>
                            @error('experience_headline')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Title</legend>
                            <input class="input w-full @error('experience_title') input-error @enderror" type="text" name="experience_title" value="{{ old('experience_title', $homepage->experience_title) }}" required>
                            @error('experience_title')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Description</legend>
                        <textarea class="textarea min-h-28 w-full @error('experience_description') textarea-error @enderror" name="experience_description" required data-rich-text>{{ old('experience_description', $homepage->experience_description) }}</textarea>
                        @error('experience_description')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    @include('admin.homepage.partials.assignment-table', [
                        'title' => 'Experiences',
                        'group' => 'experiences',
                        'rows' => $experienceRows,
                        'manageRoute' => route('admin.experiences.index'),
                        'emptyText' => 'Create experiences before assigning them to this version.',
                    ])
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Contact section</h2>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Headline</legend>
                            <input class="input w-full @error('contact_headline') input-error @enderror" type="text" name="contact_headline" value="{{ old('contact_headline', $homepage->contact_headline) }}" required>
                            @error('contact_headline')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">Title</legend>
                            <input class="input w-full @error('contact_title') input-error @enderror" type="text" name="contact_title" value="{{ old('contact_title', $homepage->contact_title) }}" required>
                            @error('contact_title')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>

                    <fieldset class="fieldset">
                        <legend class="fieldset-legend">Description</legend>
                        <textarea class="textarea min-h-28 w-full @error('contact_description') textarea-error @enderror" name="contact_description" data-rich-text>{{ old('contact_description', $homepage->contact_description) }}</textarea>
                        @error('contact_description')
                            <p class="label text-error">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">GitHub URL</legend>
                            <input class="input w-full @error('github_url') input-error @enderror" type="url" name="github_url" value="{{ old('github_url', $homepage->github_url) }}">
                            @error('github_url')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset">
                            <legend class="fieldset-legend">LinkedIn URL</legend>
                            <input class="input w-full @error('linkedin_url') input-error @enderror" type="url" name="linkedin_url" value="{{ old('linkedin_url', $homepage->linkedin_url) }}">
                            @error('linkedin_url')
                                <p class="label text-error">{{ $message }}</p>
                            @enderror
                        </fieldset>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a class="btn" href="{{ route('admin.homepage.index') }}">Cancel</a>
                <button class="btn btn-primary" type="submit">Save as new version</button>
            </div>
        </form>

        @include('admin.images.partials.picker-modal')
    </section>
@endsection
