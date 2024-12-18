<x-app-layout>
    <h6 class="mb-4">All Offices</h6>
    
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <button type="button" class="btn btn-primary btn-create" data-bs-toggle="modal" data-bs-target="#createLocationModal">
                    Add New Office Location
                </button>
            </div>
        </div>
    </section>
   
    <br>
    <br>
    <br>
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

        @if ($locations->isEmpty())
            <div class="alert alert-info">
                No data available. Please add a new data.
            </div>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <!-- <th>Contact Information</th> -->
                        <th>Region</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $location)
                        <tr>
                            <td>{{ $location->id }}</td>
                            <td>{{ $location->location_name }}</td>
                            <!-- <td>{{ $location->contact_information }}</td> -->
                            <td>{{ $location->region->region_name }}</td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm btn-edit" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editLocationModal" 
                                    data-id="{{ $location->id }}" 
                                    data-name="{{ $location->location_name }}" 
                                    data-contact="{{ $location->contact_information }}" 
                                    data-region="{{ $location->region_id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                
                                @can('delete', $location)
                                <form action="{{ route('locations.destroy', $location->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this location?');">
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
                {{ $locations->links() }} <!-- Display pagination links -->
            </div>
        @endif
    </div>
    <br><br><br><br>

    <!-- Create Location Modal -->
    <div class="modal fade" id="createLocationModal" tabindex="-1" aria-labelledby="createLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLocationModalLabel">Add New Office Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createLocationForm" action="{{ route('locations.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="location_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('location_name') is-invalid @enderror" id="location_name" name="location_name" value="{{ old('location_name') }}" required>
                            @error('location_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="contact_information" class="form-label">Contact Information</label>
                            <input type="text" class="form-control @error('contact_information') is-invalid @enderror" id="contact_information" name="contact_information" value="{{ old('contact_information') }}">
                            @error('contact_information')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="region_id" class="form-label">Region</label>
                            <select class="form-control @error('region_id') is-invalid @enderror" id="region_id" name="region_id" required>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>{{ $region->region_name }}</option>
                                @endforeach
                            </select>
                            @error('region_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Location Modal -->
    <div class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLocationModalLabel">Update Office Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editLocationForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_location_id" name="location_id">
                        <div class="mb-3">
                            <label for="edit_location_name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('location_name') is-invalid @enderror" id="edit_location_name" name="location_name" required>
                            @error('location_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_contact_information" class="form-label">Contact Information</label>
                            <input type="text" class="form-control @error('contact_information') is-invalid @enderror" id="edit_contact_information" name="contact_information" required>
                            @error('contact_information')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="edit_region_id" class="form-label">Region</label>
                            <select class="form-control @error('region_id') is-invalid @enderror" id="edit_region_id" name="region_id" required>
                                @foreach($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                @endforeach
                            </select>
                            @error('region_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            @if ($errors->any())
                // Show the create location modal if there are validation errors
                @if ($errors->has('location_name') || $errors->has('contact_information') || $errors->has('region_id'))
                    console.log('Validation errors detected, opening the Create Location modal');
                    $('#createLocationModal').modal('show');
                @endif
            @endif

            // Open edit modal and populate with data
            $('.btn-edit').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var contact = $(this).data('contact');
                var region = $(this).data('region');

                $('#edit_location_id').val(id);
                $('#edit_location_name').val(name);
                $('#edit_contact_information').val(contact);
                $('#edit_region_id').val(region);

                $('#editLocationForm').attr('action', '{{ url('locations') }}/' + id);
                console.log('Opening Edit modal for location ID:', id);
            });
        });
    </script>
</x-app-layout>
