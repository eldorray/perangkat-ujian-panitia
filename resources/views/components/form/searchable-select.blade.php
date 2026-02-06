@props([
    'options' => [],
    'selected' => null,
    'placeholder' => '-- Pilih --',
    'searchPlaceholder' => 'Cari...',
    'color' => 'blue', // blue, emerald, etc.
])

@php
    $colorClasses = [
        'blue' => [
            'ring' => 'ring-blue-500 border-blue-500',
            'bg' => 'bg-blue-50 text-blue-700',
            'icon' => 'text-blue-600',
            'search' => 'focus:ring-blue-500',
        ],
        'emerald' => [
            'ring' => 'ring-emerald-500 border-emerald-500',
            'bg' => 'bg-emerald-50 text-emerald-700',
            'icon' => 'text-emerald-600',
            'search' => 'focus:ring-emerald-500',
        ],
    ];
    $colors = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<div x-data="{
    open: false,
    search: '',
    options: {{ json_encode($options) }},
    selected: {{ json_encode($selected) }},
    get filteredOptions() {
        if (!this.search) return this.options;
        return this.options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
    },
    get selectedLabel() {
        const sel = this.options.find(o => o.value == this.selected);
        return sel ? sel.label : '{{ $placeholder }}';
    },
    selectOption(opt) {
        this.selected = opt.value;
        $dispatch('select', { value: opt.value, label: opt.label });
        this.open = false;
        this.search = '';
    }
}" x-on:click.away="open = false; search = ''"
    {{ $attributes->merge(['class' => 'relative']) }}>
    <button type="button" x-on:click="open = !open"
        class="form-select w-full text-left flex items-center justify-between gap-2 text-sm"
        :class="{ 'ring-2 {{ $colors['ring'] }}': open }">
        <span x-text="selectedLabel" :class="{ 'text-gray-400': !selected }" class="truncate"></span>
        <svg class="w-4 h-4 text-gray-400 transition-transform shrink-0" :class="{ 'rotate-180': open }" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" x-transition
        class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden"
        style="display: none;">
        <div class="p-2 border-b border-gray-100">
            <input type="text" x-model="search" x-on:keydown.escape="open = false"
                placeholder="{{ $searchPlaceholder }}"
                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 {{ $colors['search'] }}">
        </div>
        <ul class="max-h-48 overflow-y-auto py-1">
            <template x-for="opt in filteredOptions" :key="opt.value">
                <li>
                    <button type="button" x-on:click="selectOption(opt)"
                        class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50 flex items-center justify-between"
                        :class="{ '{{ $colors['bg'] }}': selected == opt.value }">
                        <span x-text="opt.label"></span>
                        <svg x-show="selected == opt.value" class="w-4 h-4 {{ $colors['icon'] }}" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </button>
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-sm text-gray-400">
                Tidak ditemukan
            </li>
        </ul>
    </div>
</div>
