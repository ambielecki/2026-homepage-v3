<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the login page renders with csrf protection', function (): void {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSee('Login')
        ->assertDontSee('Sign in to manage homepage content.')
        ->assertDontSee('This area is restricted to administrator accounts created from the command line.')
        ->assertSee('name="_token"', false);
});

test('an admin can log in with email and password', function (): void {
    $user = User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin');
    $this->assertAuthenticatedAs($user);
});

test('invalid login credentials return validation errors', function (): void {
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => 'password',
    ]);

    $response = $this->from('/login')->post('/login', [
        'email' => 'admin@example.com',
        'password' => 'wrong-password',
    ]);

    $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('admin routes redirect guests to login', function (): void {
    $response = $this->get('/admin');

    $response->assertRedirect('/login');
});

test('authenticated admins can view the dashboard', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin');

    $response
        ->assertOk()
        ->assertSee('Homepage dashboard')
        ->assertSee('href="/"', false)
        ->assertSee('Live')
        ->assertSee('Logout');
});

test('authenticated admins are redirected away from login', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/login');

    $response->assertRedirect('/admin');
});

test('admins can log out', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/login');
    $this->assertGuest();
});

test('registration and web password reset routes are not registered', function (): void {
    $this->get('/register')->assertNotFound();
    $this->get('/forgot-password')->assertNotFound();
});
