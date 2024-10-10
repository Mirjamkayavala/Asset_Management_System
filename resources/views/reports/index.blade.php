<x-app-layout>
    <div class="container">
        <h6>Assets Report</h6>
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
                <select name="make" id="make">
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
                <select name="model" id="model">
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
            <select name="location_id" id="location_id">
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
                <label for="asset_number">Asset Number:</label>
                <input type="text" name="asset_number" id="asset_number" value="{{ request('asset_number') }}">
            </div>

            <!-- Filter by Location -->
            <!-- <div>
                <label for="location_id" class="form-label">Location</label>
                <select class="form-select" id="location_id" name="location_id" required>
                    <option value="" selected>Select a location</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                    @endforeach
                </select>
            </div> -->

            <!-- Filter by Status -->
            <div>
                <label for="status">Status:</label>
                <select name="status" id="status">
                    <option value="">All</option>
                    <option value="New" {{ request('status') == 'New' ? 'selected' : '' }}>New</option>
                    <option value="In Use" {{ request('status') == 'In Use' ? 'selected' : '' }}>In Use</option>
                    <option value="Old" {{ request('status') == 'Old' ? 'selected' : '' }}>Old</option>
                    <option value="In Storage" {{ request('status') == 'In Storage' ? 'selected' : '' }}>In Storage</option>
                    <option value="Broken" {{ request('status') == 'Broken' ? 'selected' : '' }}>Broken</option>
                    <option value="Written Off" {{ request('status') == 'Written Off' ? 'selected' : '' }}>Written Off</option>
                </select>
            </div>

            <!-- Filter by Date Range -->
            <div>
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">
            </div>
            <div>
                <label for="end_date">End Date:</label>
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
                    <th>Current User</th>
                    <th>Date</th>
                    <th>Previous User</th>
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
                        <td>{{ $asset->category }}</td>
                        <td>{{ $asset->user->name ?? 'N/A' }}</td>
                        <td>{{ $asset->date }}</td>
                        <td>{{ optional($asset->previousUser)->name ?? 'N/A' }}</td>
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

    </div>
    <br><br><br><br><br>
</x-app-layout>
