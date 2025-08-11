@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <h1 class="text-xl mb-4">KYC Level 1</h1>
    <form method="POST" action="{{ route('kyc.l1.submit') }}">
        @csrf
        <label class="block mb-2">Name</label>
        <input type="text" name="name" class="border p-2 w-full" />
        <label class="block mb-2 mt-4">Family</label>
        <input type="text" name="family" class="border p-2 w-full" />
        <label class="block mb-2 mt-4">National Code</label>
        <input type="text" name="national_code" class="border p-2 w-full" />
        <button class="mt-4 bg-blue-500 text-white px-4 py-2">Submit</button>
    </form>
</div>
@endsection
