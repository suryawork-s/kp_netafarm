<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveAttachment;
use App\Models\User;
use App\Models\UserLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($type = null, $status = null)
    {
        $leaves = Leave::with('user')
            ->when($type, function ($query) use ($type) {
                if ($type === 'sakit' || $type === 'izin') {
                    // Kondisi khusus untuk type sakit atau izin
                    return $query->whereIn('type', ['sakit', 'izin']);
                }
                // Kondisi umum untuk type lainnya
                return $query->where('type', $type);
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->get();

        return view('pages.leaves.index', compact('leaves', 'type', 'status'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($type = null)
    {
        $user = Auth::user();

        // leaders selain auth user
        $leaders = User::where('id', '!=', Auth::user()->id)->get();

        return view('pages.leaves.create', compact('user', 'type', 'leaders'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(array_merge([
            'user_id' => 'required',
            'leader_id' => 'required',
            'type' => 'required',
            'description' => 'required',
        ], $request->type === 'lembur' ? [
            'date' => 'required|date',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
        ] : [
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]));


        // Buat instance Leave baru
        $leave = new Leave();
        $leave->user_id = $request->user_id;
        $leave->leader_id = $request->leader_id;
        $leave->type = $request->type;
        $leave->date = date('Y-m-d');

        // Set start dan end berdasarkan type leave
        if ($request->type == 'lembur') {
            // Format tanggal dan waktu untuk leave type 'lembur'
            $leave->start = $request->date . ' ' . $request->start;
            $leave->end = $request->date . ' ' . $request->end;
        } else {
            // Format tanggal dan waktu untuk leave type lainnya
            $leave->start = $request->start;
            $leave->end = $request->end;
        }

        $leave->description = $request->description;
        $leave->status = 'pending';
        $leave->save();

        // Hitung total hari cuti berdasarkan tanggal
        $total_hari_cuti = (new \Carbon\Carbon($request->start))->diffInDays(new \Carbon\Carbon($request->end)) + 1;

        // Jika hanya satu hari cuti (tanggal yang sama)
        if ($request->start === $request->end) {
            $total_hari_cuti = 1; // Hanya satu hari cuti jika start dan end sama
        }

        // Ambil data UserLeave untuk pengguna yang bersangkutan
        $user_leave = UserLeave::where('user_id', $request->user_id)->where('year', date('Y'))->first();

        if (!$user_leave) {
            return redirect()->route('dashboard.leaves.index')->with('success', 'Data cuti berhasil disimpan');
        }

        // Perbarui jumlah cuti yang digunakan dan sisa cuti
        if ($user_leave) {
            // Tambahkan used dengan total hari cuti yang diambil
            $user_leave->used += $total_hari_cuti;

            // Kurangi remaining dengan total hari cuti yang diambil
            if ($user_leave->remaining >= $total_hari_cuti) {
                $user_leave->remaining -= $total_hari_cuti;
                $user_leave->save();
            } else {
                return redirect()->back()->with('error', 'Sisa cuti tidak cukup');
            }
        }

        // Proses lampiran jika tipe cuti adalah sakit
        if ($request->type == 'sakit' && $request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $path = $attachment->storeAs('attachments', $attachment->getClientOriginalName(), 'public');

            // Simpan lampiran ke database
            LeaveAttachment::create([
                'leave_id' => $leave->id,
                'path' => $path,
            ]);
        }

        // Redirect berdasarkan tipe cuti
        switch ($leave->type) {
            case 'cuti':
                return redirect()->route('dashboard.leaves.index', ['type' => 'cuti'])->with('success', 'Cuti berhasil dibuat');
            case 'sakit':
                return redirect()->route('dashboard.leaves.index', ['type' => 'sakit'])->with('success', 'Cuti sakit berhasil dibuat');
            case 'izin':
                return redirect()->route('dashboard.leaves.index', ['type' => 'izin'])->with('success', 'Izin berhasil dibuat');
            case 'lembur':
                return redirect()->route('dashboard.leaves.index', ['type' => 'lembur'])->with('success', 'Lembur berhasil dibuat');
            case 'perjalanan-dinas':
                return redirect()->route('dashboard.leaves.index', ['type' => 'perjalanan-dinas'])->with('success', 'Izin perjalanan dinas berhasil dibuat');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $leave = Leave::findOrFail($id);
        return view('pages.leave.show', compact('leave'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect()->route('dashboard.report.index')->with('success', 'Data berhasil dihapus.');
    }

    public function updateStatus(Request $request)
    {
        $leave = Leave::find($request->id);
        $leave->status = $request->status;
        $leave->save();

        return redirect()->back()->with('success', 'Status berhasil diupdate');
    }

    public function print(string $id) {}
}
