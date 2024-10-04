<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

        $users = User::paginate(100);
        $roles = Role::all();
        $departments = Department::all();
        return view('users.index', compact('users', 'roles', 'departments'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $user->assignRole($role);
        $roles = Role::all(); // Fetch all roles
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'contact_number' => 'required|string|max:12|unique:users',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $user= User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'contact_number' => $request->contact_number,
            'role_id' => $request->role_id,
            'department_id' => $request->department_id,
        ]);


        $user->assignRole($request->role_id);
        

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        // Retrieve the user first
        $user = User::with('department', 'roles')->findOrFail($id);

        // Authorize the user after retrieval
        $this->authorize('view', $user);

        // Return the view with the user
        return view('users.show', compact('user'));
    }


    public function edit(User $user)
    {
        $this->authorize('edit', $user);
        $roles = Role::all();
        $departments = Department::all();
        return view('users.edit', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        // Validate the incoming request
        $request->validate([
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'contact_number' => 'nullable|string|max:12|unique:users,contact_number,' . $user->id,
            'role_id' => 'nullable|exists:roles,id', 
            'department_id' => 'required|exists:departments,id',
        ]);

        // Update the user's information
        $user->update([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'contact_number' => $request->contact_number,
            'department_id' => $request->department_id,
            'role_id' => $request->role_id,
        ]);

        
        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    public function destroy(User $user)
    {

        // Or update the assets to remove the user association
        $user->assets()->update(['user_id' => null]); // Set user_id to null instead
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
