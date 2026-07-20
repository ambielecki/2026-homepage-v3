<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\HomepageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'is_active',
    'hero_image_id',
    'meta_title',
    'meta_description',
    'hero_headline',
    'hero_title',
    'hero_description',
    'expertise_headline',
    'expertise_title',
    'projects_headline',
    'projects_title',
    'projects_description',
    'experience_headline',
    'experience_title',
    'experience_description',
    'contact_headline',
    'contact_title',
    'contact_description',
    'github_url',
    'linkedin_url',
])]
class Homepage extends Model
{
    /** @use HasFactory<HomepageFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @param  Builder<Homepage>  $query
     * @return Builder<Homepage>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * @return BelongsTo<Image, Homepage>
     */
    public function heroImage(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'hero_image_id');
    }

    /**
     * @return BelongsToMany<HomepageExpertiseCard, Homepage>
     */
    public function expertiseCards(): BelongsToMany
    {
        return $this->belongsToMany(HomepageExpertiseCard::class, 'homepage_expertise_card_assignments')
            ->withPivot(['sort_order', 'is_active'])
            ->withTimestamps()
            ->orderByPivot('sort_order')
            ->orderBy('homepage_expertise_cards.id');
    }

    /**
     * @return BelongsToMany<HomepageExpertiseCard, Homepage>
     */
    public function activeExpertiseCards(): BelongsToMany
    {
        return $this->expertiseCards()->wherePivot('is_active', true);
    }

    /**
     * @return BelongsToMany<HomepageProject, Homepage>
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(HomepageProject::class, 'homepage_project_assignments')
            ->withPivot(['sort_order', 'is_active'])
            ->withTimestamps()
            ->orderByPivot('sort_order')
            ->orderBy('homepage_projects.id');
    }

    /**
     * @return BelongsToMany<HomepageProject, Homepage>
     */
    public function activeProjects(): BelongsToMany
    {
        return $this->projects()->wherePivot('is_active', true);
    }

    /**
     * @return BelongsToMany<HomepageExperience, Homepage>
     */
    public function experiences(): BelongsToMany
    {
        return $this->belongsToMany(HomepageExperience::class, 'homepage_experience_assignments')
            ->withPivot(['sort_order', 'is_active'])
            ->withTimestamps()
            ->orderByPivot('sort_order')
            ->orderBy('homepage_experiences.id');
    }

    /**
     * @return BelongsToMany<HomepageExperience, Homepage>
     */
    public function activeExperiences(): BelongsToMany
    {
        return $this->experiences()->wherePivot('is_active', true);
    }

    public function resolvedMetaTitle(): string
    {
        if (filled($this->meta_title)) {
            return (string) $this->meta_title;
        }

        return sprintf('Andrew Bielecki | %s', $this->hero_headline);
    }

    public function resolvedMetaDescription(): string
    {
        if (filled($this->meta_description)) {
            return $this->plainText((string) $this->meta_description, 160);
        }

        return $this->plainText((string) $this->hero_description, 155);
    }

    public static function defaultContent(): self
    {
        $homepage = new self(self::defaultAttributes('Default homepage'));

        $homepage->setRelation('activeExpertiseCards', new EloquentCollection(array_map(
            fn (array $attributes): HomepageExpertiseCard => new HomepageExpertiseCard($attributes),
            self::defaultExpertiseCards(),
        )));

        $homepage->setRelation('activeProjects', new EloquentCollection(array_map(
            function (array $attributes): HomepageProject {
                $project = new HomepageProject($attributes);
                $project->setRelation('image', null);

                return $project;
            },
            self::defaultProjects(),
        )));

        $homepage->setRelation('activeExperiences', new EloquentCollection(array_map(
            fn (array $attributes): HomepageExperience => new HomepageExperience($attributes),
            self::defaultExperiences(),
        )));

        return $homepage;
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultAttributes(?string $name = null): array
    {
        return [
            'name' => $name ?? 'Homepage draft',
            'is_active' => false,
            'hero_image_id' => null,
            'meta_title' => null,
            'meta_description' => null,
            'hero_headline' => 'Lead Software Engineer',
            'hero_title' => 'Building useful software, keeping teams moving, and making room for side projects.',
            'hero_description' => 'I work across product delivery, backend architecture, and practical frontend implementation. The final copy will land later; this version establishes the structure for an employer-focused professional profile.',
            'expertise_headline' => 'Expertise',
            'expertise_title' => 'Engineering judgment for teams that need momentum and maintainability.',
            'projects_headline' => 'Hobby Projects',
            'projects_title' => 'Half-finished ideas, useful experiments, and a few things I keep coming back to.',
            'projects_description' => 'This section is for personal projects and interests rather than polished case studies. The current copy is placeholder content, but the shape is ready for dive logs, homebrewing notes, small tools, and weekend builds.',
            'experience_headline' => 'Experience',
            'experience_title' => 'Professional experience still does the heavy lifting.',
            'experience_description' => 'A few themes that describe the professional work behind the public profile.',
            'contact_headline' => 'Contact',
            'contact_title' => 'Looking for a lead engineer who can bridge product, architecture, and delivery?',
            'contact_description' => 'This area will eventually include final contact details. For now, it provides the intended shape for employer-focused calls to action.',
            'github_url' => 'https://github.com/andrewbielecki',
            'linkedin_url' => 'https://www.linkedin.com/in/andrewbielecki',
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaultExpertiseCards(): array
    {
        return [
            [
                'title' => 'Backend architecture',
                'description' => 'Designing Laravel and PHP systems with clear boundaries, testable flows, and boring deployment paths.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Product delivery',
                'description' => 'Turning ambiguous product goals into scoped plans, useful milestones, and maintainable interfaces.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Frontend collaboration',
                'description' => 'Building accessible Blade and JavaScript experiences that respect design intent without turning every page into an SPA.',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaultProjects(): array
    {
        return [
            [
                'title' => 'DiveLogRepeat',
                'url' => null,
                'description' => 'Placeholder copy for a dive log and hobby project that tracks trips, notes, and the details that make dives memorable.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Homebrew Helper',
                'url' => null,
                'description' => 'Placeholder copy for brewing notes, recipe experiments, and a simple place to keep batches from disappearing into old spreadsheets.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Small tools',
                'url' => null,
                'description' => 'Placeholder copy for side utilities, learning projects, and weekend builds that are useful enough to keep around.',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function defaultExperiences(): array
    {
        return [
            [
                'title' => 'Lead engineering work',
                'description' => 'Help teams turn product goals into steady implementation plans, clear tradeoffs, and maintainable code.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Build Laravel systems',
                'description' => 'Work across backend architecture, Blade views, databases, queues, tests, and the practical edges of production software.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Collaborate across disciplines',
                'description' => 'Keep product, design, and engineering conversations grounded in what users need and what the system can support.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Improve team habits',
                'description' => 'Nudge code review, testing, documentation, and delivery practices toward habits that make future work easier.',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];
    }

    private function plainText(string $value, int $limit): string
    {
        return Str::limit(trim((string) preg_replace('/\s+/', ' ', strip_tags($value))), $limit, '');
    }
}
