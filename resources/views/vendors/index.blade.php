<x-app-layout>
    <h4>Vendors</h4>
    @can('create', App\Models\Vendor::class)
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createVendorModal">
                    Add New Vendor
                </button>
            </div>
        </div>
    </section>
    @endcan
    <br>
    <br>
    <br>
    <div class="container">
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

        <!-- Vendors table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Information</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($vendors->isEmpty())
                <div class="alert alert-info">
                    No data available. Please add a new data.
                </div>
                @else
                    @foreach($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                            <td>{{ $vendor->vendor_name }}</td>
                            <td>{{ $vendor->vendor_contact_information }}</td>
                            <td>
                                @can('edit', App\Models\Vendor::class)
                                <!-- Edit button -->
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editVendorModal-{{ $vendor->id }}">
                                    <i class="fas fa-edit"></i> 
                                </button>
                                @endcan

                                @can('delete', $vendor)
                                <!-- Delete form -->
                                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vendor?');">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="mt-4">
            {{ $vendors->links() }}
        </div>
    </div>
</div>

<div class="modal fade" id="createVendorModal" tabindex="-1" aria-labelledby="createVendorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createVendorModalLabel">Add New Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vendors.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="vendor_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="vendor_name" name="vendor_name" value="{{ old('vendor_name') }}" required>
                        @error('vendor_name')
                            <sm class="text-danger text-sm">{{ $message }}</sm>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="vendor_contact_information" class="form-label">Contact Information</label>
                        <input type="text" class="form-control" id="vendor_contact_information" name="vendor_contact_information" value="{{ old('vendor_contact_information') }}" required>
                        @error('vendor_contact_information')
                            <sm class="text-danger text-sm">{{ $message }}</sm>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Vendor Modals -->
@foreach($vendors as $vendor)
<div class="modal fade" id="editVendorModal-{{ $vendor->id }}" tabindex="-1" aria-labelledby="editVendorModalLabel-{{ $vendor->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editVendorModalLabel-{{ $vendor->id }}">Update Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vendors.update', $vendor->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="vendor_name-{{ $vendor->id }}" class="form-label">Name</label>
                        <input type="text" class="form-control" id="vendor_name-{{ $vendor->id }}" name="vendor_name" value="{{ $vendor->vendor_name }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="vendor_contact_information-{{ $vendor->id }}" class="form-label">Vendor Contact Information</label>
                        <input type="text" class="form-control" id="vendor_contact_information-{{ $vendor->id }}" name="vendor_contact_information" value="{{ $vendor->vendor_contact_information }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>
