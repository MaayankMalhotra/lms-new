<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function studentAttendance(Request $request)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to view attendance.');
        }

        $student = auth()->user()->id; // Get the authenticated student's ID
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $attendance = DB::table('attendance')
            ->where('user_id', $student)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $leaves = DB::table('leaves')
            ->where('user_id', $student)
            ->whereBetween('leave_date', [$startDate, $endDate])
            ->get();

        return view('student.leave.leave_apply', compact('attendance', 'leaves', 'month', 'year', 'student'));
    }

    public function applyLeave(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to apply for a leave.');
        }

    DB::table('leaves')->insert([
            'user_id' => auth()->user()->id,
            'leave_date' => $request->leave_date,
            'reason' => $request->reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave application submitted successfully');
    }

    public function adminLeaves(Request $request)
    {
        $leaves = DB::table('leaves')
        ->join('users', 'leaves.user_id', '=', 'users.id') // Join with users table
        ->join('enrollments', 'leaves.user_id', '=', 'enrollments.user_id') // Join with enrollments table
        ->join('batches', 'enrollments.batch_id', '=', 'batches.id') // Join with batches table
        ->where('batches.teacher_id', auth()->user()->id) // Filter for batches where the authenticated user is the teacher
        ->select('leaves.*', 'users.*') // Include student details
        ->orderBy('leaves.created_at', 'desc')
        ->paginate(10); // Add pagination
            // ->get();
          //   dd($leaves);

        return view('admin.leave.leave', compact('leaves')); // Corrected view path
    }

//     public function approveLeave(Request $request, $leaveId)
//     {
//         // dd($request->all());
//         $leave = DB::table('leaves')->where('user_id', $request->leave_id)->pluck('id');
    
// // dd($leave);
//         $request->validate([
//             'status' => 'required|in:approved,rejected',
//         ]);

//         DB::table('leaves')
//             ->whereIn('id', $leave)
//             ->update([
//                 'status' => $request->status,
//                 'approved_by' => auth()->id(),
//                 'approved_at' => now(),
//                 'updated_at' => now(),
//             ]);

//         return redirect()->back()->with('success', "Leave {$request->status} successfully");
//     }
public function approveLeave(Request $request, $leaveId)
    {
        // dd($request->all());
        $leave = DB::table('leaves')->where('user_id', $request->leave_id)->select('id')->first();
        // dd($leave->id);
        // $request->validate([
        //     'status' => 'required|in:approved,rejected',
        // ]);
        // dd($request->all());
     DB::table('leaves')
            ->where('id', $leave->id)
            ->update([
                'status' => $request->status,
                'approved_by' => auth()->user()->id,
                'approved_at' => now(),
                'updated_at' => now(),
            ]);
        return redirect()->back()->with('success', "Leave {$request->status} successfully");
    }
}