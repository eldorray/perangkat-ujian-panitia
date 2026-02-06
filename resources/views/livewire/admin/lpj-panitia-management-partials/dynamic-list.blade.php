{{-- Dynamic list partial for LPJ form --}}
{{-- Usage: @include('...dynamic-list', ['field' => 'fieldName', 'label' => 'Label', 'placeholder' => '...', 'numbering' => 'number|alpha']) --}}
<div>
    <div class="flex items-center justify-between mb-2">
        <label class="block text-sm font-medium">{{ $label }}</label>
        <button type="button" wire:click="addRow('{{ $field }}')"
            class="text-sm text-blue-600 hover:text-blue-800">+ Tambah Poin</button>
    </div>
    <div class="space-y-2">
        @foreach ($this->{$field} as $index => $item)
            <div class="flex items-start gap-2">
                <span class="text-sm text-gray-500 mt-2 w-6 flex-shrink-0">
                    @if (($numbering ?? 'number') === 'alpha')
                        {{ chr(97 + $index) }}.
                    @else
                        {{ $index + 1 }}.
                    @endif
                </span>
                <textarea wire:model="{{ $field }}.{{ $index }}" class="input w-full" rows="2"
                    placeholder="{{ $placeholder ?? '' }}"></textarea>
                @if (count($this->{$field}) > 1)
                    <button type="button" wire:click="removeRow('{{ $field }}', {{ $index }})"
                        class="text-red-500 hover:text-red-700 mt-2 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                @endif
            </div>
        @endforeach
    </div>
</div>
