<x-app-layout>
    <div class="container">
        <h6>Asset Details</h6>
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <section class="container mt-5">
            <div class="card">
                <div class="card-body text-left">
                    <a href="{{ route('assets.itControlForm', $asset->id) }}" class="btn btn-outline-primary" target="_blank">
                        IT Control Form
                    </a>
                </div>
            </div>
        </section>
        <br><br><br>
        
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="make">Make</label>
                        <input type="text" class="form-control" id="make" value="{{ $asset->make }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="model">Model</label>
                        <input type="text" class="form-control" id="model" value="{{ $asset->model }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="serial_number">Serial Number</label>
                        <input type="text" class="form-control" id="serial_number" value="{{ $asset->serial_number }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="asset_number">Asset Number</label>
                        <input type="text" class="form-control" id="asset_number" value="{{ $asset->asset_number }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" value="{{ $asset->assetCategory->category_name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price">Price</label>
                        <input type="number" class="form-control" id="price" value="{{ $asset->price }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="location_id">Location</label>
                        <input type="text" class="form-control" id="location_id" 
                            value="{{ $asset->locations->location_name ?? 'N/A' }}" readonly> 
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="user_or_vendor">Assigned to</label>
                        <input type="text" class="form-control" id="user_or_vendor" 
                            value="{{ $asset->user ? $asset->user->name . ' (User)' : ($asset->vendor ? $asset->vendor . ' (Vendor)' : ($asset->facility_id->facility_name ?? 'N/A')) }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="previous_user_id">Previous User</label>
                        <input type="text" class="form-control" id="previous_user_id" 
                            value="{{ $asset->previous_user_id ? $asset->previousUser->name : $asset->manual_previous_user }}" readonly> 
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label for="status">Status</label>
                        <input type="text" class="form-control" id="status" value="{{ $asset->status }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('assets.index') }}" class="btn btn-secondary mr-2">Back</a>
            <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
            </a>
            @can('delete', [App\Models\Asset::class, $asset])
            <form action="{{ route('assets.destroy', $asset->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this asset?');">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
            @endcan
        </div>
    </div>
    <br><br><br><br>
</x-app-layout>
