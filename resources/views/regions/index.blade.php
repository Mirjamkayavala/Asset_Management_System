<x-app-layout>
    <h6 class="mb-4">All Regions</h6>
    @can('create', App\Models\Region::class)
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <a href="{{ route('regions.create') }}" class="btn btn-primary">
                    Create Region
                </a>
            </div>
        </div>
    </section>
    @endcan
    <br><br><br>
    <div class="table-container">
        
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

        @if ($regions->isEmpty())
            <div class="alert alert-info">
                No data available.
            </div>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Zip Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($regions as $region)
                        <tr>
                            <td>{{ $region->id }}</td>
                            <td>{{ $region->region_name }}</td>
                            <td>{{ $region->zipcode }}</td>
                            <td>
                                @can('edit', $region)
                                <form action="{{ route('regions.edit', $region->id) }}" method="GET" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm btn-edit" 
                                            data-id="{{ $region->id }}" 
                                            data-region_name="{{ $region->region_name }}" 
                                            data-zipcode="{{ $region->zipcode }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>
                                @endcan
                                @can('delete', $region)
                                <form action="{{ route('regions.destroy', $region->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this region?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $regions->links() }}
            </div>
        @endif
    </div>

    <!-- Edit Region Modal -->
    <div class="modal fade" id="editRegionModal" tabindex="-1" aria-labelledby="editRegionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRegionModalLabel">Edit Region</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRegionForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_region_id" name="region_id">
                        <div class="mb-3">
                            <label for="edit_region_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_region_name" name="region_name" required>
                            @error('region_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_zipcode" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="edit_zipcode" name="zipcode" required>
                            @error('zipcode')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Edit button click handler to populate edit form fields
            $('.btn-edit').click(function() {
                var id = $(this).data('id');
                var region_name = $(this).data('region_name');
                var zipcode = $(this).data('zipcode');

                $('#edit_region_id').val(id);
                $('#edit_region_name').val(region_name);
                $('#edit_zipcode').val(zipcode);

                // Set the action attribute of the form with the region ID
                $('#editRegionForm').attr('action', '/regions/' + id);

                $('#editRegionModal').modal('show'); // Show edit modal
            });

            // Handle form submission for editing region
            $('#editRegionForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                var actionUrl = $(this).attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        $('#editRegionModal').modal('hide');
                        alert('Region updated successfully');
                        window.location.reload(); // Reload the page or update the list dynamically
                    },
                    error: function(xhr) {
                        alert('Failed to update region: ' + xhr.responseText);
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</x-app-layout>
