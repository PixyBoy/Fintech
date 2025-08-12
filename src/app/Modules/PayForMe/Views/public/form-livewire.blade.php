<x-payforme::layouts.module>
    <form wire:submit.prevent="submit" class="space-y-4">
        <div>
            <label>Target URL</label>
            <input type="url" wire:model="target_url" class="border p-2 w-full">
        </div>
        <div>
            <label>Amount (USD)</label>
            <input type="number" step="0.01" wire:model.debounce.500ms="amount_usd" class="border p-2 w-full">
        </div>
        <div>
            <label>Notes</label>
            <textarea wire:model="notes" class="border p-2 w-full"></textarea>
        </div>
        <div>
            <input type="file" wire:model="attachments" multiple>
        </div>
        @livewire('App\\Modules\\PayForMe\\Livewire\\Public\\QuoteWidget', ['amountUsd' => \$amount_usd])
        <button class="bg-blue-500 text-white px-4 py-2">Submit</button>
        @if($request_code)
            <div class="text-green-600 mt-2">Request Code: {{ $request_code }}</div>
        @endif
    </form>
</x-payforme::layouts.module>
