<div>
    <div wire:show="step === 2" wire:cloak>
        <div class="mb-6">
            <flux:heading size="xl">Onboarding Steps</flux:heading>
            <flux:subheading>Customise the steps for this client. Drag to reorder, edit names, or add/remove
                steps.
            </flux:subheading>
        </div>

        <x-create-client.step-builder.onboarding-steps/>

        <div class="mt-6 flex items-center justify-center">
            <flux:modal.trigger name="add-group">
                <flux:button wire:loading.attr="disabled" wire:target="save" variant="filled"
                             wire:click="groupName = ''" icon="plus" size="sm">
                    Add Group
                </flux:button>
            </flux:modal.trigger>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <flux:button wire:click="step = 1" wire:loading.attr="disabled" wire:target="save" variant="ghost"
                         icon="arrow-left">
                Back
            </flux:button>

            <flux:button wire:click="save" variant="primary">
                Create Client
            </flux:button>
        </div>
    </div>

    <x-create-client.step-builder.add-group-modal/>
    <x-create-client.step-builder.update-group-modal/>
    <x-create-client.step-builder.delete-group/>

</div>
