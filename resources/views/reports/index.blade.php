<x-app-layout>
    <div class="container">
        <h5>Assets Report</h5>
        <div class="statements">
            <p> All assets listed in this report are subject to periodic review and audit. For any discrepancies or updates, please contact the asset management department.</p>
        </div>
        

        <!-- Filter Form -->
        <form method="GET" action="{{ route('reports.index') }}" class="filter-form">
            <!-- Filter by Serial Number -->
            <div>
                <label for="serial_number">Serial Number:</label>
                <input type="text" name="serial_number" id="serial_number" value="{{ request('serial_number') }}">
            </div>

           <!-- Filter by Make -->
            <div>
                <label for="make">Make:</label>
                <select class= "form-control select2" name="make" id="make">
                    <option value="">All</option>
                    @foreach($makes as $make)
                        <option value="{{ $make->make }}" {{ request('make') == $make->make ? 'selected' : '' }}>
                            {{ $make->make }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Model -->
            <div>
                <label for="model">Model:</label>
                <select class= "form-control select2" name="model" id="model">
                    <option value="">All</option>
                    @foreach($models as $model)
                        <option value="{{ $model->model }}" {{ request('model') == $model->model ? 'selected' : '' }}>
                            {{ $model->model }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Location -->
            <div>
                <label for="location_id">Location:</label>
                <select class= "form-control select2" name="location_id" id="location_id">
                    <option value="">All</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->location_name }} <!-- Change this to $location->location_name if the field is named differently -->
                        </option>
                    @endforeach
                </select>
            </div>



            <!-- Filter by Asset Number -->
            <div>
                <label for="asset_number" class="form-label">Asset Number:</label>
                <input type="text" name="asset_number" id="asset_number" value="{{ request('asset_number') }}" 
                >
            </div>

            <!-- Filter by Location -->
            <div>
                <label for="vendor" class="form-label">Vendor</label>
                <select class= "form-control select2" name="vendor" id="vendor" >
                    <option value="" selected>Select a vendor</option>
                    @foreach($vendors as $vendor)
                        <option value="{{ $vendor->vendor }}" {{ request('vendor') == $vendor->vendor ? 'selected' : '' }}>
                            {{ $vendor->vendor }}
                        </option>
                    @endforeach
                </select>
            </div>
            

            <!-- Filter by Status -->
            <div>
                <label for="status" class="form-label">Status:</label>
                <select class= "form-control select2" name="status" id="status">
                    <option value="">All</option>
                    <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                    <option value="In Use" {{ request('status') == 'In Use' ? 'selected' : '' }}>In Use</option>
                    <option value="Old" {{ request('status') == 'Old' ? 'selected' : '' }}>Old</option>
                    <option value="In Storage" {{ request('status') == 'In Storage' ? 'selected' : '' }}>In Storage</option>
                    <option value="Broken" {{ request('status') == 'Broken' ? 'selected' : '' }}>Broken</option>
                    <option value="Written Off" {{ request('status') == 'Written Off' ? 'selected' : '' }}>Written Off</option>
                </select>
            </div>
            <div>
                <label for="user_id" class="form-label">Assign To:</label>
                <select class= "form-control select2" name="user_id" id="user_id">
                    <option value="">All</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} <!-- Change this to $location->location_name if the field is named differently -->
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="facility_id" class="form-label">Facility Space:</label>
                <select class= "form-control select2" name="facility_id" id="facility_id" >
                    <option value="">All</option>
                    
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                            {{ $facility->facility_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="category_id">Category:</label>
                <select class= "form-control select2" name="category_id" id="category_id">
                    <option value="">All</option>
                    @foreach($assetCategories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }} <!-- Adjust field name if it's different -->
                            <!-- {{ $location->location_name }} -->
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter by Date Range -->
            <div>
                <label for="start_date" class="form-label">Start Date:</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">
            </div>
            <div>
                <label for="end_date" class="form-label">End Date:</label>
                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}">
            </div>

            <div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
            
        </form>

        

        <!-- Export Buttons -->
        <div class="report-actions">
            <a href="{{ route('reports.preview', request()->query()) }}" class="btn btn-primary" target="_blank">Preview</a>
        </div>

        <!-- Assets Table -->
        <!-- Assets Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Asset No</th>
                    <th>Category</th>
                    <th>Assign To</th>
                    <th>Date</th>
                    <!-- <th>Previous User</th> -->
                    <th>Vendor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $asset)
                    <tr>
                        <td>{{ $asset->make }}</td>
                        <td>{{ $asset->model }}</td>
                        <td>{{ $asset->serial_number }}</td>
                        <td>{{ $asset->asset_number }}</td>
                        <td>{{ $asset->assetCategory->category_name ?? 'N/A' }}</td>
                        <td>{{ $asset->user->name ?? 'N/A' }}</td>
                        <!-- <td>{{ \Carbon\Carbon::parse($asset->date)->format('Y-m-d H:i:s') }}</td> -->
                        <td>{{ \Carbon\Carbon::parse($asset->created_at)->format('Y-m-d H:i:s') }}</td>
                        <!-- <td>{{ optional($asset->previousUser)->name ?? 'N/A' }}</td> -->
                        <td>{{ $asset->vendor }}</td>
                        <td>{{ $asset->status }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">No assets found for the selected filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            {{ $assets->links() }}
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
         $(document).ready(function() {


            $('.select2').select2({
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>
    <br><br><br><br><br>
</x-app-layout>
