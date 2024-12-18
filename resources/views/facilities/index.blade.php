<x-app-layout>
<div class="container">
    <h6>Facilities</h6>
    <a href="{{ route('facilities.create') }}" class="btn btn-primary">Add Facility</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Facility Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facilities as $facility)
                <tr>
                    <td>{{ $facility->id }}</td>
                    <td>{{ $facility->facility_name }}</td>
                    <td>
                        <a href="{{ route('facilities.show', $facility) }}" class="btn btn-info">View</a>
                        <a href="{{ route('facilities.edit', $facility) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('facilities.destroy', $facility) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<br><br><br>
</x-app-layout>