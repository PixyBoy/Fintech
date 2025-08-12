<div>
    <table class="min-w-full">
        <thead><tr><th>Code</th><th>Amount</th><th>Total IRR</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($requests as $r)
            <tr class="border-t"><td>{{ $r->request_code }}</td><td>{{ $r->amount_usd }}</td><td>{{ $r->quote_snapshot['total_irr'] ?? '' }}</td><td>{{ $r->status }}</td></tr>
        @endforeach
        </tbody>
    </table>
    {{ $requests->links() }}
</div>
