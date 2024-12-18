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
                            <input type="text" class="form-control @error('make') is-invalid @enderror" id="make" name="make" value="{{ old('make') }}" required>
                            @error('make')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="model">Model</label>
                            <input type="text" class="form-control @error('model') is-invalid @enderror" id="model" name="model" value="{{ old('model') }}" required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                            <input type="text" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required>
                            @error('serial_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="asset_number">Asset Number</label>
                            <input type="text" class="form-control @error('asset_number') is-invalid @enderror" id="asset_number" name="asset_number" value="{{ old('asset_number') }}">
                            @error('asset_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control select2 @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="" selected>Select a category</option>
                                @foreach($assetCategories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price">Price</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" step="0.01" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3" >
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="location_id" class="form-label">Location</label>
                            <select class="form-control select2" id="location_id" name="location_id" required>
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

            <!-- Dropdowns and Fields for User, Vendor, and Facility Spaces -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="user_or_vendor">Assign to</label>
                            <select class="form-control" id="user_or_vendor" name="user_or_vendor" onchange="toggleUserOrVendor()">
                                <option value="">Select Option</option>
                                <option value="user" {{ old('user_or_vendor') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="vendor" {{ old('user_or_vendor') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                <option value="facility" {{ old('user_or_vendor') == 'facility' ? 'selected' : '' }}>Facility Spaces</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3" id="current_user_field" style="display: none;">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="user_id">User</label>
                            <select class="form-control select2 @error('user_id') is-invalid @enderror" id="user_id" name="user_id" style="width: 100%;">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3" id="vendor_field" style="display: none;">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="vendor">Vendor</label>
                            <input type="text" id="vendor" class="form-control @error('vendor') is-invalid @enderror" name="vendor" value="{{ old('vendor') }}">
                            @error('vendor')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3" id="facility_field" style="display: none;">
    <div class="card-body">
        <div class="row">
            <div class="form-group col-12">
                <label for="facility_id">Facility Space</label>
                <select class="form-control select2 @error('facility_id') is-invalid @enderror" 
                        id="facility_id" 
                        name="facility_id" 
                        style="width: 100%;">
                    <option value="">Select Facility Space</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>{{ $facility->facility_name }}</option>
                    @endforeach
                </select>
                @error('facility_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>


            <div class="card mb-3" id="previous_user_field">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="previous_user_id">Previous User</label>
                            <select class="form-control select2 @error('previous_user_id') is-invalid @enderror" id="previous_user_id" name="previous_user_id">
                                <option value="">Select Previous User</option>
                                <option value="other">Other</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('previous_user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('previous_user_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="status">Status</label>
                            <select class="form-control select2" id="status" name="status">
                                <option value="">Select Status</option>
                                <option value="New" {{ old('status') == 'New' ? 'selected' : '' }}>New</option>
                                <option value="Old" {{ old('status') == 'Old' ? 'selected' : '' }}>Old</option>
                                <option value="WrittenOff" {{ old('status') == 'WrittenOff' ? 'selected' : '' }}>Written Off</option>
                                <option value="Broken" {{ old('status') == 'Broken' ? 'selected' : '' }}>Broken</option>
                                <option value="In Storage" {{ old('status') == 'In Storage' ? 'selected' : '' }}>In Storage</option>
                            </select>
                            @error('status')
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        function toggleUserOrVendor() {
            const selectedOption = document.getElementById('user_or_vendor').value;
            document.getElementById('current_user_field').style.display = (selectedOption === 'user') ? 'block' : 'none';
            document.getElementById('vendor_field').style.display = (selectedOption === 'vendor') ? 'block' : 'none';
            document.getElementById('facility_field').style.display = (selectedOption === 'facility') ? 'block' : 'none';
        }

        $(document).ready(function() {


            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
    <br><br><br><br><br><br><br><br>

</x-app-layout>
