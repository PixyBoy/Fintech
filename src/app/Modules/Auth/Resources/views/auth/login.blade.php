@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <h1 class="text-xl mb-4">Login</h1>
    <form method="POST" action="{{ route('auth.request_otp') }}" class="mb-6">
        @csrf
        <label class="block mb-2">Phone</label>
        <input type="text" name="phone" class="border p-2 w-full" />
        @error('phone')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        <button class="mt-2 bg-blue-500 text-white px-4 py-2">Send OTP</button>
    </form>
    <form method="POST" action="{{ route('auth.verify_otp') }}">
        @csrf
        <label class="block mb-2">Phone</label>
        <input type="text" name="phone" class="border p-2 w-full" />
        <label class="block mb-2 mt-4">Code</label>
        <input type="text" name="code" class="border p-2 w-full" />
        @error('code')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        <button class="mt-2 bg-green-500 text-white px-4 py-2">Verify</button>
    </form>
</div>
@endsection
