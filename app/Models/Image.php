<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'name',
    'description',
    'alt_text',
    'has_sizes',
    'is_header',
    'original_path',
    'original_extension',
    'mime_type',
    'size',
    'width',
    'height',
])]
class Image extends Model
{
    /** @use HasFactory<ImageFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_sizes' => 'boolean',
            'is_header' => 'boolean',
            'size' => 'integer',
            'width' => 'integer',
            'height' => 'integer',
        ];
    }

    public function originalUrl(): string
    {
        return Storage::disk('public')->url($this->original_path);
    }

    public function sizedPath(string $size): string
    {
        return sprintf('images/%s/%s_%s.webp', $this->name, $this->name, $size);
    }

    public function sizedUrl(string $size): string
    {
        return Storage::disk('public')->url($this->sizedPath($size));
    }

    public function thumbnailUrl(): string
    {
        if ($this->has_sizes && Storage::disk('public')->exists($this->sizedPath('small'))) {
            return $this->sizedUrl('small');
        }

        return $this->originalUrl();
    }
}
