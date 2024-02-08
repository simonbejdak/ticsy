<x-field-layout :hidden="$hidden">
    <x-field-label :value="$label"/>
    @if($type === \App\Enums\FieldType::TEXT)
        <x-field-input
            class="p-2"
            :name="$name"
            :value="$value"
            :error="$errors->has($name)"
            :disabled="$disabled"
        />
    @elseif($type === \App\Enums\FieldType::BAR)
        <div class="flex flex-row relative justify-end h-full bg-red-700 rounded-md">
            <div class="bg-slate-800 h-full rounded-md" style="width: {{ $percentage }}%"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-white">{{ $value }}</div>
        </div>
    @elseif($type === \App\Enums\FieldType::SELECT)
        <x-field-select :disabled="$disabled" :name="$name" :error="$errors->has($name)">
            @if($blank)
                <x-field-option />
            @endif
            @foreach($value as $option)
                <x-field-option :value="$option['id']" :text="$option['name']"/>
            @endforeach
            <x-field-option :value="10" :text="'Whatever'"/>
        </x-field-select>
    @endif
</x-field-layout>
