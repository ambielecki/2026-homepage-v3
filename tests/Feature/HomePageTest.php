<?php

declare(strict_types=1);

use App\Models\Homepage;
use App\Models\HomepageExperience;
use App\Models\HomepageExpertiseCard;
use App\Models\HomepageProject;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('the homepage returns a successful response with default content when no version exists', function (): void {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('<title>Andrew Bielecki | Lead Software Engineer</title>', false)
        ->assertSee('content="'.Homepage::defaultContent()->resolvedMetaDescription().'"', false)
        ->assertSee('property="og:title" content="Andrew Bielecki | Lead Software Engineer"', false)
        ->assertSee('name="twitter:card" content="summary_large_image"', false)
        ->assertSee('content="'.asset('social-card.png').'"', false)
        ->assertSee('property="og:image:width" content="1200"', false)
        ->assertSee('property="og:image:height" content="630"', false)
        ->assertSee('name="robots" content="noindex, nofollow"', false)
        ->assertSee('lg:col-span-2', false)
        ->assertSee('lg:grid-cols-[minmax(0,1fr)_minmax(20rem,0.8fr)]', false)
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

test('the homepage renders custom seo metadata when configured', function (): void {
    Homepage::factory()->active()->create([
        'hero_headline' => 'Fallback headline',
        'hero_description' => 'Fallback hero description should not be used for metadata.',
        'meta_title' => 'Custom homepage SEO title',
        'meta_description' => 'Custom homepage SEO description.',
    ]);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('<title>Custom homepage SEO title</title>', false)
        ->assertSee('name="description" content="Custom homepage SEO description."', false)
        ->assertSee('property="og:title" content="Custom homepage SEO title"', false)
        ->assertSee('property="og:description" content="Custom homepage SEO description."', false)
        ->assertSee('name="twitter:title" content="Custom homepage SEO title"', false)
        ->assertSee('name="twitter:description" content="Custom homepage SEO description."', false)
        ->assertDontSee('<title>Andrew Bielecki | Fallback headline</title>', false)
        ->assertDontSee('name="description" content="Fallback hero description should not be used for metadata."', false);
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
        ->assertSee('href="#expertise"', false)
        ->assertSee('href="#projects"', false)
        ->assertSee('href="#experience"', false)
        ->assertSeeInOrder([
            'href="#expertise"',
            'href="#projects"',
            'href="#experience"',
            'href="#contact"',
        ], false)
        ->assertSee('[&amp;_a]:text-blue-600', false)
        ->assertSee('text-base-content/85', false)
        ->assertSee('flex aspect-[4/3] w-full items-center justify-center overflow-hidden rounded-box border border-base-300 bg-base-200', false)
        ->assertSee('h-full w-full object-contain', false)
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

test('the homepage hides disabled optional sections for the active version', function (): void {
    $homepage = Homepage::factory()->active()->create([
        'show_expertise_section' => false,
        'show_experience_section' => false,
        'expertise_title' => 'Hidden expertise section title',
        'experience_title' => 'Hidden experience section title',
    ]);

    $expertise = HomepageExpertiseCard::factory()->create([
        'title' => 'Hidden expertise card',
        'description' => 'Hidden expertise card description.',
    ]);
    $experience = HomepageExperience::factory()->create([
        'title' => 'Hidden experience card',
        'description' => 'Hidden experience card description.',
    ]);

    $homepage->expertiseCards()->attach($expertise, ['sort_order' => 1, 'is_active' => true]);
    $homepage->experiences()->attach($experience, ['sort_order' => 1, 'is_active' => true]);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertDontSee('id="expertise"', false)
        ->assertDontSee('id="experience"', false)
        ->assertDontSee('href="#expertise"', false)
        ->assertDontSee('href="#experience"', false)
        ->assertDontSee('Hidden expertise section title')
        ->assertDontSee('Hidden expertise card')
        ->assertDontSee('Hidden experience section title')
        ->assertDontSee('Hidden experience card');
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

test('the homepage social image metadata uses the configured hero image', function (): void {
    $image = Image::factory()->create([
        'alt_text' => 'Portrait of Andrew Bielecki',
        'original_path' => 'images/hero/andrew.jpg',
        'width' => 1600,
        'height' => 900,
    ]);

    Homepage::factory()->active()->create([
        'hero_image_id' => $image->id,
    ]);

    $imageUrl = $image->originalUrl();
    $imageUrl = Str::startsWith($imageUrl, ['http://', 'https://']) ? $imageUrl : url($imageUrl);

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('property="og:image" content="'.$imageUrl.'"', false)
        ->assertSee('property="og:image:alt" content="Portrait of Andrew Bielecki"', false)
        ->assertSee('property="og:image:width" content="1600"', false)
        ->assertSee('property="og:image:height" content="900"', false)
        ->assertSee('name="twitter:image" content="'.$imageUrl.'"', false)
        ->assertSee('name="twitter:image:alt" content="Portrait of Andrew Bielecki"', false);
});

test('the production homepage allows indexing', function (): void {
    config(['app.env' => 'production']);

    $this->get('/')
        ->assertOk()
        ->assertSee('name="robots" content="index, follow"', false);
});

test('robots points crawlers to the homepage sitemap', function (): void {
    config(['app.url' => 'https://www.andrewbielecki.com']);

    $this->get('/robots.txt')
        ->assertOk()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('User-agent: *')
        ->assertSee('Disallow:')
        ->assertSee('Sitemap: https://www.andrewbielecki.com/sitemap.xml')
        ->assertDontSee('/login')
        ->assertDontSee('/admin');
});

test('the sitemap includes only the public homepage', function (): void {
    config(['app.url' => 'https://www.andrewbielecki.com']);

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee('<loc>https://www.andrewbielecki.com</loc>', false)
        ->assertDontSee('/login')
        ->assertDontSee('/admin')
        ->assertDontSee('/admin/homepage')
        ->assertDontSee('/preview');
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
