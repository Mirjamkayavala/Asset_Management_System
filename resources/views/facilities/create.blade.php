<x-app-layout>
    <div class="container">
        <h6>Create Facility</h6>
        <form action="{{ route('facilities.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="facility_name" class="form-label">Facility Name</label>
                <input type="text" name="facility_name" class="form-control" id="facility_name" required>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
        </form>
    </div>
</x-app-layout>