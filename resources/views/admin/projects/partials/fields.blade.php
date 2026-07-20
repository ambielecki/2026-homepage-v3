@php
    $selectedProjectImageId = old('image_id', $project->image_id);
    $selectedProjectImage = $selectedProjectImageId ? $images->firstWhere('id', (int) $selectedProjectImageId) : null;
@endphp

<fieldset class="fieldset">
    <legend class="fieldset-legend">Title</legend>
    <input class="input w-full @error('title') input-error @enderror" type="text" name="title" value="{{ old('title', $project->title) }}" required>
    @error('title')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>

<fieldset class="fieldset">
    <legend class="fieldset-legend">Project URL</legend>
    <input class="input w-full @error('url') input-error @enderror" type="url" name="url" value="{{ old('url', $project->url) }}">
    @error('url')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>

<fieldset class="fieldset">
    <legend class="fieldset-legend">Image</legend>
    <input id="project_image_id" type="hidden" name="image_id" value="{{ $selectedProjectImageId }}">
    <div class="flex flex-col gap-3 rounded-box border border-base-300 p-4 sm:flex-row sm:items-center sm:justify-between">
        <span id="project_image_label" class="text-sm text-base-content/70">
            {{ $selectedProjectImage?->alt_text ?? 'Use mockup placeholder' }}
        </span>
        <button class="btn" type="button" data-image-picker-open data-target-input="project_image_id" data-target-label="project_image_label" data-header-only="0" data-placeholder="Use mockup placeholder">
            Select image
        </button>
    </div>
    @error('image_id')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>

<fieldset class="fieldset">
    <legend class="fieldset-legend">Description</legend>
    <textarea class="textarea min-h-32 w-full @error('description') textarea-error @enderror" name="description" required data-rich-text>{{ old('description', $project->description) }}</textarea>
    @error('description')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
