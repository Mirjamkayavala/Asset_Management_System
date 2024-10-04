<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Asset;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use Illuminate\Support\Facades\Gate;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (Gate::denies('viewAny', Department::class)) {
            abort(403, 'Unauthorized');
        }


        $query = Department::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('department_name', 'like', "%{$search}%")
                  ->orWhere('department_code', 'like', "%{$search}%");
        }

        $departments = Department::paginate(10);

        $users = User::all();
        $assets = Asset::all();

        return view('departments.index', compact('departments', 'users', 'assets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Gate::denies('create', Department::class)) {
            abort(403, 'Unauthorized');
        }

        Gate::authorize('create', Department::class);

        $users = User::all();
        $assets = Asset::all();
        return view('departments.create', compact('users', 'assets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request)
    {
        if (Gate::denies('create', Department::class)) {
            abort(403, 'Unauthorized');
        }

       

        Department::create($request->all());

        return redirect()->route('departments.index') ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        if (Gate::denies('view', $department)) {
            abort(403, 'Unauthorized');
        }
        return view('departments.show', compact('department'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        Gate::authorize('edit', $department);
       
        $assets = Asset::all();
        return view('departments.edit', compact('department', 'assets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department)
    {
        

        Gate::authorize('update', $department);

        $department->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        // if (Gate::denies('delete', $department)) {
        //     abort(403, 'Unauthorized');
        // }
        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }

    

}
