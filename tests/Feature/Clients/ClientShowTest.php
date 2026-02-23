<?php

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\OnboardingStep;
use App\Models\Payment;
use App\Models\User;
use Livewire\Livewire;

test('guests are redirected to login from the client show page', function () {
    $client = Client::factory()->create();

    $this->get(route('clients.show', $client))->assertRedirect(route('login'));
});

test('authenticated users can view the client detail page', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $this->actingAs($user)->get(route('clients.show', $client->id))->assertOk();
});

test('client detail shows name and contact info', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create([
        'name' => 'Apex Recovery Group',
        'contact_name' => 'Sarah Chen',
        'contact_email' => 'schen@apexrecovery.com',
    ]);

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->assertSee('Apex Recovery Group')
        ->assertSee('Sarah Chen')
        ->assertSee('schen@apexrecovery.com');
});

test('client detail shows the plan badge', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['plan' => 'enterprise']);

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->assertSee('Enterprise');
});

test('onboarding progress is calculated correctly', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    OnboardingStep::factory()->completed()->count(4)->create([
        'client_id' => $client->id,
        'group' => 'account_setup',
    ]);
    OnboardingStep::factory()->pending()->count(6)->create([
        'client_id' => $client->id,
        'group' => 'go_live',
    ]);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client]);

    // 4 of 10 = 40%
    expect($component->get('onboardingProgress'))->toBe(40);
});

test('total collected sums all completed payments', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    Payment::factory()->completed()->create(['client_id' => $client->id, 'amount' => 500, 'paid_at' => now()]);
    Payment::factory()->completed()->create(['client_id' => $client->id, 'amount' => 300, 'paid_at' => now()]);
    Payment::factory()->failed()->create(['client_id' => $client->id, 'amount' => 200]);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client]);

    expect($component->get('totalCollected'))->toBe(800.0);
});

test('this month collected sums only current month completed payments', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    Payment::factory()->completed()->create(['client_id' => $client->id, 'amount' => 400, 'paid_at' => now()]);
    Payment::factory()->completed()->create(['client_id' => $client->id, 'amount' => 100, 'paid_at' => now()->subMonth()]);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client]);

    expect($component->get('thisMonthCollected'))->toBe(400.0);
});

test('payment count returns total number of payments', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    Payment::factory()->count(3)->create(['client_id' => $client->id]);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client]);

    expect($component->get('paymentCount'))->toBe(3);
});

test('goal progress is calculated as a percentage of monthly goal', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['monthly_goal' => 10000]);

    Payment::factory()->completed()->create(['client_id' => $client->id, 'amount' => 5000, 'paid_at' => now()]);

    $component = Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client]);

    expect($component->get('goalProgress'))->toBe(50);
});

test('toggling an incomplete step marks it as completed', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $step = OnboardingStep::factory()->pending()->create([
        'client_id' => $client->id,
        'name' => 'Connect Bank',
        'group' => 'payment_configuration',
    ]);

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->call('toggleStep', $step->id);

    expect($step->fresh()->completed_at)->not->toBeNull();
});

test('toggling a completed step unmarks it', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();

    $step = OnboardingStep::factory()->completed()->create([
        'client_id' => $client->id,
        'name' => 'Done Step',
        'group' => 'account_setup',
    ]);

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->call('toggleStep', $step->id);

    expect($step->fresh()->completed_at)->toBeNull();
});

test('saving notes persists to the database', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create(['notes' => null]);

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->set('notes', 'Waiting on ACH approval from their bank.')
        ->call('saveNotes');

    expect($client->fresh()->notes)->toBe('Waiting on ACH approval from their bank.');
});

test('updating status to a valid value persists to the database', function () {
    $user = User::factory()->create();
    $client = Client::factory()->onboarding()->create();

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->call('updateStatus', 'active');

    expect($client->fresh()->status)->toBe(ClientStatus::Active);
});

test('updating status with an invalid value does not change the status', function () {
    $user = User::factory()->create();
    $client = Client::factory()->onboarding()->create();

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->call('updateStatus', 'invalid-status');

    expect($client->fresh()->status)->toBe(ClientStatus::Onboarding);
});

test('recent payments are scoped to the current client only', function () {
    $user = User::factory()->create();
    $client = Client::factory()->create();
    $otherClient = Client::factory()->create();

    Payment::factory()->completed()->create([
        'client_id' => $client->id,
        'debtor_name' => 'John Doe',
    ]);
    Payment::factory()->completed()->create([
        'client_id' => $otherClient->id,
        'debtor_name' => 'Jane Smith',
    ]);

    Livewire::actingAs($user)
        ->test('pages::clients.show-client', ['client' => $client])
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});
