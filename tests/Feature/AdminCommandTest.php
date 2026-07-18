<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('admin create command creates a user', function (): void {
    $this->artisan('admin:create')
        ->expectsQuestion('Name', 'Andrew Admin')
        ->expectsQuestion('Email', 'admin@example.com')
        ->expectsQuestion('Password', 'password')
        ->expectsQuestion('Confirm password', 'password')
        ->expectsOutput('Admin user created.')
        ->assertSuccessful();

    $user = User::query()->where('email', 'admin@example.com')->first();

    expect($user)->not->toBeNull()
        ->and($user?->name)->toBe('Andrew Admin')
        ->and(Hash::check('password', (string) $user?->password))->toBeTrue();
});

test('admin create command rejects duplicate emails', function (): void {
    User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    $this->artisan('admin:create')
        ->expectsQuestion('Name', 'Andrew Admin')
        ->expectsQuestion('Email', 'admin@example.com')
        ->expectsQuestion('Password', 'password')
        ->expectsQuestion('Confirm password', 'password')
        ->expectsOutput('The email has already been taken.')
        ->assertFailed();
});

test('admin create command rejects mismatched password confirmation', function (): void {
    $this->artisan('admin:create')
        ->expectsQuestion('Name', 'Andrew Admin')
        ->expectsQuestion('Email', 'admin@example.com')
        ->expectsQuestion('Password', 'password')
        ->expectsQuestion('Confirm password', 'different-password')
        ->expectsOutput('The password field confirmation does not match.')
        ->assertFailed();
});

test('admin reset password command updates a user password', function (): void {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'old-password',
    ]);

    $this->artisan('admin:reset-password')
        ->expectsQuestion('Email', 'admin@example.com')
        ->expectsQuestion('Password', 'new-password')
        ->expectsQuestion('Confirm password', 'new-password')
        ->expectsOutput('Admin password reset.')
        ->assertSuccessful();

    $user->refresh();

    expect(Hash::check('new-password', (string) $user->password))->toBeTrue();
});

test('admin reset password command fails for missing users', function (): void {
    $this->artisan('admin:reset-password')
        ->expectsQuestion('Email', 'missing@example.com')
        ->expectsOutput('No admin user was found for that email address.')
        ->assertFailed();
});
