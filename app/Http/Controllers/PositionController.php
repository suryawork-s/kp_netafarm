<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Position::with('departments')->get();

        return view('pages.positions.index', compact('positions'));
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
            'name' => 'required|unique:positions',
        ]);

        $position = new Position();
        $position->name = $request->name;
        $position->save();

        return response()->json([
            'success' => true,
            'message' => 'Poistion created successfully.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $position = Position::find($id);

        return response()->json($position);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:positions,name,' . $id,
        ]);;
        $position = Position::find($id);
        $position->name = $request->name;
        $position->save();

        return response()->json([
            'success' => true,
            'message' => 'Position updated successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $position = Position::find($id);
        $position->forceDelete();

        return json_encode($position);
    }

    public function getData()
    {
        $positions = Position::all();
        return json_encode($positions);
    }
}
