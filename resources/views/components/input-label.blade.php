@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-black text-[#1C1F2F]']) }}>
    {{ $value ?? $slot }}
</label>
