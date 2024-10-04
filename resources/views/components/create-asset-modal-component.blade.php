<div class="modal fade" id="createAssetModal" tabindex="-1" aria-labelledby="createAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAssetModalLabel">Create New Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>                
            </div>
            <div class="modal-body">
                <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="asset_number" class="form-label">Asset Number</label>
                        <input type="text" class="form-control" id="asset_number" name="asset_number" value="{{ old('asset_number') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="asset_name" class="form-label">Asset Name</label>
                        <input type="text" class="form-control" id="asset_name" name="asset_name" value="{{ old('asset_name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="serial_number" class="form-label">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="" selected>Select a category</option>
                            @foreach($assetCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select class="form-select" id="department_id" name="department_id" required>
                            <option value="" selected>Select a Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="vendor_id" class="form-label">Vendor</label>
                        <select class="form-select" id="vendor_id" name="vendor_id" required>
                            <option value="" selected>Select a vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->vendor_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="location_id" class="form-label">Location</label>
                        <select class="form-select" id="location_id" name="location_id" required>
                            <option value="" selected>Select a location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="user_id" class="form-label">User</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="" selected>Select a user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="invoice_number" class="form-label">Invoice Number</label>
                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ old('invoice_number') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="purchased_date" class="form-label">Purchased Date</label>
                        <input type="date" class="form-control" id="purchased_date" name="purchased_date" value="{{ old('purchased_date') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" class="form-control" id="cost_price" name="cost_price" step="0.01" value="{{ old('cost_price') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" selected>Select a status</option>
                            <option value="In_use">In Use</option>
                            <option value="In_Storage">In Storage</option>
                            <option value="Broken">Broken</option>
                            <option value="Not_working">Not Working</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="asset_image" class="form-label">Asset Image</label>
                        <input type="file" class="form-control" id="asset_image" name="asset_image" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="attachment" class="form-label">Attachment</label>
                        <input type="file" class="form-control" id="attachment" name="attachment" accept="application/pdf" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Create Asset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
