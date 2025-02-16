<x-app-layout>
    <h6 class="text-left">Roles and Permissions</h6>
    @can('create', App\Models\Role::class)
    <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createRoleModal">
                    Create Role
                </button>
            </div>
        </div>
    </section>
    @endcan
<!-- <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createRoleModal">Create Role</button> -->
    <div class="container mt-5">
        
        
        <!-- Roles Section -->
        <h6>Roles</h6>
        @if (session('success'))
            <div class="alert alert-success no-print">
                {{ session('success') }}
            </div>
        @endif
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->role_name }}</td>
                       
                        <td>
                            @can('delete', $role)
                            <a href="{{ route('roles_permissions.destroy_role', $role->id) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> 
                            </a>
                            @endcan
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
        
        <!-- Permissions Section -->
        <h6>Permissions</h6>
        @if (session('success'))
            <div class="alert alert-success no-print">
                {{ session('success') }}
            </div>
        @endif
        @can('create', App\Models\Permission::class)
        <section class="container mt-5">
        <div class="card">
            <div class="card-body text-left">
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createPermissionModal">
                    Create Permission
                </button>
            </div>
        </div>
        </section>
        @endcan
        <br>
        <br>
        <!-- <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#createPermissionModal">Create Permission</button> -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        
                        <td>
                            @can('delete', $permission)
                            <a href="{{ route('roles_permissions.destroy_permission', $permission->id) }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> 
                            </a>
                            @endcan
                           
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
    </div>
    
    <!-- Create Role Modal -->
    <div class="modal fade" id="createRoleModal" tabindex="-1" aria-labelledby="createRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoleModalLabel">Create Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('roles_permissions.store_role') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="roleName">Role Name</label>
                            <input type="text" class="form-control" id="roleName" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Role</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Create Permission Modal -->
    <div class="modal fade" id="createPermissionModal" tabindex="-1" aria-labelledby="createPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPermissionModalLabel">Create Permission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('roles_permissions.store_permission') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="permissionName">Permission Name</label>
                            <input type="text" class="form-control" id="permissionName" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create Permission</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.querySelectorAll('.dropdown-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                this.nextElementSibling.classList.toggle('show');
            });
        });
    </script>
    
    </x-app-layout>