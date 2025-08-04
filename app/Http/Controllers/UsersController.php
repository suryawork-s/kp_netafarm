<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use App\Models\UserLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['position', 'department'])->get();

        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();

        return view('pages.users.create', compact('departments', 'positions', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'position_id' => 'required',
            'department_id' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'join_year' => 'required',
            'password' => 'min:8',
            'repeat_password' => 'same:password',
            'role' => 'required',
        ]);

        $user = new User();
        $user->position_id = $request->position_id;
        $user->department_id = $request->department_id;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->join_year = $request->join_year;
        $user->status = 'active';
        $user->password = bcrypt($request->password);
        $user->save();

        $user->assignRole($request->role);

        return redirect()->route('dashboard.users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['position', 'department'])->find($id);

        $leaves = UserLeave::where('user_id', $id)->where('status', 'active')->where('year', date('Y'))->first();

        return response()->json(['user' => $user, 'leaves' => $leaves]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with(['position', 'department', 'leaves'])->find($id);
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();

        return view('pages.users.edit', compact('user', 'departments', 'positions', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'position_id' => 'required',
            'department_id' => 'required',
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required',
            'join_year' => 'required',
            'role' => 'required',
        ]);

        $user = User::find($id);

        $user->position_id = $request->position_id;
        $user->department_id = $request->department_id;
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->join_year = $request->join_year;
        $user->status = $request->status;
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('dashboard.users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (Auth::user()->id == $id) {
            return response()->json(['message' => 'You cannot delete yourself'], 400);
        }
        $user = User::find($id);

        if ($user) {
            $user->forceDelete(); // Pastikan Anda ingin menghapus secara permanen
            return response()->json(['message' => 'User deleted successfully'], 200);
        }

        return response()->json(['message' => 'User not found'], 404);
    }
}
