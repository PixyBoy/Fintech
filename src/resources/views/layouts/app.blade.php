<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
    @livewireScripts
</head>

<body class="bg-gray-100" dir="rtl">
    <div class="min-h-screen">
        @yield('content')
    </div>
    <div x-data="{ items: [] }"
        x-on:toast.window="items.push({ id: Date.now(), message: $event.detail.message, type: $event.detail.type || 'success' });
                        setTimeout(()=>items.shift(), 2500);"
        class="fixed right-4 top-4 z-[100] space-y-2">
        <template x-for="t in items" :key="t.id">
            <div x-transition class="min-w-[240px] rounded-lg border bg-white px-3 py-2 text-sm shadow">
                <span x-text="t.message"></span>
            </div>
        </template>
    </div>
</body>

</html>
