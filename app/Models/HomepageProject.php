<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HomepageProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'image_id',
    'title',
    'url',
    'description',
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
            'image_id' => 'integer',
        ];
    }

    /**
     * @return BelongsToMany<Homepage, HomepageProject>
     */
    public function homepages(): BelongsToMany
    {
        return $this->belongsToMany(Homepage::class, 'homepage_project_assignments')
            ->withPivot(['sort_order', 'is_active'])
            ->withTimestamps();
    }

    /**
     * @return BelongsTo<Image, HomepageProject>
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }
}
