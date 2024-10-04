<x-app-layout>
    <h4>Departments</h4>
    @can('create', App\Models\Department::class)
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <button type="button" class="btn btn-primary btn-create" data-bs-toggle="modal" data-bs-target="#createDepartmentModal">
                    Create Department
                </button>
            </div>
        </div>
    </section>
    @endcan

    <br><br><br>

    <div class="table-container">
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

        @if ($departments->isEmpty())
            <div class="alert alert-info">
                No data available.
            </div>
        @else
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Department Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td>{{ $department->department_name }}</td>
                            <td>{{ $department->department_code }}</td>
                            <td>
                                @can('edit', $department)    
                                <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                                
                                @can('delete', $department)
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">
                {{ $departments->links() }}
            </div>
        @endif
    </div>

    <!-- Create Department Modal -->
    <div class="modal fade" id="createDepartmentModal" tabindex="-1" aria-labelledby="createDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDepartmentModalLabel">Create Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="department_name" class="form-label">Department Name</label>
                            <input type="text" class="form-control" id="department_name" name="department_name" value="{{ old('department_name') }}" required>
                            @error('department_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="department_code" class="form-label">Department Code</label>
                            <input type="text" class="form-control" id="department_code" name="department_code" value="{{ old('department_code') }}" required>
                            @error('department_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-labelledby="editDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDepartmentModalLabel">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editDepartmentForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_department_name" class="form-label">Department Name</label>
                            <input type="text" class="form-control" id="edit_department_name" name="department_name" required>
                            @error('department_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_department_code" class="form-label">Department Code</label>
                            <input type="text" class="form-control" id="edit_department_code" name="department_code" required>
                            @error('department_code')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update Department</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle edit button click
            document.querySelectorAll('.btn-edit').forEach(function(button) {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const code = this.getAttribute('data-code');

                    document.getElementById('edit_department_name').value = name;
                    document.getElementById('edit_department_code').value = code;
                    
                    // Update form action URL
                    const form = document.getElementById('editDepartmentForm');
                    form.action = `{{ url('departments') }}/${id}`;
                });
            });

            // Handle form submission for editing department
            document.getElementById('editDepartmentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const actionUrl = form.action;
                const formData = new FormData(form);

                fetch(actionUrl, {
                    method: 'POST', // Use POST method for Laravel compatibility
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'X-HTTP-Method-Override': 'PUT', // Use X-HTTP-Method-Override for PUT request
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    // On success, close the modal and possibly refresh or redirect
                    if (data.success) {
                        $('#editDepartmentModal').modal('hide'); // Close the modal
                        // Optionally, you can redirect to the index page or refresh the department list
                        window.location.href = '{{ route('departments.index') }}'; // Redirect to index page
                    } else {
                        // Handle validation errors or other errors
                        console.error(data.errors);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</x-app-layout>
