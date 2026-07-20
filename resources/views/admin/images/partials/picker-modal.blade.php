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
