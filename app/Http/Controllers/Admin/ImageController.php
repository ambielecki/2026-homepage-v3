<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Models\Homepage;
use App\Models\HomepageProject;
use App\Models\Image as HomepageImage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function picker(Request $request): JsonResponse
    {
        $images = HomepageImage::query()
            ->when($request->boolean('header_only'), fn ($query) => $query->where('is_header', true))
            ->latest()
            ->paginate(9);

        return response()->json([
            'images' => $images->getCollection()->map(fn (HomepageImage $image): array => [
                'id' => $image->id,
                'alt_text' => $image->alt_text,
                'description' => $image->description,
                'is_header' => $image->is_header,
                'thumbnail_url' => $image->thumbnailUrl(),
            ])->values(),
            'pagination' => [
                'current_page' => $images->currentPage(),
                'last_page' => $images->lastPage(),
                'next_page_url' => $images->nextPageUrl(),
                'previous_page_url' => $images->previousPageUrl(),
            ],
        ]);
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

    public function destroy(HomepageImage $image): RedirectResponse
    {
        $usages = $this->imageUsages($image);

        if ($usages !== []) {
            return redirect()
                ->back()
                ->with('image_delete_error', 'Image cannot be deleted because it is still in use.')
                ->with('image_delete_usages', $usages);
        }

        Storage::disk('public')->deleteDirectory(dirname($image->original_path));
        $image->delete();

        return redirect()
            ->route('admin.images.index')
            ->with('status', 'Image deleted.');
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    private function imageUsages(HomepageImage $image): array
    {
        $homepageUsages = $image->homepages()
            ->orderBy('name')
            ->get()
            ->map(fn (Homepage $homepage): array => [
                'label' => sprintf('Homepage: %s', $homepage->name),
                'url' => route('admin.homepage.edit', $homepage),
            ])
            ->all();

        $projectUsages = $image->projects()
            ->orderBy('title')
            ->get()
            ->map(fn (HomepageProject $project): array => [
                'label' => sprintf('Project: %s', $project->title),
                'url' => route('admin.projects.edit', $project),
            ])
            ->all();

        return array_values(array_merge($homepageUsages, $projectUsages));
    }
}
