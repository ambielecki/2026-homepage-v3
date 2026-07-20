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
    $project = HomepageProject::factory()->create();
    $experience = HomepageExperience::factory()->create();
    $expertise = HomepageExpertiseCard::factory()->create();

    $this->get('/admin/homepage')->assertRedirect('/login');
    $this->get(sprintf('/admin/homepage/%s/edit', $homepage->id))->assertRedirect('/login');
    $this->get(sprintf('/admin/homepage/%s/preview', $homepage->id))->assertRedirect('/login');
    $this->delete(sprintf('/admin/homepage/%s', $homepage->id))->assertRedirect('/login');
    $this->get('/admin/projects')->assertRedirect('/login');
    $this->get(sprintf('/admin/projects/%s/edit', $project->id))->assertRedirect('/login');
    $this->get('/admin/experiences')->assertRedirect('/login');
    $this->get(sprintf('/admin/experiences/%s/edit', $experience->id))->assertRedirect('/login');
    $this->get('/admin/expertise')->assertRedirect('/login');
    $this->get(sprintf('/admin/expertise/%s/edit', $expertise->id))->assertRedirect('/login');
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
        ->assertSee('Delete')
        ->assertSee('Create draft');
});

test('authenticated admins can reach new admin navigation links', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin');

    $response
        ->assertOk()
        ->assertSee('href="/"', false)
        ->assertSee('Live')
        ->assertSee(route('admin.projects.index'))
        ->assertSee(route('admin.experiences.index'))
        ->assertSee(route('admin.expertise.index'))
        ->assertSee('Manage Projects')
        ->assertDontSee('Session');
});

test('authenticated admins can edit project urls in the project section', function (): void {
    $user = User::factory()->create();
    $project = HomepageProject::factory()->create([
        'title' => 'ShowMyRides',
        'url' => 'https://showmyrides.com',
    ]);

    $response = $this->actingAs($user)->get(route('admin.projects.edit', $project));

    $response
        ->assertOk()
        ->assertSee('Project URL')
        ->assertSee('name="url"', false)
        ->assertSee('value="https://showmyrides.com"', false);
});

