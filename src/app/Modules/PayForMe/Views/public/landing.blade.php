<x-payforme::layouts.module>
    <div class="bg-white p-6 rounded shadow">
        <h1 class="text-2xl mb-4">Pay For Me</h1>
        <p>Request us to pay for a purchase on your behalf.</p>
        @if(session('request_code'))
            <div class="mt-4 text-green-600">Request Code: {{ session('request_code') }}</div>
        @endif
        <a href="{{ route('payforme.request.create') }}" class="text-blue-500">Create Request</a>
    </div>
</x-payforme::layouts.module>
