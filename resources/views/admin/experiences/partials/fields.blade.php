<fieldset class="fieldset">
    <legend class="fieldset-legend">Title</legend>
    <input class="input w-full @error('title') input-error @enderror" type="text" name="title" value="{{ old('title', $experience->title) }}" required>
    @error('title')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>

<fieldset class="fieldset">
    <legend class="fieldset-legend">Description</legend>
    <textarea class="textarea min-h-32 w-full @error('description') textarea-error @enderror" name="description" required data-rich-text>{{ old('description', $experience->description) }}</textarea>
    @error('description')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>
