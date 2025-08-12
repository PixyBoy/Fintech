<x-pay-for-me::layouts.module>
    <form action="{{ route('payforme.request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label>Target URL</label>
            <input type="url" name="target_url" class="border p-2 w-full" required>
        </div>
        <div>
            <label>Amount (USD)</label>
            <input type="number" step="0.01" name="amount_usd" class="border p-2 w-full" required>
        </div>
        <div>
            <label>Notes</label>
            <textarea name="notes" class="border p-2 w-full"></textarea>
        </div>
        <div>
            <label>Attachments</label>
            <input type="file" name="attachments[]" multiple class="border p-2 w-full">
        </div>
        <div>
            @livewire('App\\Modules\\PayForMe\\Livewire\\Public\\QuoteWidget')
        </div>
        <button class="bg-blue-500 text-white px-4 py-2">Submit</button>
    </form>
</x-pay-for-me::layouts.module>
