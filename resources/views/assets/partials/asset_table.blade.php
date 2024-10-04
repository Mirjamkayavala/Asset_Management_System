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
