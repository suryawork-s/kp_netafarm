<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::all();

        return view('pages.department.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:departments',
        ]);

        $department = new Department();
        $department->name = $request->name;
        $department->save();

        return response()->json([
            'success' => true,
            'message' => 'Department created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Show department_position when department_id = $id
        $department = Department::find($id);
        $active = $department->positions;

        $positions = Position::all();
        return response()->json([
            'active' => $active,
            'positions' => $positions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::find($id);

        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:departments,name,' . $id,
        ]);

        $department = Department::find($id);
        $department->name = $request->name;
        $department->save();

        return response()->json([
            'success' => true,
            'message' => 'Department updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::find($id);
        $department->forceDelete();

        return json_encode($department);
    }

    public function getData()
    {
        $departments = Department::all();
        return json_encode($departments);
    }

    // public function syncPositions(Request $request)
    // {
    //     $department = Department::find($request->id);
    //     $department->positions()->sync($request->positions);
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Position updated successfully.'
    //     ]);
    // }
}
