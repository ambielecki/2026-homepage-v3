<?php

declare(strict_types=1);

use App\Models\Homepage;
use App\Models\HomepageExperience;
use App\Models\HomepageExpertiseCard;
use App\Models\HomepageProject;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('homepage admin routes require authentication', function (): void {
    $homepage = Homepage::factory()->create();

    $this->get('/admin/homepage')->assertRedirect('/login');
    $this->get(sprintf('/admin/homepage/%s/edit', $homepage->id))->assertRedirect('/login');
    $this->get(sprintf('/admin/homepage/%s/preview', $homepage->id))->assertRedirect('/login');
});

test('authenticated admins can view homepage versions', function (): void {
    $user = User::factory()->create();
    Homepage::factory()->active()->create([
        'name' => 'Published homepage',
    ]);
    Homepage::factory()->create([
        'name' => 'Draft homepage',
    ]);

    $response = $this->actingAs($user)->get('/admin/homepage');

    $response
        ->assertOk()
        ->assertSee('Homepage versions')
        ->assertSee('Published homepage')
        ->assertSee('Draft homepage')
        ->assertSee('Active')
        ->assertSee('Preview')
        ->assertSee('Create draft');
});

test('authenticated admins can preview any homepage version without activating it', function (): void {
    $user = User::factory()->create();
    $active = Homepage::factory()->active()->create([
        'hero_title' => 'Currently active homepage',
    ]);
    $draft = Homepage::factory()->create([
        'name' => 'Preview draft',
        'hero_headline' => 'Draft headline',
        'hero_title' => 'Draft homepage title',
        'hero_description' => 'Draft homepage description.',
    ]);
    HomepageExpertiseCard::factory()->for($draft)->create([
        'title' => 'Draft expertise',
        'description' => 'Draft expertise description.',
        'is_active' => true,
    ]);

    $response = $this->actingAs($user)->get(route('admin.homepage.preview', $draft));

    $response
        ->assertOk()
        ->assertSee('content="noindex, nofollow"', false)
        ->assertSee('Preview draft')
        ->assertSee('Draft homepage title')
        ->assertSee('Draft expertise')
        ->assertSee('Edit version')
        ->assertDontSee('Currently active homepage');

    expect($active->fresh()->is_active)->toBeTrue()
        ->and($draft->fresh()->is_active)->toBeFalse();
});

test('authenticated admins can create a draft homepage version with default rows', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/admin/homepage');

    $homepage = Homepage::query()->firstOrFail();

    $response
        ->assertRedirect(route('admin.homepage.edit', $homepage))
        ->assertSessionHas('status', 'Homepage draft created.');

    expect($homepage->is_active)->toBeFalse()
        ->and($homepage->expertiseCards()->count())->toBe(3)
        ->and($homepage->projects()->count())->toBe(3)
        ->and($homepage->experiences()->count())->toBe(4);
});

