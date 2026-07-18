<fieldset class="fieldset">
    <legend class="fieldset-legend">Alt text</legend>
    <input class="input w-full @error('alt_text') input-error @enderror" type="text" name="alt_text" value="{{ old('alt_text', $image?->alt_text) }}" required>
    @error('alt_text')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>

<fieldset class="fieldset">
    <legend class="fieldset-legend">Description</legend>
    <textarea class="textarea min-h-32 w-full @error('description') textarea-error @enderror" name="description">{{ old('description', $image?->description) }}</textarea>
    @error('description')
        <p class="label text-error">{{ $message }}</p>
    @enderror
</fieldset>

<label class="flex items-start gap-3 rounded-box border border-base-300 p-4">
    <input class="checkbox mt-1" type="checkbox" name="is_header" value="1" @checked(old('is_header', $image?->is_header ?? false))>
    <span>
        <span class="block text-sm font-semibold">Header image</span>
        <span class="block text-sm leading-6 text-base-content/70">Mark this image as appropriate for wide header placement.</span>
    </span>
</label>
