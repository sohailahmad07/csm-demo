<div class="max-w-xl" wire:show="step === 1" wire:cloak>
    <div class="mb-6">
        <flux:heading size="xl">New Client</flux:heading>
        <flux:subheading>Enter the agency's basic information to get started.</flux:subheading>
    </div>

    @island('generalInfo')
    <div class="space-y-5">
        <flux:input label="Name" wire:model="name" placeholder="e.g. Apex Recovery Group"/>

        <flux:input label="Contact Name" wire:model="contactName" placeholder="e.g. Sarah Chen"/>

        <flux:input type="email" label="Contact Email" wire:model="contactEmail"
                    placeholder="e.g. sarah@agency.com"/>

        <flux:select wire:model="plan" label="Plan">
            <flux:select.option value="starter">Starter</flux:select.option>
            <flux:select.option value="growth">Growth</flux:select.option>
            <flux:select.option value="enterprise">Enterprise</flux:select.option>
        </flux:select>

        <flux:input type="number" label="Monthly Collection Goal ($)" wire:model="monthlyGoal"
                    placeholder="e.g. 50000" min="0" step="1"/>

        <flux:input type="date" label="Go Live At" wire:model="goLiveAt"/>

        <div class="flex justify-end pt-2">
            <flux:button wire:click="nextStep" type="submit" variant="primary" icon-trailing="arrow-right">
                Next: Onboarding Steps
            </flux:button>
        </div>
    </div>
    @endisland
</div>
