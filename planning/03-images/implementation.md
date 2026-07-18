# Images Implementation Plan

- Add an `images` table and `App\Models\Image` for uploaded image metadata.
- Store uploaded originals as WebP files on the public disk under `images/{uuid-v7}/{uuid-v7}.webp`.
- Add authenticated `/admin/images` list, upload, store, edit, and update routes.
- Process uploaded images with an Artisan command that creates `_small`, `_medium`, and `_large` WebP variants and marks `has_sizes`.
- Add admin image views using daisyUI form, card, file input, checkbox, and button components.
- Add feature tests in `ImageTest.php` for upload, listing, editing, route protection, and image processing.
