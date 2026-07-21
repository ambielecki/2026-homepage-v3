@php
    $modalId = sprintf('delete-image-%s', $image->id);
    $buttonClass = $buttonClass ?? 'btn btn-sm btn-error';
@endphp

<button class="{{ $buttonClass }}" type="button" onclick="document.getElementById('{{ $modalId }}').showModal()">
    Delete
</button>

<dialog id="{{ $modalId }}" class="modal">
    <div class="modal-box">
        <h3 class="text-lg font-semibold">Delete image?</h3>
        <p class="py-4 text-sm leading-6 text-base-content/70">
            This deletes the image record and all stored files for this image. If the image is still used by homepage content, deletion will be blocked.
        </p>
        <div class="modal-action">
            <form method="dialog">
                <button class="btn" type="submit">Cancel</button>
            </form>
            <form method="POST" action="{{ route('admin.images.destroy', $image) }}">
                @csrf
                @method('DELETE')
                <button class="btn btn-error" type="submit">Delete image</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button type="submit">Close</button>
    </form>
</dialog>
