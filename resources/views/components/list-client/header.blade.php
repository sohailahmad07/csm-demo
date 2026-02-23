<div class="mb-6 flex items-center justify-between">
    <div>
        <flux:heading size="xl">Client Onboarding</flux:heading>
        <flux:subheading>Track setup progress for all clients</flux:subheading>
    </div>
    <flux:button variant="primary" icon="plus" :href="route('clients.create')" wire:navigate>New Client</flux:button>
</div>
