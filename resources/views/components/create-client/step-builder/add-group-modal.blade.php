<flux:modal name="add-group" {{ $attributes }}>
    <div class="space-y-6">
        <flux:heading size="lg">
            Add New Group
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
            <flux:button @click="$js.addGroup($refs.name)" variant="primary">
                Add group
            </flux:button>
        </div>
    </div>
</flux:modal>
