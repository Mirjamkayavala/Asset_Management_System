<div :results="$results" :assets="$assets" >
<div class="container mt-5">
    <div class="text-center mb-4">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAssetModal">
            Create New Asset
        </button>
    </div>
    
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (isset($results) && $results->isNotEmpty())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Asset Number</th>
                    <th>Name</th>
                    <th>Serial Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $asset)
                    <tr>
                        <td>{{ $asset->asset_number }}</td>
                        <td>{{ $asset->asset_name }}</td>
                        <td>{{ $asset->serial_number }}</td>
                        <td>{{ $asset->status }}</td>
                        <td>
                            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info btn-sm">View More</a>
                            <a href="{{ route('assets.index') }}" class="btn btn-primary btn-sm">Back to Index</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @elseif (!empty($assets) && $assets->isNotEmpty())
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Asset Number</th>
                    <th>Name</th>
                    <th>Serial Number</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $asset->asset_number }}</td>
                        <td>{{ $asset->asset_name }}</td>
                        <td>{{ $asset->serial_number }}</td>
                        <td>{{ $asset->status }}</td>
                        <td>
                            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info btn-sm">View More</a>
                            <a href="{{ route('assets.index') }}" class="btn btn-primary btn-sm">Back to Index</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $assets->links() }}
        </div>
    @else
        <p>No available data</p>
    @endif
</div>
</div>
