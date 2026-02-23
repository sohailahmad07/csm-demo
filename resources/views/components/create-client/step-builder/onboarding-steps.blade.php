<div x-data="{
                handleDragStart(e) {
                     const item = e.target.closest('[x-sort\\:item]');
                     if (!item || item.parentElement !== $el) return;
                     let text = item.getAttribute('data-group-name')
                     let ghostElement = `<div style='width: calc(100% - 18rem)' id='__drag_ghost' class='fixed -top-[9999px] h-9 rounded-md bg-zinc-100 px-2 py-1 text-sm dark:bg-zinc-700' {{ $attributes }}>
                        ${text}
                     </div>`;
                    document.body.insertAdjacentHTML('beforeend', ghostElement);
                    const ghost = document.getElementById('__drag_ghost');
                    e.dataTransfer.setDragImage(ghost, e.offsetX, 20);
                    requestAnimationFrame(() => ghost.remove());
                }
            }"
     x-sort.ghost="$js.handleGroupSort($item, $position)"
     x-sort:config="{
            handle: '[x-sort\\:handle]',
            onStart: function (evt) {
               $el.setAttribute('data-sorting-group', true)
            },
            onEnd: function (evt) {
               $el.removeAttribute('data-sorting-group')
            },
         }"
     x-on:dragstart="handleDragStart($event)"
     class="space-y-8 divide-y divide-zinc-200 dark:divide-white/10">
    <template x-for="groupName in $wire.groupOrder" :key="groupName">
        <div class="space-y-4" x-sort:item="groupName" x-bind:data-group-name="groupName">
            <x-create-client.step-builder.onboarding-step-header/>
            <x-create-client.step-builder.onboarding-step/>
            <div class="flex items-center justify-center pb-6 in-data-sorting-group:hidden">
                <flux:button wire:loading.attr="disabled" wire:target="save" @click="$js.addStep(groupName)"
                             variant="ghost" icon="plus" size="sm">
                    Add Step
                </flux:button>
            </div>
        </div>
    </template>
</div>
