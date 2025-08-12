<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <title>Admin - {{ $title ?? config('app.name') }}</title>
</head>
<body class="bg-slate-50">
  <header class="bg-white border-b p-3 flex justify-between">
    <div class="font-bold">Admin</div>
    <div>
      @auth('admin')
        <form method="POST" action="{{ route('auth.admin.logout') }}">
          @csrf
          <x-ui-button variant="secondary">Logout</x-ui-button>
        </form>
      @endauth
    </div>
  </header>
  <main class="container mx-auto p-4">
    {{ $slot }}
  </main>
</body>
</html>
