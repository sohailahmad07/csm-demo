<?php

use App\Models\Client;
use App\Models\OnboardingStep;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to login from the client list', function () {
    $this->get(route('clients.index'))->assertRedirect(route('login'));
});

test('authenticated users can visit the client list', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('clients.index'))->assertOk();
});

test('client list shows client names', function () {
    $user = User::factory()->create();

    Client::factory()->create(['name' => 'Apex Recovery Group']);
    Client::factory()->create(['name' => 'Harbor Point Recovery']);

    $component = Livewire::actingAs($user)->test('pages::clients.list-client');
    $names = $component->get('clients')->getCollection()->pluck('name');

    expect($names)->toContain('Apex Recovery Group');
    expect($names)->toContain('Harbor Point Recovery');
});

test('status filter shows only matching clients', function () {
    $user = User::factory()->create();

    Client::factory()->active()->create(['name' => 'Active Agency']);
    Client::factory()->onboarding()->create(['name' => 'Onboarding Agency']);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.list-client')
        ->call('setStatusFilter', 'onboarding');

    $names = $component->get('clients')->getCollection()->pluck('name');

    expect($names)->toContain('Onboarding Agency');
    expect($names)->not->toContain('Active Agency');
});

test('status filter all shows all clients', function () {
    $user = User::factory()->create();

    Client::factory()->active()->create(['name' => 'Active Agency']);
    Client::factory()->onboarding()->create(['name' => 'Onboarding Agency']);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.list-client')
        ->call('setStatusFilter', 'all');

    $names = $component->get('clients')->getCollection()->pluck('name');

    expect($names)->toContain('Active Agency');
    expect($names)->toContain('Onboarding Agency');
});

test('onboarding progress percentage is calculated correctly', function () {
    $user = User::factory()->create();

    $client = Client::factory()->create(['name' => 'Progress Agency']);

    OnboardingStep::factory()->completed()->count(5)->create([
        'client_id' => $client->id,
        'group' => 'account_setup',
    ]);
    OnboardingStep::factory()->pending()->count(5)->create([
        'client_id' => $client->id,
        'group' => 'go_live',
    ]);

    $component = Livewire::actingAs($user)->test('pages::clients.list-client');
    $progressClient = $component->get('clients')->getCollection()->firstWhere('name', 'Progress Agency');

    // 5 of 10 = 50%
    expect($component->instance()->onboardingProgress($progressClient))->toBe(50);
});

test('search filters clients by name', function () {
    $user = User::factory()->create();

    Client::factory()->create(['name' => 'Apex Recovery Group']);
    Client::factory()->create(['name' => 'Harbor Point Recovery']);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.list-client')
        ->set('search', 'Apex');

    $names = $component->get('clients')->getCollection()->pluck('name');

    expect($names)->toContain('Apex Recovery Group');
    expect($names)->not->toContain('Harbor Point Recovery');
});

test('client list returns no results when no clients match the filter', function () {
    $user = User::factory()->create();

    Client::factory()->active()->create(['name' => 'Active Agency']);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.list-client')
        ->call('setStatusFilter', 'onboarding');

    expect($component->get('clients')->getCollection()->count())->toBe(0);
});
