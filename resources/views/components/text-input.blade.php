@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-2xl border-[#513CC7]/10 bg-[#513CC7]/5 px-4 py-3 text-[#1C1F2F] shadow-sm transition placeholder:text-[#1C1F2F]/45 focus:border-[#513CC7] focus:ring-[#513CC7] disabled:opacity-60']) }}>
