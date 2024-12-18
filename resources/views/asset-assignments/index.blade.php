<x-app-layout>
    <div class="container">
        <h6>Asset History</h6>

        
        <section class="container mt-5">
            <div class="card">
                <div class="card-body text-left">
                <a href="{{ route('assets.deleted') }}" class="btn btn-outline-primary">
                    Archived Assets
                </a>
                </div>
            </div>
        </section>
        
        <br><br>
       

        <!-- <form action="{{ route('asset-assignments.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all asset assignment records?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Clear</button>
        </form> -->

        <br>

        @if($assignments->isEmpty())
            <div class="alert alert-info">
                No data found.
            </div>
        @else
            
            <table>
                <thead>
                    <tr>
                        <th>Change Type</th>
                        <th>Changed By</th>
                        <th>Changes</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($history as $record)
                    <tr>
                        <td>{{ $record->change_type }}</td>
                        <td>{{ $record->changedBy->name }}</td>
                        <td>{{ json_encode($record->changes) }}</td>
                        <td>{{ $record->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $assignments->links() }}
        @endif
    </div>
    <br><br><br><br>
</x-app-layout>
