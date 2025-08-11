<nav class="flex items-center justify-between border-b bg-white px-4 py-3">
    <a href="/" class="text-lg font-semibold">MyShop</a>

    <div class="flex items-center gap-3">
        @if($authed)
            <a href="/dashboard" class="rounded-lg bg-gray-900 px-3 py-1.5 text-sm text-white hover:bg-black">
                Dashboard
            </a>
            <button wire:click="logout"
                    class="rounded-lg bg-gray-100 px-3 py-1.5 text-sm text-gray-800 hover:bg-gray-200">
                Logout
            </button>
        @else
            <button
                x-on:click="window.dispatchEvent(new CustomEvent('open-dialog',{detail:{name:'login-dialog'}}))"
                class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm text-white hover:bg-blue-700">
                Login
            </button>
        @endif
    </div>
</nav>
