<?php

use App\Models\User;

test('a user can register', function () {
    $response = $this->post('/register', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('home'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
});

test('a user can log in', function () {
    $user = User::factory()->create(['password' => 'password']);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('home'));

    $this->assertAuthenticatedAs($user);
});

test('a user cannot log in with the wrong password', function () {
    $user = User::factory()->create(['password' => 'password']);

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect('/login');
    $response->assertSessionHasErrors('email');

    $this->assertGuest();
});

test('a user can log out', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect(route('login'));

    $this->assertGuest();
});

test('guests cannot view the wardrobe', function () {
    $this->get('/wardrobe')->assertRedirect(route('login'));
});

test('guests can view the login form', function () {
    $this->get('/login')->assertOk()->assertSee('Log in');
});

test('guests can view the sign up form', function () {
    $this->get('/register')->assertOk()->assertSee('Sign up');
});

test('the sidebar shows the authenticated users details', function () {
    $user = User::factory()->create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSee('Jane Doe')
        ->assertSee('jane@example.com');
});
