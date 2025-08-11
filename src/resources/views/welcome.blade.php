@extends('layouts.app')

@section('content')

<div x-data class="min-h-screen bg-gray-50">

    {{-- Navbar SSR/Reactive --}}
    <livewire:ui.navbar />

    <main class="mx-auto max-w-5xl px-4 py-10">
        <h1 class="text-2xl font-bold">Welcome</h1>
        <p class="mt-2 text-gray-600">صفحهٔ فرود تست لاگین با دیالوگ</p>
    </main>

    <x-shared-kernel::dialog name="login-dialog" title="ورود با کد یکبارمصرف" maxWidth="max-w-md">
        <livewire:auth.login />
    </x-shared-kernel::dialog>
</div>
@endsection
