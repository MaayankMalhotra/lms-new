<?php

namespace App\Http\Controllers;

use App\Models\InternshipBatch;
use App\Models\InternshipClass;
use App\Models\InternshipRecordingCourse;
use Illuminate\Http\Request;

class AdminInternshipClassCreateController extends Controller
{
    public function create()
    {
        $batches = InternshipBatch::all();
        $recordingCourses = InternshipRecordingCourse::all();
        return view('admin.internship_classes.create',compact('batches','recordingCourses'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'batch_id' => 'required|exists:internship_batches,id',
        //     'class_date_time' => 'required|date',
        //     'link' => 'required|url',
        //     'thumbnail' => 'nullable|image|max:2048',
        //     'subject' => 'required|string|max:255',
        //     'recording_id' => 'nullable|exists:internship_recordings,id'
        // ]);
    
        $data = $request->only(['batch_id', 'class_date_time', 'link','subject','recording_id']);
    
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('class_thumbnails', 'public');
        }
    
        InternshipClass::create($data);
    
        return redirect()->route('admin.internship.class.index')->with('success', 'Class created and assigned to batch successfully.');
    }

     // Show the list of internship classes
     public function index()
     {
         $internshipClasses = InternshipClass::with('batch') // If you need batch details
             ->paginate(10);  // Pagination for better performance
         return view('admin.internship_classes.index', compact('internshipClasses'));
     }

      // Show the form to edit an internship class
      public function edit($id)
      {
          $internshipClass = InternshipClass::findOrFail($id);
          $batches = InternshipBatch::all();  // Fetch all batches
      
          return view('admin.internship_classes.edit', compact('internshipClass', 'batches'));
      }

      public function update(Request $request, $id)
      {
          $request->validate([
              'class_date_time' => 'required|date',
              'link' => 'required|url',
              'thumbnail' => 'nullable|file|image|max:2048',
              'subject' => 'required|string|max:255',
          ]);
    
          $internshipClass = InternshipClass::findOrFail($id);
          $internshipClass->class_date_time = $request->class_date_time;
          $internshipClass->link = $request->link;
          $internshipClass->status = $request->status;
          $internshipClass->status = $request->subject;
    
          // Handle thumbnail upload
          if ($request->hasFile('thumbnail')) {
              $thumbnailPath = $request->file('thumbnail')->store('class_thumbnails', 'public');
              $internshipClass->thumbnail = $thumbnailPath;
          }
    
          $internshipClass->save();
          return redirect()->route('admin.internship.class.index')->with('success', 'Internship class updated successfully');
      }
      

      // Handle the delete of internship class
      public function destroy($id)
      {
          $internshipClass = InternshipClass::findOrFail($id);
          $internshipClass->delete();
          return redirect()->route('admin.internship.class.index')->with('success', 'Internship class deleted successfully');
      }

      public function addNotes(Request $request, $id)
    {
        $request->validate([
            'links.*.name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
        ]);

        $internshipClass = InternshipClass::findOrFail($id);
        $existingNotes = $internshipClass->notes ?? []; // Get existing notes or empty array
        $newLinks = $request->links; // Array of {name, url} objects

        // Append new links to existing ones
        $updatedNotes = array_merge($existingNotes, $newLinks);

        $internshipClass->update([
            'notes' => $updatedNotes,
        ]);

        return redirect()->back()->with('success', 'Notes links added successfully.');
    }

    public function addNotes2(Request $request, $id)
    {
        $request->validate([
            'links.*.name' => 'required|string|max:255',
            'links.*.url' => 'required|url',
        ]);

        $internshipClass = InternshipClass::findOrFail($id);
        $existingAssignments = $internshipClass->notes_2 ?? []; // Get existing assignments or empty array
        $newLinks = $request->links; // Array of {name, url} objects

        // Append new links to existing ones
        $updatedAssignments = array_merge($existingAssignments, $newLinks);

        $internshipClass->update([
            'notes_2' => $updatedAssignments,
        ]);

        return redirect()->back()->with('success', 'Assignment links added successfully.');
    }
    
}