test('authenticated admins can preview any homepage version without activating it', function (): void {
    $user = User::factory()->create();
    config(['app.env' => 'production']);
    $active = Homepage::factory()->active()->create([
        'hero_title' => 'Currently active homepage',
    ]);
    $draft = Homepage::factory()->create([
        'name' => 'Preview draft',
        'hero_headline' => 'Draft headline',
        'hero_title' => 'Draft homepage title',
        'hero_description' => 'Draft homepage description.',
    ]);
    $expertise = HomepageExpertiseCard::factory()->create([
        'title' => 'Draft expertise',
        'description' => 'Draft expertise description.',
    ]);
    $draft->expertiseCards()->attach($expertise, [
        'sort_order' => 1,
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

test('authenticated admins can edit homepage seo fields', function (): void {
    $user = User::factory()->create();
    $homepage = Homepage::factory()->create([
        'meta_title' => 'Editable SEO title',
        'meta_description' => 'Editable SEO description.',
    ]);

    $response = $this->actingAs($user)->get(route('admin.homepage.edit', $homepage));

    $response
        ->assertOk()
        ->assertSee('SEO')
        ->assertSee('name="meta_title"', false)
        ->assertSee('value="Editable SEO title"', false)
        ->assertSee('name="meta_description"', false)
        ->assertSee('Editable SEO description.');
});

test('authenticated admins can edit optional section visibility', function (): void {
    $user = User::factory()->create();
    $homepage = Homepage::factory()->create([
        'show_expertise_section' => false,
        'show_experience_section' => true,
    ]);

    $response = $this->actingAs($user)->get(route('admin.homepage.edit', $homepage));

    $response
        ->assertOk()
        ->assertSee('Show Expertise section')
        ->assertSee('name="show_expertise_section"', false)
        ->assertSee('Show Experience section')
        ->assertSee('name="show_experience_section"', false)
        ->assertSee('class="toggle"', false);
});

test('authenticated admins can create a draft homepage version with default assignments', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/admin/homepage');

    $homepage = Homepage::query()->firstOrFail();

    $response
        ->assertRedirect(route('admin.homepage.edit', $homepage))
        ->assertSessionHas('status', 'Homepage draft created.');

    expect($homepage->is_active)->toBeFalse()
        ->and($homepage->show_expertise_section)->toBeTrue()
        ->and($homepage->show_experience_section)->toBeTrue()
        ->and(HomepageExpertiseCard::query()->count())->toBe(3)
        ->and(HomepageProject::query()->count())->toBe(3)
        ->and(HomepageExperience::query()->count())->toBe(4)
        ->and($homepage->expertiseCards()->count())->toBe(3)
        ->and($homepage->projects()->count())->toBe(3)
        ->and($homepage->experiences()->count())->toBe(4);
});

test('authenticated admins can save homepage edits as a new draft version with assignments', function (): void {
    $user = User::factory()->create();
    $homepage = Homepage::factory()->create();
    $expertise = HomepageExpertiseCard::factory()->create([
        'title' => 'Existing expertise',
    ]);
    $inactiveExpertise = HomepageExpertiseCard::factory()->create([
        'title' => 'Inactive expertise',
    ]);
    $project = HomepageProject::factory()->create([
        'url' => 'https://showmyrides.com',
    ]);
    $experience = HomepageExperience::factory()->create();
    $image = Image::factory()->create();

    $homepage->expertiseCards()->attach($expertise, ['sort_order' => 1, 'is_active' => true]);
    $homepage->projects()->attach($project, ['sort_order' => 1, 'is_active' => true]);
    $homepage->experiences()->attach($experience, ['sort_order' => 1, 'is_active' => true]);

    $response = $this->actingAs($user)->put(route('admin.homepage.update', $homepage), [
        'name' => 'Updated homepage',
        'hero_image_id' => $image->id,
        'meta_title' => 'Updated SEO title',
        'meta_description' => 'Updated SEO description.',
        'hero_headline' => 'Updated hero headline',
        'hero_title' => 'Updated hero title',
        'hero_description' => 'Updated hero description.',
        'expertise_headline' => 'Updated expertise headline',
        'expertise_title' => 'Updated expertise title',
        'show_expertise_section' => '0',
        'projects_headline' => 'Updated projects headline',
        'projects_title' => 'Updated projects title',
        'projects_description' => 'Updated projects description.',
        'experience_headline' => 'Updated experience headline',
        'experience_title' => 'Updated experience title',
        'experience_description' => 'Updated experience description.',
        'show_experience_section' => '1',
        'contact_headline' => 'Updated contact headline',
        'contact_title' => 'Updated contact title',
        'contact_description' => 'Updated contact description.',
        'github_url' => 'https://github.com/andrewbielecki',
        'linkedin_url' => 'https://www.linkedin.com/in/andrewbielecki',
        'expertise_cards' => [
            [
                'id' => $expertise->id,
                'sort_order' => 2,
                'is_active' => '1',
            ],
            [
                'id' => $inactiveExpertise->id,
                'sort_order' => 1,
            ],
        ],
        'projects' => [
            [
                'id' => $project->id,
                'sort_order' => 1,
                'is_active' => '1',
            ],
        ],
        'experiences' => [
            [
                'id' => $experience->id,
                'sort_order' => 1,
            ],
        ],
    ]);

    $response
        ->assertSessionHas('status', 'Homepage saved as a new draft version.');

    $newHomepage = Homepage::query()
        ->where('name', 'Updated homepage')
        ->firstOrFail();

    $activeExpertise = $newHomepage->expertiseCards()->whereKey($expertise->id)->firstOrFail();
    $inactiveAssignedExpertise = $newHomepage->expertiseCards()->whereKey($inactiveExpertise->id)->firstOrFail();
    $assignedProject = $newHomepage->projects()->whereKey($project->id)->firstOrFail();
    $inactiveExperience = $newHomepage->experiences()->whereKey($experience->id)->firstOrFail();

    $response->assertRedirect(route('admin.homepage.edit', $newHomepage));

    expect($homepage->fresh()->name)->not->toBe('Updated homepage')
        ->and($newHomepage->is_active)->toBeFalse()
        ->and($newHomepage->hero_image_id)->toBe($image->id)
        ->and($newHomepage->meta_title)->toBe('Updated SEO title')
        ->and($newHomepage->meta_description)->toBe('Updated SEO description.')
        ->and($newHomepage->show_expertise_section)->toBeFalse()
        ->and($newHomepage->show_experience_section)->toBeTrue()
        ->and($newHomepage->github_url)->toBe('https://github.com/andrewbielecki')
        ->and((int) $activeExpertise->pivot->sort_order)->toBe(2)
        ->and((bool) $activeExpertise->pivot->is_active)->toBeTrue()
        ->and((bool) $inactiveAssignedExpertise->pivot->is_active)->toBeFalse()
        ->and($assignedProject->url)->toBe('https://showmyrides.com')
        ->and((bool) $inactiveExperience->pivot->is_active)->toBeFalse();
});

test('authenticated admins can save a homepage without contact description text', function (): void {
    $user = User::factory()->create();
    $homepage = Homepage::factory()->create();

    $response = $this->actingAs($user)->put(route('admin.homepage.update', $homepage), [
        'name' => 'No contact description',
        'hero_headline' => 'Hero headline',
        'hero_title' => 'Hero title',
        'hero_description' => 'Hero description.',
        'expertise_headline' => 'Expertise headline',
        'expertise_title' => 'Expertise title',
        'projects_headline' => 'Projects headline',
        'projects_title' => 'Projects title',
        'projects_description' => 'Projects description.',
        'experience_headline' => 'Experience headline',
        'experience_title' => 'Experience title',
        'experience_description' => 'Experience description.',
        'contact_headline' => 'Contact headline',
        'contact_title' => 'Contact title',
        'contact_description' => null,
        'github_url' => 'https://github.com/andrewbielecki',
        'linkedin_url' => 'https://www.linkedin.com/in/andrewbielecki',
    ]);

    $newHomepage = Homepage::query()
        ->where('name', 'No contact description')
        ->firstOrFail();

    $response
        ->assertRedirect(route('admin.homepage.edit', $newHomepage))
        ->assertSessionHas('status', 'Homepage saved as a new draft version.');

    expect($newHomepage->contact_description)->toBeNull();
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

test('authenticated admins can duplicate a homepage version with assignments', function (): void {
    $user = User::factory()->create();
    $homepage = Homepage::factory()->active()->create([
        'name' => 'Original version',
        'meta_title' => 'Original SEO title',
        'meta_description' => 'Original SEO description.',
        'show_expertise_section' => false,
        'show_experience_section' => true,
    ]);
    $expertise = HomepageExpertiseCard::factory()->create([
        'title' => 'Copied expertise',
    ]);
    $project = HomepageProject::factory()->create([
        'title' => 'Copied project',
        'url' => 'https://showmyrides.com',
    ]);
    $experience = HomepageExperience::factory()->create([
        'title' => 'Copied experience',
    ]);

    $homepage->expertiseCards()->attach($expertise, ['sort_order' => 2, 'is_active' => true]);
    $homepage->projects()->attach($project, ['sort_order' => 1, 'is_active' => true]);
    $homepage->experiences()->attach($experience, ['sort_order' => 3, 'is_active' => false]);

    $response = $this->actingAs($user)->post(route('admin.homepage.duplicate', $homepage));

    $clone = Homepage::query()->where('name', 'Original version copy')->firstOrFail();
    $copiedExperience = $clone->experiences()->whereKey($experience->id)->firstOrFail();

    $response
        ->assertRedirect(route('admin.homepage.edit', $clone))
        ->assertSessionHas('status', 'Homepage version duplicated.');

    expect($clone->is_active)->toBeFalse()
        ->and($clone->meta_title)->toBe('Original SEO title')
        ->and($clone->meta_description)->toBe('Original SEO description.')
        ->and($clone->show_expertise_section)->toBeFalse()
        ->and($clone->show_experience_section)->toBeTrue()
        ->and($clone->expertiseCards()->whereKey($expertise->id)->exists())->toBeTrue()
        ->and($clone->projects()->whereKey($project->id)->exists())->toBeTrue()
        ->and($clone->projects()->where('url', 'https://showmyrides.com')->exists())->toBeTrue()
        ->and((int) $copiedExperience->pivot->sort_order)->toBe(3)
        ->and((bool) $copiedExperience->pivot->is_active)->toBeFalse()
        ->and(HomepageProject::query()->where('title', 'Copied project')->count())->toBe(1);
});

test('authenticated admins can delete inactive homepage versions only', function (): void {
    $user = User::factory()->create();
    $active = Homepage::factory()->active()->create();
    $draft = Homepage::factory()->create();
    $project = HomepageProject::factory()->create();
    $draft->projects()->attach($project, ['sort_order' => 1, 'is_active' => true]);

    $activeResponse = $this->actingAs($user)->delete(route('admin.homepage.destroy', $active));

    $activeResponse
        ->assertRedirect(route('admin.homepage.index'))
        ->assertSessionHas('status', 'The active homepage version cannot be deleted. Activate another version first.');

    expect($active->fresh())->not->toBeNull();

    $draftResponse = $this->actingAs($user)->delete(route('admin.homepage.destroy', $draft));

    $draftResponse
        ->assertRedirect(route('admin.homepage.index'))
        ->assertSessionHas('status', 'Homepage version deleted.');

    expect($draft->fresh())->toBeNull()
        ->and($project->fresh())->not->toBeNull();
});

test('authenticated admins can manage reusable homepage entities', function (): void {
    $user = User::factory()->create();
    $image = Image::factory()->create();

    $projectResponse = $this->actingAs($user)->post(route('admin.projects.store'), [
        'image_id' => $image->id,
        'title' => 'ShowMyRides',
        'url' => 'https://showmyrides.com',
        'description' => 'Vehicle showcase project.',
    ]);
    $project = HomepageProject::query()->where('title', 'ShowMyRides')->firstOrFail();

    $projectResponse
        ->assertRedirect(route('admin.projects.edit', $project))
        ->assertSessionHas('status', 'Project created.');

    $this->actingAs($user)
        ->put(route('admin.projects.update', $project), [
            'image_id' => null,
            'title' => 'ShowMyRides updated',
            'url' => 'https://showmyrides.com',
            'description' => 'Updated project description.',
        ])
        ->assertRedirect(route('admin.projects.edit', $project))
        ->assertSessionHas('status', 'Project updated.');

    $expertiseResponse = $this->actingAs($user)->post(route('admin.expertise.store'), [
        'title' => 'Backend architecture',
        'description' => 'Reusable expertise card.',
    ]);
    $expertise = HomepageExpertiseCard::query()->where('title', 'Backend architecture')->firstOrFail();

    $expertiseResponse
        ->assertRedirect(route('admin.expertise.edit', $expertise))
        ->assertSessionHas('status', 'Expertise card created.');

    $experienceResponse = $this->actingAs($user)->post(route('admin.experiences.store'), [
        'title' => 'Lead engineering',
        'description' => 'Reusable experience card.',
    ]);
    $experience = HomepageExperience::query()->where('title', 'Lead engineering')->firstOrFail();

    $experienceResponse
        ->assertRedirect(route('admin.experiences.edit', $experience))
        ->assertSessionHas('status', 'Experience created.');

    expect($project->fresh()->title)->toBe('ShowMyRides updated')
        ->and($expertise->fresh())->not->toBeNull()
        ->and($experience->fresh())->not->toBeNull();
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
