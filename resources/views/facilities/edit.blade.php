<x-app-layout>
    <div class="container">
        <h6>Edit Facility</h6>
        <form action="{{ route('facilities.update', $facility) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="facility_name" class="form-label">Facility Name</label>
                <input type="text" name="facility_name" class="form-control" id="facility_name" value="{{ $facility->facility_name }}" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
        </form>
    </div>
</x-app-layout>