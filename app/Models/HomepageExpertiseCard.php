<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HomepageExpertiseCardFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'title',
    'description',
])]
class HomepageExpertiseCard extends Model
{
    /** @use HasFactory<HomepageExpertiseCardFactory> */
    use HasFactory;

    /**
     * @return BelongsToMany<Homepage, HomepageExpertiseCard>
     */
    public function homepages(): BelongsToMany
    {
        return $this->belongsToMany(Homepage::class, 'homepage_expertise_card_assignments')
            ->withPivot(['sort_order', 'is_active'])
            ->withTimestamps();
    }
}
