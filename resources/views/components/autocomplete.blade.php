@props(['name', 'label', 'items', 'selected', 'createRoute' => null, 'placeholder' => 'Selecione uma opção'])

<div x-data="{ open: false, search: '', selected: @js($selected), items: @js($items) }" class="relative">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="mt-1 relative">
        <input type="text"
            x-model="search"
            @focus="open = true"
            @click.away="open = false"
            placeholder="{{ $placeholder }}"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
        >
        <input type="hidden" name="{{ $name }}" x-model="selected" id="{{ $name }}">
    </div>

    <div x-show="open" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto max-h-60 focus:outline-none sm:text-sm">
        <template x-for="item in items.filter(i => i.name.toLowerCase().includes(search.toLowerCase()))" :key="item.id">
            <div class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white"
                @click="selected = item.id; search = item.name; open = false">
                <span class="font-normal block truncate" x-text="item.name"></span>
            </div>
        </template>

        @if($createRoute)
            <div class="cursor-pointer select-none relative py-2 pl-3 pr-9 bg-gray-100 hover:bg-gray-200"
                @click="window.location.href = '{{ $createRoute }}'">
                <span class="font-normal block truncate">+ Criar novo</span>
            </div>
        @endif
    </div>
</div> 