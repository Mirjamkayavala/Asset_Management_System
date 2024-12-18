<x-app-layout>
    <div class="container">
        <h6>Edit Asset</h6>
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

        

        <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Asset Make -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="make">Make</label>
                            <input type="text" class="form-control" id="make" name="make" value="{{ old('make', $asset->make) }}" required>
                            @error('make')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model" value="{{ old('model', $asset->model) }}" required>
                            @error('model')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Serial and Asset Numbers -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="serial_number">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" >
                            @error('serial_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="asset_number">Asset Number</label>
                            <input type="text" class="form-control" id="asset_number" name="asset_number" value="{{ old('asset_number', $asset->asset_number) }}" >
                            @error('asset_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category and Location -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control select2" id="category_id" name="category_id" required>
                                <option value="">Select a category</option>
                                @foreach($assetCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price">Price</label>
                            <input type="decimal" class="form-control" id="price" name="price" value="{{ old('price', $asset->price) }}">
                            @error('price')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Field -->
            <div class="card mb-3">
                <div class="card-body">
                    
                    <label for="location_id" class="form-label">Location</label>
                    <select class="form-control select2" id="location_id" name="location_id" required>
                        <option value="">Select a location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id', $asset->location_id) == $location->id ? 'selected' : '' }}>
                                {{ $location->location_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('location_id')
                        div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Assign to (User/Vendor) -->
            <div class="card mb-3">
                <div class="card-body">
                    <label for="user_or_vendor">Assign to</label>
                    <select class="form-control" id="user_or_vendor" name="user_or_vendor" onchange="toggleUserOrVendor()">
                        <option value="">Select Option</option>
                        <option value="user" {{ old('user_or_vendor', $asset->user_or_vendor) == 'user' ? 'selected' : '' }}>Current User</option>
                        <option value="vendor" {{ old('user_or_vendor', $asset->user_or_vendor) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="facility" {{ old('user_or_vendor', $asset->user_or_vendor) == 'facility' ? 'selected' : '' }}>Facility Space</option>
                    </select>
                </div>
            </div>

            <!-- Current User Field -->
            <div class="card mb-3" id="current_user_field" style="{{ old('user_or_vendor', $asset->user_or_vendor) == 'user' ? '' : 'display: none;' }}">
                <div class="card-body">
                    <label for="user_id">User</label>
                    <select class="form-control select2" id="user_id" name="user_id">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', $asset->user_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Vendor Field -->
            <div class="card mb-3" id="vendor_field" style="{{ old('user_or_vendor', $asset->user_or_vendor) == 'vendor' ? '' : 'display: none;' }}">
                <div class="card-body">
                    <label for="vendor">Vendor</label>
                    <input type="text" id="vendor" class="form-control" name="vendor" value="{{ old('vendor', $asset->vendor) }}">
                    
                </div>
            </div>

            <!-- Storage Location -->
            <div class="card mb-3" id="facility_field" style="{{ old('user_or_facility', $asset->user_or_facility) == 'facility' ? '' : 'display: none;' }}">
                <div class="card-body">
                    <label for="facility_id">Facility Space</label>
                            <select class="form-control select2" id="facility_id" name="facility_id">
                                <option value="">Select Facility Space</option>
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->facility_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                    
                </div>
            </div>

            <div class="card mb-3" id="previous_user_field">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="previous_user_id">Previous User</label>
                            <select class="form-control select2" id="previous_user_id" name="previous_user_id" onchange="toggleManualInput('previous')">
                                <option value="">Select Previous User</option>
                                <option value="other">Other</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('previous_user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                                
                            </select>
                            <!-- Manual input field for Previous User (hidden by default) -->
                            <input type="text" class="form-control mt-2" id="manual_previous_user" name="manual_previous_user" placeholder="Enter Previous User" style="display:none;" value="{{ old('manual_previous_user') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="status">Status</label>
                            <select class="form-control select2" id="status" name="status" required onchange="checkAssetStatus()">
                                <option value="">Select Status</option>
                                <option value="New" {{ old('status', $asset->status) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Old" {{ old('status', $asset->status) == 'Old' ? 'selected' : '' }}>Old</option>
                                <option value="Broken" {{ old('status', $asset->status) == 'Broken' ? 'selected' : '' }}>Broken</option>
                                <option value="WrittenOff" {{ old('status', $asset->status) == 'WrittenOff' ? 'selected' : '' }}>Written Off</option>
                                <option value="In_Storage" {{ old('status', $asset->status) == 'In_Storage' ? 'selected' : '' }}>In Storage</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-footer">
                <a href="{{ route('assets.index') }}" class="btn btn-secondary mr-2">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Add JavaScript to toggle visibility of fields based on the selected option -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            toggleUserOrVendor(); // Call this on page load to set the initial state

            const userOrVendorSelect = document.getElementById("user_or_vendor");
            const userOrFacilitySelect = document.getElementById("user_or_facility");
            userOrVendorSelect.addEventListener("change", toggleUserOrVendor);
        });

        function toggleUserOrVendor() {
            const selected = document.getElementById("user_or_vendor").value;

            // Show/hide fields based on selection
            document.getElementById("current_user_field").style.display = selected === "user" ? "block" : "none";
            document.getElementById("vendor_field").style.display = selected === "vendor" ? "block" : "none";
            document.getElementById("facility_field").style.display = selected === "facility" ? "block" : "none";
        }
        function checkAssetStatus() {
            const statusElement = document.getElementById('status');
            const selectedStatus = statusElement.value;

            if (selectedStatus === 'Broken' || selectedStatus === 'WrittenOff') {
                const confirmChange = confirm(`You have selected '${selectedStatus}'. This will trigger an insurance claim for this asset. Do you want to continue?`);

                if (!confirmChange) {
                    // Revert the selection to its previous value
                    statusElement.value = "{{ old('status', $asset->status) }}";
                }
            }
        }

        $(document).ready(function() {


            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>

    <br><br><br><br><br>
</x-app-layout>
