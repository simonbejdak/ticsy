@if(!$hidden)
    <div {{ $attributes->merge(['class' => 'flex flex-row justify-end w-full h-full']) }}>
        {{ $slot }}
    </div>
@endif
