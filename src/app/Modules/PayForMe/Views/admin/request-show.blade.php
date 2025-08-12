<div>
    <h2 class="text-xl mb-4">Request {{ $request->request_code }}</h2>
    <div>Status: {{ $request->status }}</div>
    <div class="mt-4">
        <form wire:submit.prevent="updateStatus" class="space-y-2">
            <select wire:model="status" class="border p-2">
                <option value="paid">paid</option>
                <option value="processing">processing</option>
                <option value="done">done</option>
                <option value="refunded">refunded</option>
                <option value="failed">failed</option>
            </select>
            <textarea wire:model="note" class="border p-2 w-full" placeholder="Internal note"></textarea>
            <input type="file" wire:model="receipt">
            <button class="bg-blue-500 text-white px-4 py-2">Update</button>
        </form>
    </div>
</div>
