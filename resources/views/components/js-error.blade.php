<div role="alert" aria-live="polite" aria-atomic="true"
     x-data
     x-ref="error"
     {{ $attributes->class(['mt-3 text-sm font-medium text-red-500 dark:text-red-400']) }} data-flux-error>
    <span x-text="$wire.$errors.first($refs.error.getAttribute('name'))"></span>
    {{ $slot }}
</div>
