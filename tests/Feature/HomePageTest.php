<?php

declare(strict_types=1);

use App\Models\Homepage;
use App\Models\HomepageExperience;
use App\Models\HomepageExpertiseCard;
use App\Models\HomepageProject;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the homepage returns a successful response with default content when no version exists', function (): void {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Andrew Bielecki | Lead Software Engineer')
        ->assertSee('Building useful software, keeping teams moving, and making room for side projects.')
        ->assertSee('Engineering judgment for teams that need momentum and maintainability.')
        ->assertSee('Half-finished ideas, useful experiments, and a few things I keep coming back to.')
        ->assertSee('DiveLogRepeat')
        ->assertSee('Professional experience still does the heavy lifting.')
        ->assertSee('Looking for a lead engineer who can bridge product, architecture, and delivery?')
        ->assertSee('href="https://github.com/andrewbielecki"', false)
        ->assertSee('href="https://www.linkedin.com/in/andrewbielecki"', false)
        ->assertSee('GitHub')
        ->assertSee('LinkedIn')
        ->assertSee('href="'.asset('favicon.ico').'"', false)
        ->assertSee('type="image/svg+xml" href="'.asset('favicon.svg').'"', false)
        ->assertSee('href="'.asset('apple-touch-icon.png').'"', false)
        ->assertSee('href="'.asset('site.webmanifest').'"', false)
        ->assertDontSee('Start a conversation')
        ->assertDontSee('View hobby projects')
        ->assertDontSee('href="#github-placeholder"', false)
        ->assertDontSee('href="#linkedin-placeholder"', false);
});

test('the homepage renders the active database version and hides inactive rows', function (): void {
    $homepage = Homepage::factory()->active()->create([
        'hero_headline' => 'Principal Laravel Engineer',
        'hero_title' => 'Database managed hero title',
        'hero_description' => 'Database managed hero description for the public homepage.',
        'expertise_headline' => 'Skills',
        'expertise_title' => 'Database managed expertise title',
        'projects_headline' => 'Projects',
        'projects_title' => 'Database managed projects title',
        'projects_description' => 'Database managed projects description.',
        'experience_headline' => 'Work',
        'experience_title' => 'Database managed experience title',
        'experience_description' => 'Database managed experience description.',
        'contact_headline' => 'Reach out',
        'contact_title' => 'Database managed contact title',
        'contact_description' => 'Database managed contact description.',
        'github_url' => 'https://github.com/andrewbielecki',
        'linkedin_url' => 'https://www.linkedin.com/in/andrewbielecki',
    ]);

    $activeExpertise = HomepageExpertiseCard::factory()->create([
        'title' => 'Active expertise card',
        'description' => 'Visible expertise description.',
    ]);
    $inactiveExpertise = HomepageExpertiseCard::factory()->create([
        'title' => 'Inactive expertise card',
    ]);
    $homepage->expertiseCards()->attach($activeExpertise, ['sort_order' => 1, 'is_active' => true]);
    $homepage->expertiseCards()->attach($inactiveExpertise, ['sort_order' => 2, 'is_active' => false]);

    $projectImage = Image::factory()->create([
        'alt_text' => 'Project screenshot alt text',
    ]);

    $activeProject = HomepageProject::factory()->create([
        'image_id' => $projectImage->id,
        'title' => 'Active hobby project',
        'url' => 'https://showmyrides.com',
        'description' => 'Visible hobby project description with <a href="https://showmyrides.com">ShowMyRides</a> link.',
    ]);
    $inactiveProject = HomepageProject::factory()->create([
        'title' => 'Inactive hobby project',
    ]);
    $homepage->projects()->attach($activeProject, ['sort_order' => 1, 'is_active' => true]);
    $homepage->projects()->attach($inactiveProject, ['sort_order' => 2, 'is_active' => false]);

    $activeExperience = HomepageExperience::factory()->create([
        'title' => 'Active experience card',
        'description' => 'Visible experience description.',
    ]);
    $inactiveExperience = HomepageExperience::factory()->create([
        'title' => 'Inactive experience card',
    ]);
    $homepage->experiences()->attach($activeExperience, ['sort_order' => 1, 'is_active' => true]);
    $homepage->experiences()->attach($inactiveExperience, ['sort_order' => 2, 'is_active' => false]);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Principal Laravel Engineer')
        ->assertSee('Database managed hero title')
        ->assertSee('Database managed expertise title')
        ->assertSee('Active expertise card')
        ->assertSee('<a class="link link-hover" href="https://showmyrides.com">Active hobby project</a>', false)
        ->assertSee('<a href="https://showmyrides.com">ShowMyRides</a>', false)
        ->assertSee('[&amp;_a]:text-blue-600', false)
        ->assertSee('text-base-content/85', false)
        ->assertSee('Project screenshot alt text')
        ->assertSee('Active experience card')
        ->assertSee('href="https://github.com/andrewbielecki"', false)
        ->assertSee('href="https://www.linkedin.com/in/andrewbielecki"', false)
        ->assertDontSee('text-primary', false)
        ->assertDontSee('text-base-content/70', false)
        ->assertDontSee('text-base-content/75', false)
        ->assertDontSee('Inactive expertise card')
        ->assertDontSee('Inactive hobby project')
        ->assertDontSee('Inactive experience card')
        ->assertDontSee('Start a conversation')
        ->assertDontSee('View hobby projects');
});

test('the homepage omits the contact description when it is empty', function (): void {
    Homepage::factory()->active()->create([
        'contact_title' => 'Contact without description',
        'contact_description' => null,
        'github_url' => 'https://github.com/andrewbielecki',
        'linkedin_url' => 'https://www.linkedin.com/in/andrewbielecki',
    ]);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Contact without description')
        ->assertSee('href="https://github.com/andrewbielecki"', false)
        ->assertSee('href="https://www.linkedin.com/in/andrewbielecki"', false)
        ->assertDontSee('text-neutral-content/75', false);
});

test('favicon assets exist for browser fallbacks', function (): void {
    foreach ([
        'favicon.ico',
        'favicon.svg',
        'favicon-16x16.png',
        'favicon-32x32.png',
        'apple-touch-icon.png',
        'android-chrome-192x192.png',
        'android-chrome-512x512.png',
        'site.webmanifest',
    ] as $path) {
        $this->assertFileExists(public_path($path));
    }
});
