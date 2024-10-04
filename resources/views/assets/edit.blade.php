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

        <br><br>
        <form action="{{ route('assets.update', $asset->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Required for updating a resource -->

            <!-- Make and Model Fields -->
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

            <!-- Serial Number and Asset Number Fields -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="serial_number">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}" required>
                            @error('serial_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="asset_number">Asset Number</label>
                            <input type="text" class="form-control" id="asset_number" name="asset_number" value="{{ old('asset_number', $asset->asset_number) }}" required>
                            @error('asset_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id">Category</label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                @foreach($assetCategories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == $asset->category_id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location_id">Location</label>
                            <select class="form-control" id="location_id" name="location_id" required>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ $location->id == $asset->location_id ? 'selected' : '' }}>{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category and Date Fields -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <!-- <div class="col-md-6 mb-3">
                            <label for="category">Category</label>

                            <!-- Input for editing the category -->
                            <!-- <input type="text" class="form-control mb-2" id="category" name="category" value="{{ old('category', $asset->category) }}" required> -->

                            <!-- Dropdown menu for selecting a new category -->
                            <!-- <select class="form-control" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Laptops" {{ old('category', $asset->category) == 'Laptops' ? 'selected' : '' }}>Laptops</option>
                                <option value="Desktop" {{ old('category', $asset->category) == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                <option value="Monitors" {{ old('category', $asset->category) == 'Monitors' ? 'selected' : '' }}>Monitors</option>
                                <option value="Screens" {{ old('category', $asset->category) == 'Screens' ? 'selected' : '' }}>Screens</option>
                                <option value="Printers" {{ old('category', $asset->category) == 'Printers' ? 'selected' : '' }}>Printers</option>
                                <option value="Scanners" {{ old('category', $asset->category) == 'Scanners' ? 'selected' : '' }}>Scanners</option>
                                <option value="Servers" {{ old('category', $asset->category) == 'Servers' ? 'selected' : '' }}>Servers</option>
                                <option value="TPV PC" {{ old('category', $asset->category) == 'TPV PC' ? 'selected' : '' }}>TPV PC</option>
                                <option value="TPV Screens" {{ old('category', $asset->category) == 'TPV Screens' ? 'selected' : '' }}>TPV Screens</option>
                                <option value="Microphone" {{ old('category', $asset->category) == 'Microphone' ? 'selected' : '' }}>Microphone</option>
                                <option value="Keyboard" {{ old('category', $asset->category) == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                <option value="Mouse" {{ old('category', $asset->category) == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                <option value="Drives" {{ old('category', $asset->category) == 'Drives' ? 'selected' : '' }}>Drives</option>
                                <option value="Workstations" {{ old('category', $asset->category) == 'Workstations' ? 'selected' : '' }}>Workstations</option>
                                <option value="Mobile Device" {{ old('category', $asset->category) == 'Mobile Device' ? 'selected' : '' }}>Mobile Device</option>
                            </select>

                            @error('category')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>  -->

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $asset->date) }}" required>
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assign to (User or Vendor) -->
            <!-- <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location', $asset->location) }}" required>
                            <select class="form-control" id="user_or_vendor" name="user_or_vendor" onchange="toggleUserOrVendor()">
                                <option value="">Select Option</option>
                                <option value="user" {{ old('user_or_vendor', $asset->user_or_vendor) == 'user' ? 'selected' : '' }}>Current User</option>
                                <option value="vendor" {{ old('user_or_vendor', $asset->user_or_vendor) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            </select> -->
                        <!-- </div>
                    </div>
                </div>
            </div>  -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="user_or_vendor">Assign to</label>
                            <select class="form-control" id="user_or_vendor" name="user_or_vendor" onchange="toggleUserOrVendor()">
                                <option value="">Select Option</option>
                                <option value="user" {{ old('user_or_vendor', $asset->user_or_vendor) == 'user' ? 'selected' : '' }}>Current User</option>
                                <option value="vendor" {{ old('user_or_vendor', $asset->user_or_vendor) == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor Field -->
            <div class="card mb-3" id="vendor_field" style="display: none;">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="vendor">Vendor</label>
                            <input type="vendor" class="form-control" id="vendor" name="vendor" value="{{ old('vendor', $asset->vendor) }}" required>
                            <!-- <select class="form-control" id="vendor" name="vendor">
                                <option value="">Select Vendor</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor', $asset->vendor) == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->name }}
                                    </option>
                                @endforeach
                            </select> -->
                            @error('vendor')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current User Field -->
            <div class="card mb-3" id="current_user_field" style="display: none;">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="user_id">Current User</label>
                            <select class="form-control" id="user_id" name="user_id">
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
                </div>
            </div>
            <!-- Current Previous User Field -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for=previous_user_id>Previous User</label>
                            <select class="form-control" id="previous_user_id" name="previous_user_id">
                                <option value="">Select Previous User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id', $asset->previous_user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Field -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="New" {{ old('status', $asset->status) == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Old" {{ old('status', $asset->status) == 'Old' ? 'selected' : '' }}>Old</option>
                                <option value="Broken" {{ old('status', $asset->status) == 'Broken' ? 'selected' : '' }}>Broken</option>
                                <option value="WrittenOff" {{ old('status', $asset->status) == 'WrittenOff' ? 'selected' : '' }}>Written Off</option>
                                <option value="In_Storage" {{ old('status', $asset->status) == 'In_storage' ? 'selected' : '' }}>In Storage</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insurance Field (Initially Hidden) -->
            <div class="card mb-3" id="insurance_field" style="display: none;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="insurance_id">Insurance Details</label>
                            <option value="">Select Insurance</option>
                                @foreach($insurances as $insurance)
                                    <option value="{{ $insurance->id }}" {{ $insurance->id == $asset->insurance_id ? 'selected' : '' }}>
                                        {{ $insurance->policy_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('insurance')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="form-footer">
                <a href="{{ route('assets.index') }}" class="btn btn-secondary mr-2">Back</a>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
    <br><br><br><br><br>

    <!-- Script for Toggling Fields Based on Selection -->
    <script>
        function toggleUserOrVendor() {
            const selectedOption = document.getElementById('user_or_vendor').value;
            const userField = document.getElementById('current_user_field');
            const vendorField = document.getElementById('vendor_field');
            const vendorInput = document.getElementById('vendor');
            const userSelect = document.getElementById('user_id');

            if (selectedOption === 'user') {
                userField.style.display = 'block';
                vendorField.style.display = 'none';
                userSelect.required = true;
                vendorInput.required = false;
            } else if (selectedOption === 'vendor') {
                userField.style.display = 'none';
                vendorField.style.display = 'block';
                userSelect.required = false;
                vendorInput.required = true;
            } else {
                userField.style.display = 'none';
                vendorField.style.display = 'none';
                userSelect.required = false;
                vendorInput.required = false;
            }
        }

        function toggleInsuranceField() {
            var status = document.getElementById('status').value;
            var insuranceField = document.getElementById('insurance_field');

            // Show insurance field if status is "Broken", otherwise hide it
            if (status === 'Broken') {
                insuranceField.style.display = 'block';
            } else {
                insuranceField.style.display = 'none';
            }
        }

        // Initialize fields based on previous selection
        toggleUserOrVendor();
        document.addEventListener('DOMContentLoaded', function () {
            const statusField = document.getElementById('status');
            const userField = document.getElementById('user_id');

            // Function to toggle user dropdown based on status
            function toggleUserField() {
                if (statusField.value === 'Broken') {
                    userField.disabled = true;
                } else {
                    userField.disabled = false;
                }
            }

            // Initial check when page loads
            toggleUserField();

            // Listen for changes in the status field
            statusField.addEventListener('change', toggleUserField);
        });
    </script>
</x-app-layout>
