<?php

use App\Models\User;

test('unauthenticated users are redirected to login from home', function () {
    $this->get(route('home'))->assertRedirect(route('login'));
});

test('authenticated users are redirected to dashboard from home', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertRedirect(route('dashboard'));
});
