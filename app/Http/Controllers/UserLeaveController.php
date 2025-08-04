<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserLeave;
use Illuminate\Http\Request;

class UserLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'year' => 'required',
            'total' => 'required',
        ]);

        $userLeave = User::find($user_id);

        $userLeave = new UserLeave();

        $userLeave->user_id = $user_id;
        $userLeave->year = $request->year;
        $userLeave->total = $request->total;
        $userLeave->used = 0;
        $userLeave->remaining = $userLeave->total;
        $userLeave->status = 'active';

        $userLeave->save();

        return redirect()->back()->with('success', 'Data cuti berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leave = UserLeave::find($id);
        return response()->json($leave);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'year' => 'required',
            'total' => 'required',
        ]);

        $userLeave = UserLeave::find($id);
        $userLeave->year = $request->year;
        $userLeave->total = $request->total;
        $userLeave->used = $request->used;
        $userLeave->remaining = $request->remaining;
        $userLeave->status = 'active';
        $userLeave->save();

        return redirect()->back()->with('success', 'Data cuti berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userLeave = UserLeave::find($id);
        $userLeave->delete();

        return redirect()->back()->with('success', 'Data cuti berhasil dihapus');
    }
}
