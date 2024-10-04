<x-app-layout >
<div class="container">
    <h6>Create Asset Category</h6>
    <form action="{{ route('asset_categories.store') }}" method="POST">
        @csrf
        <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="category_name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name" name="category_name" required>
                        </div>
                        @error('category_name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
        </div>
        <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <label for="category_code" class="form-label">Category Code</label>
                            <input type="text" class="form-control" id="category_code" name="category_code" required>
                        </div>
                        @error('category_code')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

</x-app-layout>
