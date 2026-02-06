@props([
    'options' => [],
    'value' => '',
    'placeholder' => '-- Pilih --',
    'searchPlaceholder' => 'Cari...',
    'emptyMessage' => 'Tidak ada data',
    'disabled' => false,
])

<div x-data="{
    open: false,
    search: '',
    value: @entangle($attributes->wire('model')),
    options: {{ json_encode($options) }},
    placeholder: '{{ $placeholder }}',
    get filteredOptions() {
        if (!this.search) return this.options;
        return this.options.filter(option =>
            option.label.toLowerCase().includes(this.search.toLowerCase())
        );
    },
    get selectedLabel() {
        const selected = this.options.find(opt => opt.value == this.value);
        return selected ? selected.label : this.placeholder;
    },
    selectOption(option) {
        this.value = option.value;
        this.open = false;
        this.search = '';
    },
    clear() {
        this.value = '';
        this.search = '';
    }
}" x-on:click.away="open = false; search = ''" class="relative"
    {{ $disabled ? 'x-bind:class="opacity-50 pointer-events-none"' : '' }}>
    {{-- Trigger Button --}}
    <button type="button" x-on:click="open = !open"
        class="form-select w-full text-left flex items-center justify-between gap-2 {{ $attributes->get('class') }}"
        :class="{ 'ring-2 ring-blue-500 border-blue-500': open }" {{ $disabled ? 'disabled' : '' }}>
        <span x-text="selectedLabel" :class="{ 'text-gray-400': !value }" class="truncate"></span>
        <div class="flex items-center gap-1">
            <button type="button" x-show="value" x-on:click.stop="clear()"
                class="text-gray-400 hover:text-gray-600 p-0.5 -m-0.5 rounded">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
            <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-50 mt-1 w-full bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden"
        style="display: none;">
        {{-- Search Input --}}
        <div class="p-2 border-b border-gray-100">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" x-model="search" x-ref="searchInput" x-on:keydown.escape="open = false"
                    placeholder="{{ $searchPlaceholder }}"
                    class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        {{-- Options List --}}
        <ul class="max-h-48 overflow-y-auto py-1">
            <template x-for="option in filteredOptions" :key="option.value">
                <li>
                    <button type="button" x-on:click="selectOption(option)"
                        class="w-full px-3 py-2 text-left text-sm hover:bg-gray-50 flex items-center justify-between transition-colors"
                        :class="{ 'bg-blue-50 text-blue-700': value == option.value }">
                        <span x-text="option.label"></span>
                        <svg x-show="value == option.value" class="w-4 h-4 text-blue-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </button>
                </li>
            </template>
            <li x-show="filteredOptions.length === 0" class="px-3 py-4 text-center text-sm text-gray-400">
                {{ $emptyMessage }}
            </li>
        </ul>
    </div>
</div>
