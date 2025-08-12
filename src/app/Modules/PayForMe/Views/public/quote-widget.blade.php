<div class="p-4 border rounded">
    <div>Rate: {{ $quote['rate_used'] ?? '-' }}</div>
    <div>Fee: {{ $quote['fee_usd'] ?? '-' }}</div>
    <div>Total IRR: {{ $quote['total_irr'] ?? '-' }}</div>
</div>
