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

    HomepageExpertiseCard::factory()->for($homepage)->create([
        'title' => 'Active expertise card',
        'description' => 'Visible expertise description.',
        'sort_order' => 1,
        'is_active' => true,
    ]);
    HomepageExpertiseCard::factory()->for($homepage)->create([
        'title' => 'Inactive expertise card',
        'is_active' => false,
    ]);

    $projectImage = Image::factory()->create([
        'alt_text' => 'Project screenshot alt text',
    ]);

    HomepageProject::factory()->for($homepage)->create([
        'image_id' => $projectImage->id,
        'title' => 'Active hobby project',
        'description' => 'Visible hobby project description.',
        'sort_order' => 1,
        'is_active' => true,
    ]);
    HomepageProject::factory()->for($homepage)->create([
        'title' => 'Inactive hobby project',
        'is_active' => false,
    ]);

    HomepageExperience::factory()->for($homepage)->create([
        'title' => 'Active experience card',
        'description' => 'Visible experience description.',
        'sort_order' => 1,
        'is_active' => true,
    ]);
    HomepageExperience::factory()->for($homepage)->create([
        'title' => 'Inactive experience card',
        'is_active' => false,
    ]);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Principal Laravel Engineer')
        ->assertSee('Database managed hero title')
        ->assertSee('Database managed expertise title')
        ->assertSee('Active expertise card')
        ->assertSee('Active hobby project')
        ->assertSee('Project screenshot alt text')
        ->assertSee('Active experience card')
        ->assertSee('href="https://github.com/andrewbielecki"', false)
        ->assertSee('href="https://www.linkedin.com/in/andrewbielecki"', false)
        ->assertDontSee('Inactive expertise card')
        ->assertDontSee('Inactive hobby project')
        ->assertDontSee('Inactive experience card')
        ->assertDontSee('Start a conversation')
        ->assertDontSee('View hobby projects');
});
