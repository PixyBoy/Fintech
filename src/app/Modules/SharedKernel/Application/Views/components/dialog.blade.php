@props([
    'open' => false,
    'title' => null,
    'maxWidth' => 'max-w-md',
    'onClose' => 'close-dialog',
    'name' => 'dialog',
])

<div x-data="{ open: @js($open) }"
     x-on:open-dialog.window="if($event.detail?.name==='{{ $name }}'){ open = true }"
     x-on:close-dialog.window="if($event.detail?.name==='{{ $name }}'){ open = false }"
     x-cloak>
    <div x-show="open"
         class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="open" x-transition.opacity
             class="fixed inset-0 bg-black/40"></div>

        <div x-show="open" x-transition
             class="relative z-10 w-full {{ $maxWidth }} rounded-2xl bg-white p-5 shadow-xl">
            @if($title)
                <h3 class="mb-3 text-lg font-semibold text-gray-800">{{ $title }}</h3>
            @endif

            <button type="button"
                    class="absolute right-3 top-3 rounded-md p-1 text-gray-500 hover:bg-gray-100"
                    x-on:click="open=false; window.dispatchEvent(new CustomEvent('{{ $onClose }}',{detail:{name:'{{ $name }}'}}))">
                ✕
            </button>

            {{ $slot }}
        </div>
    </div>
</div>
