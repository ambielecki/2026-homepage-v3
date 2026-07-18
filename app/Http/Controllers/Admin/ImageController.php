<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Models\Image as HomepageImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;

class ImageController extends Controller
{
    public function index(): View
    {
        /** @var LengthAwarePaginator<int, HomepageImage> $images */
        $images = HomepageImage::query()
            ->latest()
            ->paginate(12);

        return view('admin.images.index', [
            'images' => $images,
        ]);
    }

    public function create(): View
    {
        return view('admin.images.create');
    }

    public function store(StoreImageRequest $request): RedirectResponse
    {
        $name = (string) Str::uuid7();
        $directory = sprintf('images/%s', $name);
        $uploadedFile = $request->file('image');
        $extension = strtolower($uploadedFile->extension() ?: $uploadedFile->guessExtension() ?: 'jpg');
        $path = sprintf('%s/%s.%s', $directory, $name, $extension);
        $processedImage = Image::decodePath($uploadedFile->getRealPath());

        Storage::disk('public')->putFileAs($directory, $uploadedFile, sprintf('%s.%s', $name, $extension));

        $image = HomepageImage::query()->create([
            'name' => $name,
            'description' => $request->string('description')->toString() ?: null,
            'alt_text' => $request->string('alt_text')->toString(),
            'is_header' => $request->boolean('is_header'),
            'original_path' => $path,
            'original_extension' => $extension,
            'mime_type' => $uploadedFile->getMimeType() ?: 'application/octet-stream',
            'size' => Storage::disk('public')->size($path),
            'width' => $processedImage->width(),
            'height' => $processedImage->height(),
        ]);

        Artisan::call('images:process', [
            'image' => $image->id,
        ]);

        return redirect()
            ->route('admin.images.index')
            ->with('status', 'Image uploaded.');
    }

    public function edit(HomepageImage $image): View
    {
        return view('admin.images.edit', [
            'image' => $image,
        ]);
    }

    public function update(UpdateImageRequest $request, HomepageImage $image): RedirectResponse
    {
        $image->update([
            'description' => $request->string('description')->toString() ?: null,
            'alt_text' => $request->string('alt_text')->toString(),
            'is_header' => $request->boolean('is_header'),
        ]);

        return redirect()
            ->route('admin.images.index')
            ->with('status', 'Image updated.');
    }
}
