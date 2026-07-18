<?php

declare(strict_types=1);

test('the homepage returns a successful response', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('the homepage includes professional profile metadata and sections', function (): void {
    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSee('Andrew Bielecki | Lead Software Engineer')
        ->assertSee('Lead software engineer focused on useful software, steady delivery, and maintainable systems.')
        ->assertSee('Building useful software, keeping teams moving, and making room for side projects.')
        ->assertSee('Engineering judgment for teams that need momentum and maintainability.')
        ->assertSee('Half-finished ideas, useful experiments, and a few things I keep coming back to.')
        ->assertSee('DiveLogRepeat')
        ->assertSee('Placeholder screenshot for DiveLogRepeat')
        ->assertSee('Homebrew Helper')
        ->assertSee('Placeholder screenshot for Homebrew Helper')
        ->assertSee('Small tools')
        ->assertSee('Placeholder screenshot for Small tools')
        ->assertSee('Professional experience still does the heavy lifting.')
        ->assertSee('Looking for a lead engineer who can bridge product, architecture, and delivery?')
        ->assertSee('href="#github-placeholder"', false)
        ->assertSee('href="#linkedin-placeholder"', false);
});
