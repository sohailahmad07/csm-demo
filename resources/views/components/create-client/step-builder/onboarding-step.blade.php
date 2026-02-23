<div x-sort="$js.handleSort($item, $position, groupName)"
     x-sort:config="{ handle: '[x-sort\\:handle]' }"
     x-sort:group="stepsGroup" {{ $attributes }}>
    <template x-for="(step, index) in $wire.steps[groupName]" :key="step.id">
        <div class="grid grid-cols-[2rem_1fr_1fr_2.5rem] gap-y-2 gap-3 group in-data-sorting-group:hidden"
             x-sort:item="step.id">
            <div x-sort:handle
                 class="mt-2 flex cursor-grab justify-center text-zinc-400 hover:text-zinc-600 active:cursor-grabbing dark:text-zinc-500 dark:hover:text-zinc-300">
                <flux:icon.grip-vertical class="size-4"/>
            </div>
            <flux:field>
                <flux:label>Name</flux:label>
                <flux:input x-model="$wire.steps[groupName][index].name"
                            x-bind:data-invalid="$wire.$errors.has('steps.' + groupName + '.' + index + '.name')"
                            wire:loading.attr="disabled" wire:target="save"
                            placeholder="Step name"/>
                <x-js-error x-bind:name="'steps.' + groupName + '.' + index + '.name'"/>
            </flux:field>
            <flux:field>
                <flux:label>Due Date</flux:label>
                <flux:input type="date" x-model="$wire.steps[groupName][index].due_at"
                            wire:loading.attr="disabled" wire:target="save"
                            x-bind:data-invalid="$wire.$errors.has('steps.' + groupName + '.' + index + '.due_at')"/>
                <x-js-error x-bind:name="'steps.' + groupName + '.' + index + '.due_at'"/>
            </flux:field>
            <div class="flex items-center justify-center">
                <flux:button size="sm" variant="ghost" icon="trash"
                             wire:loading.attr="disabled" wire:target="save"
                             @click="$js.removeStep(step.id, groupName)"/>
            </div>
        </div>
    </template>
</div>
