<div>
    <table class="min-w-full">
        <thead><tr><th>ID</th><th>User</th><th>Code</th><th>Status</th></tr></thead>
        <tbody>
        @foreach($requests as $r)
            <tr class="border-t"><td>{{ $r->id }}</td><td>{{ $r->user_id }}</td><td><a href="{{ route('payforme.admin.show', $r->id) }}" class="text-blue-500">{{ $r->request_code }}</a></td><td>{{ $r->status }}</td></tr>
        @endforeach
        </tbody>
    </table>
    {{ $requests->links() }}
</div>
