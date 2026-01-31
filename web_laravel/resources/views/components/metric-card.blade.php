@props(['label', 'value', 'color' => 'indigo'])

<div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm flex items-center gap-4 transition-all hover:shadow-md">
    <div class="p-3 rounded-full bg-{{ $color }}-100 text-{{ $color }}-600">
        {{ $slot }}
    </div>
    <div>
        <p class="text-sm text-slate-500 font-medium">{{ $label }}</p>
        <p class="text-2xl font-bold text-slate-800">{{ $value }}</p>
    </div>
</div>
