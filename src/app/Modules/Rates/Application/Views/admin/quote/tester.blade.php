<div class="p-4" wire:poll="noop">
    <h1 class="text-xl mb-4">تست محاسبه</h1>
    <form wire:submit.prevent="submit" class="space-y-2">
        <div>
            <label>Service</label>
            <input type="text" wire:model="serviceKey" class="border" />
        </div>
        <div>
            <label>Amount USD</label>
            <input type="number" step="0.01" wire:model="amountUsd" class="border" />
        </div>
        <button type="submit" class="bg-blue-500 text-white px-3 py-1">محاسبه</button>
    </form>
    @if($result)
        <pre class="mt-4">{{ json_encode($result, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
    @endif
</div>
