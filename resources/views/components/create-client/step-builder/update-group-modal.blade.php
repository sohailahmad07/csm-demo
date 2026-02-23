<flux:modal name="update-group" {{ $attributes }}>
    <div class="space-y-6">
        <flux:heading size="lg">
            Update Group Name
        </flux:heading>
        <flux:field>
            <flux:label>Group Name</flux:label>
            <flux:input x-ref="name" wire:model="groupName" placeholder="Your group name"/>
            <flux:text class="text-red-600" data-error></flux:text>
        </flux:field>
        <div class="flex gap-x-2">
            <flux:spacer/>
            <flux:modal.close>
                <flux:button>Close</flux:button>
            </flux:modal.close>
            <flux:button @click="$js.updateGroup($refs.name)" variant="primary">
                Update group
            </flux:button>
        </div>
    </div>
</flux:modal>
