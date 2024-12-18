<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('roles_permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Assign role to user and update permissions.
     */
    public function assignRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:admin,technician,costing_department,viewer,normal_user',
        ]);

        $user = Auth::user();
        
        // Find the role the user is switching to
        $role = Role::where('role_name', $request->role)->first();

        if (!$role) {
            return redirect()->back()->withErrors(['role' => 'Invalid role selected']);
        }

        // Remove user's current permissions
        $user->permissions()->detach();

        // Assign new role to the user
        $user->role = $role->role_name;
        $user->save();

        // Get the permissions associated with the new role
        $rolePermissions = $role->permissions;

        // Assign new permissions to the user
        foreach ($rolePermissions as $permission) {
            $user->permissions()->attach($permission->id);
        }

        return redirect()->route('dashboard')->with('success', 'Role and permissions updated successfully');
    }

    /**
     * Show the form for creating a new role.
     */
    public function createRole()
    {
        $this->authorize('create', Role::class);
        $permissions = Permission::all();

        return view('roles_permissions.create_role', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'role_name' => 'required|unique:roles,role_name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['role_name' => $request->role_name]);

        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return redirect()->route('roles_permissions.index')->with('success', 'Role created successfully');
    }

    /**
     * Show the form for creating a new permission.
     */
    public function createPermission()
    {
        $this->authorize('create', Permission::class);
        return view('roles_permissions.create_permission');
    }

    /**
     * Store a newly created permission in storage.
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->route('roles_permissions.index')->with('success', 'Permission created successfully');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        $this->authorize('delete', $role);
        $role->delete();

        return redirect()->route('roles_permissions.index')->with('success', 'Role deleted successfully');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroyPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $this->authorize('delete', $permission);
        $permission->delete();

        return redirect()->route('roles_permissions.index')->with('success', 'Permission deleted successfully');
    }

    // public function assignRole(Request $request, User $user)
    // {
    //     $request->validate(['role_id' => 'required|exists:roles,id']);
    //     $user->role_id = $request->role_id;
    //     $user->save();

    //     return redirect()->back()->with('success', 'Role assigned successfully.');
    // }
}
