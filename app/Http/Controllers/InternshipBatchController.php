<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\InternshipBatch;
use Illuminate\Http\Request;

class InternshipBatchController extends Controller
{
    public function index(Request $request)
    {
        $query = InternshipBatch::query();
    
        // Filter by internship
        if ($request->has('internship_id') && $request->internship_id != '') {
            $query->where('internship_id', $request->internship_id);
        }
    
        // Filter by batch name
        if ($request->has('batch_name') && $request->batch_name != '') {
            $query->where('batch_name', 'like', '%' . $request->batch_name . '%');
        }
    
        // Fetch batches with pagination
        $batches = $query->paginate(10);
    
        // Fetch internships for dropdown
        $internships = Internship::all();
    
        return view('admin.internship-batches.index', compact('batches', 'internships'));
    }

    public function create()
    {
        $internships = Internship::all(); 
        return view('admin.internship-batches.create', compact('internships'));
    }
    

    public function store(Request $request)
    {
        // $request->validate([
        //     'internship_id' => 'required|integer',
        //     'batch_name' => 'required|string',
        //     'start_time' => 'required|date',
        //     'end_time' => 'required|date|after:start_time',
        //     'class_schedule' => 'required|string',
        // ]);

        InternshipBatch::create($request->all());

        return redirect()->back()->with('success', 'Batch created successfully.');
    }

    public function edit($id)
    {
        $batch = InternshipBatch::findOrFail($id);
        $internships = Internship::all(); 
        return view('admin.internship-batches.edit', compact('batch','internships'));
    }

    public function update(Request $request, $id)
    {
        $batch = InternshipBatch::findOrFail($id);
        $batch->update($request->all());

        return redirect()->route('admin.internship-batches.index')->with('success', 'Batch updated.');
    }

    public function destroy($id)
    {
        InternshipBatch::destroy($id);
        return redirect()->back()->with('success', 'Batch deleted.');
    }
}
