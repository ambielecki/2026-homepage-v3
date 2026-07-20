<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HomepageExperienceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'title',
    'description',
])]
class HomepageExperience extends Model
{
    /** @use HasFactory<HomepageExperienceFactory> */
    use HasFactory;

    /**
     * @return BelongsToMany<Homepage, HomepageExperience>
     */
    public function homepages(): BelongsToMany
    {
        return $this->belongsToMany(Homepage::class, 'homepage_experience_assignments')
            ->withPivot(['sort_order', 'is_active'])
            ->withTimestamps();
    }
}
