<x-field-layout :field="$field" :required="$required">
    <input
        x-data="{
            error: @json($errors->has($field->name)),
            disabled: @json($field->isDisabled())
        }"
        @click="error = false"
        value="{{ $field->value }}"
        class="{{ 'w-full' . ' ' . $field->style() }}"
        :class="error ? '{{ 'ring-1 ring-red-500 ' }}' : ''"
        wire:model.lazy="{{ $field->wireModel }}"
        placeholder="{{ $field->placeholder }}"
        {{ ($field->isDisabled()) ? ' disabled' : '' }}
    />
    <div class="absolute bottom-0 -right-[29px]">
        @if($field->hasAnchor())
            <a href="{{ $field->anchor }}" >
                <div class="h-6 w-6 border border-slate-400 rounded-sm hover:shadow-md">
                    <svg class="w-full p-0.5" viewBox="0 0 24 24">
                        <path class="fill-slate-600" d="m12 3.75c-4.55635 0-8.25 3.69365-8.25 8.25 0 4.5563 3.69365 8.25 8.25 8.25 4.5563 0 8.25-3.6937 8.25-8.25 0-4.55635-3.6937-8.25-8.25-8.25zm-9.75 8.25c0-5.38478 4.36522-9.75 9.75-9.75 5.3848 0 9.75 4.36522 9.75 9.75 0 5.3848-4.3652 9.75-9.75 9.75-5.38478 0-9.75-4.3652-9.75-9.75zm9.75-.75c.4142 0 .75.3358.75.75v3.5c0 .4142-.3358.75-.75.75s-.75-.3358-.75-.75v-3.5c0-.4142.3358-.75.75-.75zm0-3.25c-.5523 0-1 .44772-1 1s.4477 1 1 1h.01c.5523 0 1-.44772 1-1s-.4477-1-1-1z" />
                    </svg>
                </div>
            </a>
        @endif
    </div>
</x-field-layout>
