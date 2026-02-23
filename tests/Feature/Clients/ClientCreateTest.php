<?php

use App\Enums\ClientPlan;
use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to login from the create client page', function () {
    $this->get(route('clients.create'))->assertRedirect(route('login'));
});

test('authenticated users can visit the create client page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('clients.create'))->assertOk();
});

test('step 1 validates required fields', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::clients.create-client')
        ->call('nextStep')
        ->assertHasErrors(['name', 'contactName', 'contactEmail', 'monthlyGoal', 'goLiveAt']);
});

test('step 1 validates email format', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::clients.create-client')
        ->set('name', 'Test Agency')
        ->set('contactName', 'Test Person')
        ->set('contactEmail', 'not-an-email')
        ->set('monthlyGoal', '10000')
        ->set('goLiveAt', now()->addDays(30)->format('Y-m-d'))
        ->call('nextStep')
        ->assertHasErrors(['contactEmail' => 'email']);
});

test('step 1 validates go live date must be in the future', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::clients.create-client')
        ->set('name', 'Test Agency')
        ->set('contactName', 'Test Person')
        ->set('contactEmail', 'test@agency.com')
        ->set('plan', 'starter')
        ->set('monthlyGoal', '10000')
        ->set('goLiveAt', now()->subDay()->format('Y-m-d'))
        ->call('nextStep')
        ->assertHasErrors(['goLiveAt']);
});

test('step 1 advances to step 2 when all fields are valid', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::clients.create-client')
        ->set('name', 'New Test Agency')
        ->set('contactName', 'Jane Doe')
        ->set('contactEmail', 'jane@testagency.com')
        ->set('plan', 'growth')
        ->set('monthlyGoal', '50000')
        ->set('goLiveAt', now()->addDays(30)->format('Y-m-d'))
        ->call('nextStep');

    expect($component->get('step'))->toBe(2);
});

test('mounts with 10 pre-populated onboarding steps', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)->test('pages::clients.create-client');
    $allSteps = collect($component->get('steps'))->flatten(1);

    expect($allSteps)->toHaveCount(10);
});

test('save validates step names are required', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)->test('pages::clients.create-client');
    $steps = json_decode(json_encode($component->get('steps')), true);
    $firstGroupName = array_key_first($steps);
    $steps[$firstGroupName][0]['name'] = '';

    $component
        ->set('name', 'New Agency')
        ->set('contactName', 'Jane Doe')
        ->set('contactEmail', 'jane@test.com')
        ->set('plan', 'starter')
        ->set('monthlyGoal', '50000')
        ->set('goLiveAt', now()->addDays(10)->format('Y-m-d'))
        ->set('steps', $steps)
        ->call('save')
        ->assertHasErrors(['steps.'.$firstGroupName.'.0.name']);
});

test('save creates the client and onboarding steps in the database', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)->test('pages::clients.create-client');

    $component
        ->set('name', 'New Test Agency')
        ->set('contactName', 'Jane Doe')
        ->set('contactEmail', 'jane@testagency.com')
        ->set('plan', 'growth')
        ->set('monthlyGoal', '50000')
        ->set('goLiveAt', now()->addDays(30)->format('Y-m-d'))
        ->call('save');

    $client = Client::where('name', 'New Test Agency')->firstOrFail();

    expect($client->contact_name)->toBe('Jane Doe');
    expect($client->plan)->toBe(ClientPlan::Growth);
    expect($client->status)->toBe(ClientStatus::Onboarding);
    expect($client->onboardingSteps()->count())->toBe(10);
});

test('save redirects to the client show page', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::clients.create-client')
        ->set('name', 'Redirect Test Agency')
        ->set('contactName', 'Jane Doe')
        ->set('contactEmail', 'jane@test.com')
        ->set('plan', 'starter')
        ->set('monthlyGoal', '10000')
        ->set('goLiveAt', now()->addDays(30)->format('Y-m-d'))
        ->call('save');

    $client = Client::where('name', 'Redirect Test Agency')->firstOrFail();

    $component->assertRedirect(route('clients.show', $client));
});
