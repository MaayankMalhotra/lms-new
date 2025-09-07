<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{
    public function showUploadForm()
    {
        return view('admin.image-upload-link-generation');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Store the file in the correct path
                $path = $file->storeAs('uploads', $fileName, 'public');
                
       
                // Generate the correct URL using storage_path
                $url = 'think-champ.com/thinkchampion/storage/app/public/' . $path;
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded successfully',
                    'url' => $url,
                    'name' => $fileName
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}