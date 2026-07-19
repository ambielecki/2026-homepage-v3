<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HomepageProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'homepage_id',
    'image_id',
    'title',
    'description',
    'sort_order',
    'is_active',
])]
class HomepageProject extends Model
{
    /** @use HasFactory<HomepageProjectFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Homepage, HomepageProject>
     */
    public function homepage(): BelongsTo
    {
        return $this->belongsTo(Homepage::class);
    }

    /**
     * @return BelongsTo<Image, HomepageProject>
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
