<x-app-layout>
    <section>
        <h6>Assets</h6>
    </section>
    
    @can('create', App\Models\Asset::class)
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <a href="{{ route('assets.create') }}" class="btn btn-primary">Create Asset</a>
            </div>
        </div>
        <br><br>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session()->has('error'))
            <div class="alert alert-danger">
                <ul>
                    <li>{{session('error')}}</li>
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('assets.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Import File</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        @error('file')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload File</button>
                </form>
            </div>
        </div>
    </section>
    @endcan

    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($paginatedAssets->isEmpty())
            <div class="alert alert-info">
                No assets available.
            </div>
        @else
            <div class="user-group mt-4">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Model</th>
                            <th>Serial Number</th>
                            <th>Asset Number</th>
                            <!-- <th>Date</th> -->
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paginatedAssets as $asset)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $asset->make }}</td>
                                <td>{{ $asset->model }}</td>
                                <td>{{ $asset->serial_number }}</td>
                                <td>{{ $asset->asset_number }}</td>
                                <!-- <td>{{ $asset->date }}</td> -->
                                <td>{{ $asset->status }}</td>
                                <td>
                                    @can('view', [App\Models\Asset::class, $asset])
                                    <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @endcan

                                    @can('edit', [App\Models\Asset::class, $asset])
                                    <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan

                                    @can('delete', [App\Models\Asset::class, $asset])
                                    <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this asset?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-4">
            {{ $paginatedAssets->links() }}
        </div>
    </div>
    
    <br><br><br>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.view-audit-trail', function(e) {
                e.preventDefault();
                var assetId = $(this).data('asset-id');
                
                // AJAX request to fetch audit trail data
                $.ajax({
                    url: '/assets/' + assetId + '/audit-trail',
                    method: 'GET',
                    success: function(response) {
                        var auditTrailBody = $('#auditTrailBody');
                        auditTrailBody.empty();
                        
                        if(response.length > 0) {
                            $.each(response, function(index, trail) {
                                var row = `<tr>
                                    <td>${trail.current_user}</td>
                                    <td>${trail.previous_user}</td>
                                    <td>${trail.updated_data}</td>
                                    <td>${trail.duration}</td>
                                </tr>`;
                                auditTrailBody.append(row);
                            });
                        } else {
                            auditTrailBody.append('<tr><td colspan="4">No audit trail data available.</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>
