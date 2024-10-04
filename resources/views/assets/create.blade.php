<x-app-layout>
    <div class="container">
        <h6>Create New Asset</h6>
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
        <form action="{{ route('assets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="make">Make</label>
                            <input type="text" class="form-control" id="make" name="make" value="{{ old('make') }}" required>
                            @error('make')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}" required>
                            @error('model')
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
                            <label for="serial_number">Serial Number</label>
                            <input type="text" class="form-control" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required>
                            @error('serial_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="asset_number">Asset Number</label>
                            <input type="text" class="form-control" id="asset_number" name="asset_number" value="{{ old('asset_number') }}" required>
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
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="" selected>Select a category</option>
                                    @foreach($assetCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                                @error('category_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location_id" class="form-label">Location</label>
                            <select class="form-select" id="location_id" name="location_id" required>
                                <option value="" selected>Select a location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                                @endforeach
                            </select>
                            @error('location_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        
                    <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <!-- <div class="col-md-6 mb-3">
                                        <label for="category">Category</label>
                                        <select class="form-control" id="category" name="category" onchange="toggleOtherInput()">
                                            <option value="">Select Category</option>
                                            <option value="other">Other </option>
                                            <option value="Laptops" {{ old('category') == 'Laptops' ? 'selected' : '' }}>Laptops</option>
                                            <option value="Desktop" {{ old('category') == 'Desktop' ? 'selected' : '' }}>Desktop</option>
                                            <option value="Screens" {{ old('category') == 'Screens' ? 'selected' : '' }}>Screens</option>
                                            <option value="Monitors" {{ old('category') == 'Monitors' ? 'selected' : '' }}>Monitors</option>
                                            <option value="Printers" {{ old('category') == 'Printers' ? 'selected' : '' }}>Printers</option>
                                            <option value="Scanners" {{ old('category') == 'Scanners' ? 'selected' : '' }}>Scanners</option>
                                            <option value="Servers" {{ old('category') == 'Servers' ? 'selected' : '' }}>Servers</option>
                                            <option value="TPV PC" {{ old('category') == 'TPV PC' ? 'selected' : '' }}>TPV PC</option>
                                            <option value="TPV Screens" {{ old('category') == 'TPV Screens' ? 'selected' : '' }}>TPV Screens</option>
                                            <option value="Microphone" {{ old('category') == 'Microphone' ? 'selected' : '' }}>Microphone</option>
                                            <option value="Keyboard" {{ old('category') == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                            <option value="Mouse" {{ old('category') == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                            <option value="Drives" {{ old('category') == 'Drives' ? 'selected' : '' }}>Drives</option>
                                            <option value="Workstations" {{ old('category') == 'Workstations' ? 'selected' : '' }}>Workstations</option>
                                            <option value="Mobile Device" {{ old('category') == 'Mobile Device' ? 'selected' : '' }}>Mobile Device</option>
                                        </select>
                                         Manual input field for Other Category (hidden by default) -->
                                        <!-- <input type="text" class="form-control mt-2" id="manual_category" name="manual_category" placeholder="Enter Category" style="display:none;" value="{{ old('manual_category') }}">
                                        @error('category')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>  -->
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                                        @error('date')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6 mb-3">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> -->
                    </div>
                </div>
            </div>
            <!-- New dropdown to select User or Vendor -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                            <!-- <select class="form-control" id="user_or_vendor" name="user_or_vendor" onchange="toggleUserOrVendor()">
                                <option value="">Select Option</option>
                                <option value="user" {{ old('user_or_vendor') == 'user' ? 'selected' : '' }}>Current User</option>
                                <option value="vendor" {{ old('user_or_vendor') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            </select> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- New dropdown to select User or Vendor -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="user_or_vendor">Assign to</label>
                            <select class="form-control" id="user_or_vendor" name="user_or_vendor" onchange="toggleUserOrVendor()">
                                <option value="">Select Option</option>
                                <option value="user" {{ old('user_or_vendor') == 'user' ? 'selected' : '' }}>Current User</option>
                                <option value="vendor" {{ old('user_or_vendor') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current User field (initially hidden) -->
            <div class="card mb-3" id="current_user_field">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="user_id">Current User</label>
                            <select class="form-control" id="user_id" name="user_id" onchange="toggleManualInput('current')">
                                <option value="">Select User</option>
                                <option value="other"></option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                                
                            </select>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <!-- Manual input field for Current User (hidden by default) -->
                            <input type="text" class="form-control mt-2" id="manual_current_user" name="manual_current_user" placeholder="Enter Current User" style="display:none;" value="{{ old('manual_current_user') }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vendor field (initially hidden) -->
            <div class="card mb-3" id="vendor_field" style="display: none;">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="vendor">Vendor</label>
                            <input type="text" id="vendor" class= "form-control" name="vendor" value="{{ old('vendor') }}" required>
                            <!-- <select class="form-control" id="vendor_id" name="vendor_id">
                                <option value="">Select Vendor</option>
                                <option value="other">Other (Manual Entry)</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->vendor_name }}
                                    </option>
                                @endforeach
                            </select> -->
                            <!-- <input type="text" class="form-control mt-2" id="manual_vendor" name="manual_vendor" placeholder="Enter vendor" style="display:none;" value="{{ old('manual_vendor') }}"> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3" id="previous_user_field">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="previous_user_id">Previous User</label>
                            <select class="form-control" id="previous_user_id" name="previous_user_id" onchange="toggleManualInput('previous')">
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

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required onchange="toggleUserFields()">
                                <option value="">Select Status</option>
                                <option value="New" {{ old('status') == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Old" {{ old('status') == 'Old' ? 'selected' : '' }}>Old</option>
                                <!-- <option value="In Use" {{ old('status') == 'In Use' ? 'selected' : '' }}>In Use</option>
                                <option value="In Storage" {{ old('status') == 'In Storage' ? 'selected' : '' }}>In Storage</option> -->
                            </select>
                            @error('asset_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-footer">
                <a href="{{ route('assets.index') }}" class="btn btn-secondary mr-2">Back</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <br><br><br><br>

    <script>
        function toggleUserOrVendor() {
			const selectedOption = document.getElementById('user_or_vendor').value;
			const userField = document.getElementById('current_user_field');
			const vendorField = document.getElementById('vendor_field');
			const vendorInput = document.getElementById('vendor');
			const userSelect = document.getElementById('user_id');

			// Show or hide fields based on the selected option
			if (selectedOption === 'user') {
				userField.style.display = 'block';  // Show user field
				vendorField.style.display = 'none'; // Hide vendor field

				userSelect.required = true; // Set required for user field
				vendorInput.required = false; // Remove required from vendor field
			} else if (selectedOption === 'vendor') {
				userField.style.display = 'none';  // Hide user field
				vendorField.style.display = 'block'; // Show vendor field

				userSelect.required = false; // Remove required from user field
				vendorInput.required = true;  // Set required for vendor field
			} else {
				// Hide both fields if nothing is selected
				userField.style.display = 'none';
				vendorField.style.display = 'none';

				userSelect.required = false; // Remove required from user field
				vendorInput.required = false; // Remove required from vendor field
			}
		}

        function toggleOtherInput() {
            const categorySelect = document.getElementById('category');
            const manualCategoryInput = document.getElementById('manual_category');
            
            if (categorySelect.value === 'other') {
                manualCategoryInput.style.display = 'block';
                manualCategoryInput.required = true;
            } else {
                manualCategoryInput.style.display = 'none';
                manualCategoryInput.required = false;
            }
        }

        function toggleManualInput(userType) {
            const selectField = document.getElementById(userType + '_user_id');
            const manualInputField = document.getElementById('manual_' + userType + '_user');

            // Show manual input field if "Other" is selected
            if (selectField.value === 'other') {
                manualInputField.style.display = 'block';
                manualInputField.required = true; // Set manual input field as required
            } else {
                manualInputField.style.display = 'none';
                manualInputField.required = false; // Remove required attribute if not needed
            }
        }

		// Trigger on page load in case of old data
		window.onload = function() {
			toggleUserOrVendor();
		};

    </script>


</x-app-layout>
