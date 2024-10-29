<x-app-layout>
    <div class="container">
        <h6>Asset History</h6>

        <form action="{{ route('asset-assignments.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all asset assignment records?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Clear</button>
        </form>

        <br>

        @if($assignments->isEmpty())
            <div class="alert alert-info">
                No data found.
            </div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Asset</th>
                        <th>Assigned To</th>
                        <th>Assigned By</th>
                        <th>Assigned Date</th>
                        <th>Action</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assignments as $assignment)
                    <tr>
                        <!-- Display the current user (who the asset is assigned to) -->
                        <td>{{ $assignment->user ? $assignment->user->name : 'N/A' }}</td>

                        <!-- Display asset details -->
                        <td>{{ $assignment->asset ? $assignment->asset->make : 'N/A' }}</td>

                        <!-- Display the assigned to user's name (asset's assigned user) -->
                        <td>{{ $assignment->asset && $assignment->asset->user ? $assignment->asset->user->name : 'N/A' }}</td>

                        <!-- Display the assigned by user's name (currently logged in user) -->
                        <td>{{ Auth::user() ? Auth::user()->name : 'N/A' }}</td>

                        <!-- Display the assigned date or fallback to the asset's creation date -->
                        <td>
                            {{ $assignment->assignment_date 
                                ? $assignment->assignment_date->format('Y-m-d') 
                                : ($assignment->asset && $assignment->asset->created_at 
                                    ? $assignment->asset->created_at->format('Y-m-d') 
                                    : 'N/A') 
                            }}
                        </td>

                        <td>{{ $assignment->action }}</td>

                        <!-- Check if created_at is not null before formatting -->
                        <td>{{ $assignment->created_at ? $assignment->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $assignments->links() }}
        @endif
    </div>
    <br><br><br><br>
</x-app-layout>
