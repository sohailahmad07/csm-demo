<div {{ $attributes->class(['flex items-center justify-between py-1']) }}>
    <div class="flex items-center gap-2">
        <flux:icon.grip-vertical x-sort:handle
                                 class="size-4  cursor-grab text-zinc-400 dark:text-zinc-500"/>
        <flux:subheading x-text="groupName"></flux:subheading>
    </div>
    <div class="flex gap-2 items-center">
        <flux:button wire:loading.attr="disabled" wire:target="save"
                     @click="$js.openUpdateModal(groupName)" variant="ghost" icon="pencil-square">
            Edit Group Name
        </flux:button>
        <flux:button wire:loading.attr="disabled" wire:target="save"
                     @click="$js.deleteGroup(groupName)" variant="danger" icon="trash">
            Delete
        </flux:button>
    </div>
</div>
