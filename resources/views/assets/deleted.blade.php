<x-app-layout>
    <div class="container">
        <h6>Deleted Assets</h6>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Clear All Button -->
        <form action="{{ route('assets.clear-archived') }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete all archived assets?');">
            @csrf
            <button type="submit" class="btn btn-danger mb-3">Clear All Archived Assets</button>
        </form>

        @if($deletedAssets->isEmpty())
            <p>No deleted assets found.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Serial Number</th>
                        <th>Asset Number</th>
                        <th>Category</th>
                        <th>Deleted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deletedAssets as $asset)
                        <tr>
                            <td>{{ $asset->id }}</td>
                            <td>{{ $asset->make }}</td>
                            <td>{{ $asset->model }}</td>
                            <td>{{ $asset->serial_number }}</td>
                            <td>{{ $asset->asset_number }}</td>
                            <td>{{ $asset->assetCategory->category_name ?? 'N/A' }}</td> 
                            <td>{{ $asset->deleted_at }}</td>
                            <td>
                                <!-- Restore Button -->
                                @can('restore', $asset)
                                <form action="{{ route('assets.restore', $asset->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Restore</button>
                                </form>
                                @endcan
                                
                                <!-- Delete Button -->
                                
                                <form action="{{ route('assets.delete', $asset->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to permanently delete this asset?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
