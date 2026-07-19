@extends('layouts.admin')

@section('title', 'Edit Homepage')

@section('content')
    @php
        $expertiseRows = old('expertise_cards', $homepage->expertiseCards->map(fn ($card) => [
            'id' => $card->id,
            'title' => $card->title,
            'description' => $card->description,
            'sort_order' => $card->sort_order,
            'is_active' => $card->is_active,
            'remove' => false,
        ])->all());
        $expertiseRows[] = ['id' => null, 'title' => '', 'description' => '', 'sort_order' => count($expertiseRows) + 1, 'is_active' => true, 'remove' => false];

        $projectRows = old('projects', $homepage->projects->map(fn ($project) => [
            'id' => $project->id,
            'image_id' => $project->image_id,
            'title' => $project->title,
            'description' => $project->description,
            'sort_order' => $project->sort_order,
            'is_active' => $project->is_active,
            'remove' => false,
        ])->all());
        $projectRows[] = ['id' => null, 'image_id' => null, 'title' => '', 'description' => '', 'sort_order' => count($projectRows) + 1, 'is_active' => true, 'remove' => false];

        $experienceRows = old('experiences', $homepage->experiences->map(fn ($experience) => [
            'id' => $experience->id,
            'title' => $experience->title,
            'description' => $experience->description,
            'sort_order' => $experience->sort_order,
            'is_active' => $experience->is_active,
            'remove' => false,
        ])->all());
        $experienceRows[] = ['id' => null, 'title' => '', 'description' => '', 'sort_order' => count($experienceRows) + 1, 'is_active' => true, 'remove' => false];

        $selectedHeroImageId = old('hero_image_id', $homepage->hero_image_id);
        $selectedHeroImage = $selectedHeroImageId ? $images->firstWhere('id', (int) $selectedHeroImageId) : null;
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

                    <div class="mt-4 space-y-5">
                        @foreach ($expertiseRows as $index => $row)
                            <div class="rounded-box border border-base-300 p-4">
                                <input type="hidden" name="expertise_cards[{{ $index }}][id]" value="{{ $row['id'] }}">

                                <div class="grid gap-4 lg:grid-cols-[6rem_1fr_1fr]">
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Order</legend>
                                        <input class="input w-full @error('expertise_cards.' . $index . '.sort_order') input-error @enderror" type="number" min="0" max="999" name="expertise_cards[{{ $index }}][sort_order]" value="{{ $row['sort_order'] ?? $index + 1 }}">
                                    </fieldset>

                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Title</legend>
                                        <input class="input w-full @error('expertise_cards.' . $index . '.title') input-error @enderror" type="text" name="expertise_cards[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}">
                                        @error('expertise_cards.' . $index . '.title')
                                            <p class="label text-error">{{ $message }}</p>
                                        @enderror
                                    </fieldset>

                                    <div class="flex items-end gap-4 pb-2">
                                        <label class="flex items-center gap-2 text-sm">
                                            <input class="toggle toggle-sm" type="checkbox" name="expertise_cards[{{ $index }}][is_active]" value="1" @checked((bool) ($row['is_active'] ?? true))>
                                            Active
                                        </label>
                                        @if ($row['id'])
                                            <label class="flex items-center gap-2 text-sm text-error">
                                                <input class="checkbox checkbox-sm" type="checkbox" name="expertise_cards[{{ $index }}][remove]" value="1" @checked((bool) ($row['remove'] ?? false))>
                                                Remove
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Description</legend>
                                    <textarea class="textarea min-h-24 w-full @error('expertise_cards.' . $index . '.description') textarea-error @enderror" name="expertise_cards[{{ $index }}][description]" data-rich-text>{{ $row['description'] ?? '' }}</textarea>
                                    @error('expertise_cards.' . $index . '.description')
                                        <p class="label text-error">{{ $message }}</p>
                                    @enderror
                                </fieldset>
                            </div>
                        @endforeach
                    </div>
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

                    <div class="mt-4 space-y-5">
                        @foreach ($projectRows as $index => $row)
                            <div class="rounded-box border border-base-300 p-4">
                                <input type="hidden" name="projects[{{ $index }}][id]" value="{{ $row['id'] }}">

                                <div class="grid gap-4 lg:grid-cols-[6rem_1fr_1fr]">
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Order</legend>
                                        <input class="input w-full @error('projects.' . $index . '.sort_order') input-error @enderror" type="number" min="0" max="999" name="projects[{{ $index }}][sort_order]" value="{{ $row['sort_order'] ?? $index + 1 }}">
                                    </fieldset>

                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Title</legend>
                                        <input class="input w-full @error('projects.' . $index . '.title') input-error @enderror" type="text" name="projects[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}">
                                        @error('projects.' . $index . '.title')
                                            <p class="label text-error">{{ $message }}</p>
                                        @enderror
                                    </fieldset>

                                    @php
                                        $projectImageId = $row['image_id'] ?? null;
                                        $projectImage = $projectImageId ? $images->firstWhere('id', (int) $projectImageId) : null;
                                        $projectImageInputId = 'project_' . $index . '_image_id';
                                        $projectImageLabelId = 'project_' . $index . '_image_label';
                                    @endphp
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Image</legend>
                                        <input id="{{ $projectImageInputId }}" type="hidden" name="projects[{{ $index }}][image_id]" value="{{ $projectImageId }}">
                                        <div class="flex flex-col gap-3 rounded-box border border-base-300 p-3 sm:flex-row sm:items-center sm:justify-between">
                                            <span id="{{ $projectImageLabelId }}" class="text-sm text-base-content/70">
                                                {{ $projectImage?->alt_text ?? 'Use mockup placeholder' }}
                                            </span>
                                            <button class="btn btn-sm" type="button" data-image-picker-open data-target-input="{{ $projectImageInputId }}" data-target-label="{{ $projectImageLabelId }}" data-header-only="0" data-placeholder="Use mockup placeholder">
                                                Select image
                                            </button>
                                        </div>
                                    </fieldset>
                                </div>

                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Description</legend>
                                    <textarea class="textarea min-h-24 w-full @error('projects.' . $index . '.description') textarea-error @enderror" name="projects[{{ $index }}][description]" data-rich-text>{{ $row['description'] ?? '' }}</textarea>
                                    @error('projects.' . $index . '.description')
                                        <p class="label text-error">{{ $message }}</p>
                                    @enderror
                                </fieldset>

                                <div class="flex flex-wrap gap-4 pt-2">
                                    <label class="flex items-center gap-2 text-sm">
                                        <input class="toggle toggle-sm" type="checkbox" name="projects[{{ $index }}][is_active]" value="1" @checked((bool) ($row['is_active'] ?? true))>
                                        Active
                                    </label>
                                    @if ($row['id'])
                                        <label class="flex items-center gap-2 text-sm text-error">
                                            <input class="checkbox checkbox-sm" type="checkbox" name="projects[{{ $index }}][remove]" value="1" @checked((bool) ($row['remove'] ?? false))>
                                            Remove
                                        </label>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card card-border bg-base-100">
                <div class="card-body">
                    <h2 class="card-title">Experience section</h2>

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

                    <div class="mt-4 space-y-5">
                        @foreach ($experienceRows as $index => $row)
                            <div class="rounded-box border border-base-300 p-4">
                                <input type="hidden" name="experiences[{{ $index }}][id]" value="{{ $row['id'] }}">

                                <div class="grid gap-4 lg:grid-cols-[6rem_1fr_1fr]">
                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Order</legend>
                                        <input class="input w-full @error('experiences.' . $index . '.sort_order') input-error @enderror" type="number" min="0" max="999" name="experiences[{{ $index }}][sort_order]" value="{{ $row['sort_order'] ?? $index + 1 }}">
                                    </fieldset>

                                    <fieldset class="fieldset">
                                        <legend class="fieldset-legend">Title</legend>
                                        <input class="input w-full @error('experiences.' . $index . '.title') input-error @enderror" type="text" name="experiences[{{ $index }}][title]" value="{{ $row['title'] ?? '' }}">
                                        @error('experiences.' . $index . '.title')
                                            <p class="label text-error">{{ $message }}</p>
                                        @enderror
                                    </fieldset>

                                    <div class="flex items-end gap-4 pb-2">
                                        <label class="flex items-center gap-2 text-sm">
                                            <input class="toggle toggle-sm" type="checkbox" name="experiences[{{ $index }}][is_active]" value="1" @checked((bool) ($row['is_active'] ?? true))>
                                            Active
                                        </label>
                                        @if ($row['id'])
                                            <label class="flex items-center gap-2 text-sm text-error">
                                                <input class="checkbox checkbox-sm" type="checkbox" name="experiences[{{ $index }}][remove]" value="1" @checked((bool) ($row['remove'] ?? false))>
                                                Remove
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <fieldset class="fieldset">
                                    <legend class="fieldset-legend">Description</legend>
                                    <textarea class="textarea min-h-24 w-full @error('experiences.' . $index . '.description') textarea-error @enderror" name="experiences[{{ $index }}][description]" data-rich-text>{{ $row['description'] ?? '' }}</textarea>
                                    @error('experiences.' . $index . '.description')
                                        <p class="label text-error">{{ $message }}</p>
                                    @enderror
                                </fieldset>
                            </div>
                        @endforeach
                    </div>
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

        <dialog id="image_picker_modal" class="modal" data-image-picker-modal data-url="{{ route('admin.homepage.images') }}">
            <div class="modal-box max-w-5xl">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-xl font-semibold">Select image</h2>
                    </div>
                    <form method="dialog">
                        <button class="btn btn-sm" type="submit">Close</button>
                    </form>
                </div>

                <div class="mt-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <label class="flex items-center gap-2 text-sm">
                        <input class="checkbox checkbox-sm" type="checkbox" data-image-picker-header-filter>
                        Header images only
                    </label>
                    <a class="btn btn-sm" href="{{ route('admin.images.create') }}">Upload image</a>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-image-picker-results>
                    <div class="col-span-full text-sm text-base-content/70">Loading images.</div>
                </div>

                <div class="modal-action justify-between">
                    <button class="btn" type="button" data-image-picker-clear>Use placeholder</button>
                    <div class="join">
                        <button class="btn join-item" type="button" data-image-picker-prev>Previous</button>
                        <button class="btn join-item" type="button" data-image-picker-next>Next</button>
                    </div>
                </div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </section>
@endsection
