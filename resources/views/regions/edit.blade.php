<x-app-layout>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h6>Edit Region</h6>
                </div>
                @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                @endif
                <div class="card-body">
                    <form id="editRegionForm" action="{{ route('regions.update', $region->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="edit_region_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_region_name" name="region_name" value="{{ old('region_name', $region->region_name) }}" required>
                            @error('region_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit_zipcode" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="edit_zipcode" name="zipcode" value="{{ old('zipcode', $region->zipcode) }}" required>
                            @error('zipcode')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('regions.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>