test('authenticated admins can save homepage edits as a new draft version', function (): void {
    $user = User::factory()->create();
    $homepage = Homepage::factory()->create();
    $expertise = HomepageExpertiseCard::factory()->for($homepage)->create();
    $project = HomepageProject::factory()->for($homepage)->create();
    $experience = HomepageExperience::factory()->for($homepage)->create();
    $image = Image::factory()->create();

    $response = $this->actingAs($user)->put(route('admin.homepage.update', $homepage), [
        'name' => 'Updated homepage',
        'hero_image_id' => $image->id,
        'hero_headline' => 'Updated hero headline',
        'hero_title' => 'Updated hero title',
        'hero_description' => 'Updated hero description.',
        'expertise_headline' => 'Updated expertise headline',
        'expertise_title' => 'Updated expertise title',
        'projects_headline' => 'Updated projects headline',
        'projects_title' => 'Updated projects title',
        'projects_description' => 'Updated projects description.',
        'experience_headline' => 'Updated experience headline',
        'experience_title' => 'Updated experience title',
        'experience_description' => 'Updated experience description.',
        'contact_headline' => 'Updated contact headline',
        'contact_title' => 'Updated contact title',
        'contact_description' => 'Updated contact description.',
        'github_url' => 'https://github.com/andrewbielecki',
        'linkedin_url' => 'https://www.linkedin.com/in/andrewbielecki',
        'expertise_cards' => [
            [
                'id' => $expertise->id,
                'title' => 'Updated expertise',
                'description' => 'Updated expertise description.',
                'sort_order' => 2,
                'is_active' => '1',
            ],
            [
                'title' => 'New expertise',
                'description' => 'New expertise description.',
                'sort_order' => 1,
                'is_active' => '1',
            ],
        ],
        'projects' => [
            [
                'id' => $project->id,
                'remove' => '1',
            ],
            [
                'image_id' => $image->id,
                'title' => 'New project',
                'description' => 'New project description.',
                'sort_order' => 1,
                'is_active' => '1',
            ],
        ],
        'experiences' => [
            [
                'id' => $experience->id,
                'title' => 'Updated experience',
                'description' => 'Updated experience description.',
                'sort_order' => 1,
            ],
        ],
    ]);

    $response
        ->assertSessionHas('status', 'Homepage saved as a new draft version.');

    $newHomepage = Homepage::query()
        ->where('name', 'Updated homepage')
        ->firstOrFail();

    $response->assertRedirect(route('admin.homepage.edit', $newHomepage));

    expect($homepage->fresh()->name)->not->toBe('Updated homepage')
        ->and($newHomepage->is_active)->toBeFalse()
        ->and($newHomepage->hero_image_id)->toBe($image->id)
        ->and($newHomepage->github_url)->toBe('https://github.com/andrewbielecki')
        ->and($newHomepage->expertiseCards()->where('title', 'Updated expertise')->exists())->toBeTrue()
        ->and($newHomepage->expertiseCards()->where('title', 'New expertise')->exists())->toBeTrue()
        ->and($newHomepage->projects()->whereKey($project->id)->exists())->toBeFalse()
        ->and($newHomepage->projects()->where('title', 'New project')->exists())->toBeTrue()
        ->and($newHomepage->experiences()->firstOrFail()->is_active)->toBeFalse();
});

test('authenticated admins can activate one homepage version', function (): void {
    $user = User::factory()->create();
    $active = Homepage::factory()->active()->create();
    $draft = Homepage::factory()->create();

    $response = $this->actingAs($user)->post(route('admin.homepage.activate', $draft));

    $response
        ->assertRedirect(route('admin.homepage.index'))
        ->assertSessionHas('status', 'Homepage version activated.');

    expect($active->fresh()->is_active)->toBeFalse()
        ->and($draft->fresh()->is_active)->toBeTrue();
});

test('authenticated admins can duplicate a homepage version', function (): void {
    $user = User::factory()->create();
    $homepage = Homepage::factory()->active()->create([
        'name' => 'Original version',
    ]);
    HomepageExpertiseCard::factory()->for($homepage)->create([
        'title' => 'Copied expertise',
    ]);
    HomepageProject::factory()->for($homepage)->create([
        'title' => 'Copied project',
    ]);
    HomepageExperience::factory()->for($homepage)->create([
        'title' => 'Copied experience',
    ]);

    $response = $this->actingAs($user)->post(route('admin.homepage.duplicate', $homepage));

    $clone = Homepage::query()->where('name', 'Original version copy')->firstOrFail();

    $response
        ->assertRedirect(route('admin.homepage.edit', $clone))
        ->assertSessionHas('status', 'Homepage version duplicated.');

    expect($clone->is_active)->toBeFalse()
        ->and($clone->expertiseCards()->where('title', 'Copied expertise')->exists())->toBeTrue()
        ->and($clone->projects()->where('title', 'Copied project')->exists())->toBeTrue()
        ->and($clone->experiences()->where('title', 'Copied experience')->exists())->toBeTrue();
});

test('authenticated admins can fetch paginated image picker results with header filtering', function (): void {
    $user = User::factory()->create();
    Image::factory()->create([
        'alt_text' => 'Header safe image',
        'is_header' => true,
    ]);
    Image::factory()->create([
        'alt_text' => 'General image',
        'is_header' => false,
    ]);

    $response = $this->actingAs($user)->getJson(route('admin.homepage.images', [
        'header_only' => '1',
    ]));

    $response
        ->assertOk()
        ->assertJsonPath('images.0.alt_text', 'Header safe image')
        ->assertJsonCount(1, 'images')
        ->assertJsonStructure([
            'images' => [
                '*' => ['id', 'alt_text', 'description', 'is_header', 'thumbnail_url'],
            ],
            'pagination' => ['current_page', 'last_page', 'next_page_url', 'previous_page_url'],
        ]);
});
