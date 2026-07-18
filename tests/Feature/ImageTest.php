<?php

declare(strict_types=1);

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('image admin routes require authentication', function (): void {
    $this->get('/admin/images')->assertRedirect('/login');
    $this->get('/admin/images/create')->assertRedirect('/login');
});

test('authenticated admins can view the image list', function (): void {
    Storage::fake('public');
    $user = User::factory()->create();
    $image = Image::factory()->create([
        'description' => 'Homepage header image',
        'alt_text' => 'Blue geometric header',
    ]);

    Storage::disk('public')->put($image->original_path, 'image-content');

    $response = $this->actingAs($user)->get('/admin/images');

    $response
        ->assertOk()
        ->assertSee('Images')
        ->assertSee('Homepage header image')
        ->assertSee('Blue geometric header')
        ->assertSee('Edit');
});

test('authenticated admins can view the upload form', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/images/create');

    $response
        ->assertOk()
        ->assertSee('Upload image')
        ->assertSee('name="image"', false)
        ->assertSee('name="alt_text"', false)
        ->assertSee('name="description"', false)
        ->assertSee('name="is_header"', false);
});

test('authenticated admins can upload an image and generated sizes are processed', function (): void {
    Storage::fake('public');
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/admin/images', [
        'image' => UploadedFile::fake()->image('homepage-header.jpg', 1600, 900),
        'description' => 'A homepage header image.',
        'alt_text' => 'Abstract blue homepage header',
        'is_header' => '1',
    ]);

    $response
        ->assertRedirect('/admin/images')
        ->assertSessionHas('status', 'Image uploaded.');

    $image = Image::query()->firstOrFail();

    expect($image->description)->toBe('A homepage header image.')
        ->and($image->alt_text)->toBe('Abstract blue homepage header')
        ->and($image->is_header)->toBeTrue()
        ->and($image->has_sizes)->toBeTrue()
        ->and($image->original_extension)->toBe('jpg')
        ->and($image->mime_type)->toBe('image/jpeg')
        ->and($image->width)->toBe(1600)
        ->and($image->height)->toBe(900);

    Storage::disk('public')->assertExists($image->original_path);
    Storage::disk('public')->assertExists($image->sizedPath('small'));
    Storage::disk('public')->assertExists($image->sizedPath('medium'));
    Storage::disk('public')->assertExists($image->sizedPath('large'));
});

test('image uploads require an image file and alt text', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->from('/admin/images/create')->post('/admin/images', [
        'description' => 'Missing file and alt text.',
    ]);

    $response
        ->assertRedirect('/admin/images/create')
        ->assertSessionHasErrors(['image', 'alt_text']);
});

test('authenticated admins can edit image metadata', function (): void {
    Storage::fake('public');
    $user = User::factory()->create();
    $image = Image::factory()->create([
        'description' => 'Old description',
        'alt_text' => 'Old alt text',
        'is_header' => false,
    ]);

    Storage::disk('public')->put($image->original_path, 'image-content');

    $response = $this->actingAs($user)->put(sprintf('/admin/images/%s', $image->id), [
        'description' => 'Updated image description',
        'alt_text' => 'Updated alt text',
        'is_header' => '1',
    ]);

    $response
        ->assertRedirect('/admin/images')
        ->assertSessionHas('status', 'Image updated.');

    $image->refresh();

    expect($image->description)->toBe('Updated image description')
        ->and($image->alt_text)->toBe('Updated alt text')
        ->and($image->is_header)->toBeTrue();
});

test('the image processing command fails clearly for missing records', function (): void {
    $this->artisan('images:process', ['image' => 999])
        ->expectsOutput('Image not found.')
        ->assertFailed();
});
