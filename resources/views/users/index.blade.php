<x-app-layout>
    <h6>Employees Details</h6>

    @can('create', App\Models\User::class)
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    Add Employee
                </button>
            </div>
        </div>
    </section>
    @endcan
    <br><br>

    <div class="container">
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

        <div class="row">
            @foreach ($users as $user)
                <div class="col-md-2 mb-4">
                    <div class="card shadow-sm position-relative">
                        <!-- Three-dot action button -->
                        <div class="position-absolute top-0 end-0 mt-2 me-2">
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    @can('view', $user)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('users.show', ['user' => $user->id]) }}">
                                                <i class="fas fa-eye me-2"></i> View
                                            </a>
                                        </li>
                                    @endcan

                                    @can('update', $user)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('users.edit', ['user' => $user->id]) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                    @endcan

                                    @can('delete', $user)
                                        <li>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="fas fa-trash-alt me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                        </div>

                        <!-- User image and name -->
                        <div class="card-body text-center">
                            <img src="{{ asset('images/image.png') }}" alt="User Image" class="rounded-circle mb-3" width="50" height="50">
                            <h5 class="card-title">{{ $user->name }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination links -->
        {{ $users->links() }}
    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Add Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="department_id" class="form-label">Department</label>
                            <select class="form-select select2" id="department_id" name="department_id">
                                <option value="" selected>Select a Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="role_id" class="form-label">Role</label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <br><br><br><br>
</x-app-layout>
