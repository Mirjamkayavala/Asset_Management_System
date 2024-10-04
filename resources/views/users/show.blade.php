<x-app-layout>
    <div class="container">
        <h6>User Details</h6>
        <table class="table table-bordered">
            <tr>
                <th>ID</th>
                <td>{{ $user->id }}</td>
            </tr>
            <tr>
                <th>Username</th>
                <td>{{ $user->username }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Contact Number</th>
                <td>{{ $user->contact_number }}</td>
            </tr>
            <tr>
                <th>Department</th>
                <td>{{ $user->department_id ? $user->department->department_name : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Roles</th>
                <td>{{ $user->role_id ? $user->role->role_name : 'N/A' }}</td>
                <!-- <td>
                    @if($user->roles->isEmpty())
                        No Roles Assigned
                    @else
                        <ul>
                            @foreach($user->roles as $role)
                                @if($role)
                                    <li>{{ $role->role_name }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </td> -->
            </tr>
        </table>
        <a href="{{ route('users.index') }}" class="btn btn-primary">Back</a>
    </div>
</x-app-layout>
