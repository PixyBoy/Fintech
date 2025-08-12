<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <title>{{ $title ?? config('app.name') }}</title>
</head>
<body class="bg-gray-50 text-gray-900">
  @include('site::partials.navbar')
  <main class="container mx-auto p-4">
    {{ $slot }}
  </main>

  {{-- مودال لاگین کاربر (OTP) - از ماژول Auth لود می‌شود --}}
  @livewire('auth.public.login-dialog')
</body>
</html>
