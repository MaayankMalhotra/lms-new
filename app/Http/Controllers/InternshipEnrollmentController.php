<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipBatch;
use App\Models\InternshipClass;
use App\Models\InternshipEnrollment;
use Illuminate\Http\Request;

class InternshipEnrollmentController extends Controller
{
    public function assignBatchView(){
        $batches = InternshipBatch::all();
        $students = InternshipEnrollment::all();
        return view('admin.internship-enrollments.assign-view', compact('batches', 'students'));
    }

    public function assignStudentsToBatch(Request $request)
{
    $request->validate([
        'batch_id' => 'required|exists:internship_batches,id',
        'student_ids' => 'required|array',
        'student_ids.*' => 'exists:internship_enrollments,id',
    ]);
    $batch = InternshipBatch::findOrFail($request->batch_id);
    $batch->students()->syncWithoutDetaching($request->student_ids);
    

    return redirect()->back()->with('success', 'Students assigned to batch successfully.');
}

public function viewEnrollments(Request $request)
{
    $query = InternshipEnrollment::query();

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('internship_id')) {
        $query->where('internship_id', $request->internship_id);
    }

    $enrollments = $query->paginate(10);

    $internships = Internship::all();

    return view('admin.internship-enrollments.index', compact('enrollments', 'internships'));
}
public function toggleEnrollmentStatus($id)
{
    $enrollment = InternshipEnrollment::findOrFail($id);
    $enrollment->status = $enrollment->status === 'active' ? 'inactive' : 'active';
    $enrollment->save();

    return redirect()->back()->with('success', 'Enrollment status updated successfully.');
}

public function edit($id)
{
    $enrollment = InternshipEnrollment::findOrFail($id);

    return response()->json([
        'name' => $enrollment->name,
        'phone' => $enrollment->phone,
        'amount' => $enrollment->amount,
    ]);
}

public function update(Request $request, $id)
{
    $enrollment = InternshipEnrollment::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'amount' => 'required|numeric|min:0',
    ]);

    $enrollment->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'amount' => $request->amount,
    ]);

    return redirect()->route('admin.internship-enrollment-view')
        ->with('success', 'Enrollment updated successfully.');
}


}
