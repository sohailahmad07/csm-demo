<flux:modal name="delete-group" {{ $attributes->class(['min-w-88']) }}>
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Delete Group?</flux:heading>
            <flux:text class="mt-2">
                You're about to delete this group and all item inside it.<br>
                This action cannot be reversed.
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer/>
            <flux:modal.close>
                <flux:button variant="ghost">Cancel</flux:button>
            </flux:modal.close>
            <flux:button id="confirm" variant="danger">Delete Group</flux:button>
        </div>
    </div>
</flux:modal>
