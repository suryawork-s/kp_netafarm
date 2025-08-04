<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with(['user', 'leader']);

        // Filter by start and end date
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }


        // Filter by type
        if ($request->has('type')) {
            $query->whereIn('type', $request->type);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->whereIn('status', $request->status);
        }

        $leaves = $query->orderBy('created_at', 'desc')->get();


        return view('pages.report.index', compact('leaves'));
    }
}
