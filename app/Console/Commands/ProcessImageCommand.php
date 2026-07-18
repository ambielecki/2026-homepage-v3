<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Image as HomepageImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProcessImageCommand extends Command
{
    protected $signature = 'images:process {image : The image record id}';

    protected $description = 'Create web optimized image sizes';

    /**
     * @var array<string, int>
     */
    private array $sizes = [
        'small' => 480,
        'medium' => 960,
        'large' => 1440,
    ];

    public function handle(): int
    {
        $image = HomepageImage::query()->find($this->argument('image'));

        if (! $image instanceof HomepageImage) {
            $this->error('Image not found.');

            return self::FAILURE;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($image->original_path)) {
            $this->error('Original image file not found.');

            return self::FAILURE;
        }

        $sourcePath = $disk->path($image->original_path);

        foreach ($this->sizes as $label => $width) {
            Image::decodePath($sourcePath)
                ->scaleDown(width: $width)
                ->save($disk->path($image->sizedPath($label)), quality: 82);
        }

        $image->update([
            'has_sizes' => true,
        ]);

        $this->info('Image sizes processed.');

        return self::SUCCESS;
    }
}
