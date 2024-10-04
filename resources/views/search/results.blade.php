<x-app-layout>

<div class="container">
   

    @if($assets->isNotEmpty())
        <h6>Assets</h6>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Asset Number</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $asset->make }}</td>
                        <td>{{ $asset->model }}</td>
                        <td>{{ $asset->serial_number }}</td>
                        <td>{{ $asset->asset_number }}</td>
                        <td>{{ $asset->date }}</td>
                        <td>{{ $asset->status }}</td>
                        
                        <td>
                            <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('assets.index') }}" class="btn btn-primary btn-sm">Back</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($departments->isNotEmpty())
        <h6>Departments</h6>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Department Code</th>
                    <th>User</th>
                    <th>Actions</th>
                   
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr>
                        <td>{{ $department->id }}</td>
                        <td>{{ $department->department_name }}</td>
                        <td>{{ $department->department_code }}</td>
                        <td>{{ $department->user ? $department->user->name : '' }}</td>
                        <td>
                            @can('edit', $department)    
                                <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endcan
                            @can('delete', $department)
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @if($regions->isNotEmpty())
        <h6>Regions</h6>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Zipcode</th>
                    <th>Action</th>
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
    @endif

    @if($locations->isNotEmpty())
        <h6>Office</h6>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Information</th>
                    <th>Region</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($locations as $location)
                    <tr>
                        
                        <td>{{ $location->id }}</td>
                        <td>{{ $location->location_name }}</td>
                        <td>{{ $location->contact_information }}</td>
                        <td>{{ $location->region->region_name }}</td> 
                        <td>
                            @can('edit', $location)
                            <button type="button" class="btn btn-primary btn-sm btn-edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editLocationModal" 
                                data-id="{{ $location->id }}" 
                                data-name="{{ $location->location_name }}" 
                                data-contact="{{ $location->contact_information }}" 
                                data-region="{{ $location->region_id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            @endcan
                            
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
        <!-- Edit Department Modal -->
        <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editDepartmentForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_department_name" class="form-label">Department Name</label>
                                <input type="text" class="form-control" id="edit_department_name" name="department_name" required>
                                @error('department_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_department_code" class="form-label">Department Code</label>
                                <input type="text" class="form-control" id="edit_department_code" name="department_code" required>
                                @error('department_code')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Department</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($vendors->isNotEmpty())
        <h6>Vendors</h6>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact Information</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->id }}</td>
                        <td>{{ $vendor->vendor_name }}</td>
                        <td>{{ $vendor->vendor_contact_information }}</td>
                        <td>
                            <!-- Edit button -->
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
            </tbody>
        </table>
    @endif
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

    @if($assetCategories->isNotEmpty())
    <h6>Asset Categories</h6>
        <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Category Code</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assetCategories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->category_code }}</td>
                        <td>
                            <!-- Button trigger modal for Edit -->
                            @can('update', $category)
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editCategoryModal{{ $category->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            @endcan

                            <!-- Modal for Edit -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <form action="{{ route('asset_categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit Asset Category</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label for="category_name" class="form-label">Category Name</label>
                                                    <input type="text" class="form-control" id="category_name" name="category_name" value="{{ $category->category_name }}" required>
                                                    @error('category_name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="category_code" class="form-label">Category Code</label>
                                                    <input type="text" class="form-control" id="category_code" name="category_code" value="{{ $category->category_code }}" required>
                                                    @error('category_code')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Form -->
                            @can('delete', $category)
                            <form action="{{ route('asset_categories.destroy', $category->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

    @endif

    @if($invoices->isNotEmpty())
        <h6>Invoice</h6>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Purchased Date</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->id }}</td>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>{{ $invoice->invoice_date }}</td>
                        <td>{{ $invoice->amount }}</td>
                        <td>
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>  
                                </a>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editInvoiceModal" 
                                    data-id="{{ $invoice->id }}"
                                    data-invoice_number="{{ $invoice->invoice_number }}"
                                    data-invoice_date="{{ $invoice->invoice_date }}"
                                    data-amount="{{ $invoice->amount }}"
                                    data-file_path="{{ $invoice->file_path }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>
                                </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Edit Invoice Modal -->
        <div class="modal fade" id="editInvoiceModal" tabindex="-1" aria-labelledby="editInvoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInvoiceModalLabel">Edit Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editInvoiceForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="edit_invoice_number" class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" id="edit_invoice_number" name="invoice_number" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_invoice_date" class="form-label">Purchased Date</label>
                                <input type="date" class="form-control" id="edit_invoice_date" name="invoice_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="edit_amount" name="amount" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_file_path" class="form-label">Invoice File</label>
                                <input type="file" class="form-control" id="edit_file_path" name="file_path">
                                <small id="current_file" class="form-text text-muted"></small>
                            </div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($insurances->isNotEmpty())
        <h6>Insurance deitails</h6>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Asset</th>
                    <th>Assign To</th>
                    <th>Policy Number</th>
                    <th>Status</th>
                    <th>Claim Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($insurances as $insurance)
                        <tr>
                            <td>{{ $insurance->id }}</td>
                            <td>{{ $insurance->asset ? $insurance->asset->asset_name : 'N/A' }}</td>
                            <td>{{ $insurance->user ? $insurance->user->name : 'N/A' }}</td>
                            <td>{{ $insurance->claim_number }}</td>
                            <td>{{ ucfirst($insurance->status) }}</td>
                            <td>{{ $insurance->claim_date }}</td>
                            <td>
                                <a href="{{ route('insurances.show', $insurance->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('insurances.edit', $insurance->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('insurances.destroy', $insurance->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
    @endif

    @if($users->isNotEmpty())
        <h6>User deitails</h6>
        <table class="table table-striped table-bordered">
            <thead>
                
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact Number</th>
                    <th>Actions</th>
                </tr>
                
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->contact_number }}</td>
                        <td>
                            <a href="{{ route('users.show', ['user' => $user->id]) }}" class="btn btn-info">
                                <i class="fas fa-eye"></i> 
                            </a>
                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> 
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash-alt"></i> 
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($assets->isEmpty() && $departments->isEmpty() && $locations->isEmpty() && $vendors->isEmpty() && $assetCategories->isEmpty() && $regions->isEmpty() && $invoices->isEmpty() && $insurances ->isEmpty() && $users->isEmpty())
        <p>No results found for '{{ request('query') }}'.</p>
    @endif
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


</x-app-layout>
