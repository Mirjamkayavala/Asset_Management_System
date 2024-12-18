<x-app-layout>
    <div class="container">
        <h6>Audit Trails</h6>

        <form action="{{ route('audit-trails.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all audit trails?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Clear</button>
        </form>

        <br>

        @if($auditTrails->isEmpty())
            <div class="alert alert-info">
                No data available.
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <!-- <th>User</th> -->
                        <th>Table Name</th>
                        <th>Column Name</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($auditTrails as $auditTrail)
                    <tr>
                        <!-- <td>{{ $auditTrail->user->name }}</td> -->
                        <td>{{ $auditTrail->table_name }}</td>
                        <td>{{ $auditTrail->column_name }}</td>
                        <td>{{ $auditTrail->old_value }}</td>
                        <td>{{ $auditTrail->new_value }}</td>
                        <td>{{ $auditTrail->action }}</td>
                        <td>{{ $auditTrail->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $auditTrails->links() }}
        @endif
    </div>
    <br><br><br><br>
</x-app-layout>
