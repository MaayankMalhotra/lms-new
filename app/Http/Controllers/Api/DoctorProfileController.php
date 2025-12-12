<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DoctorProfileController extends Controller
{
    /**
     * Return a doctor's profile by query param doctor_id.
     */
    public function show(Request $request)
    {
        $doctorId = $request->query('doctor_id');
        if (!$doctorId) {
            return response()->json(['message' => 'doctor_id is required'], 422);
        }

        $doctor = User::find($doctorId);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json([
            'data' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'college_company' => $doctor->college_company,
                'qualification' => $doctor->qualification,
                'profile_image' => $doctor->profile_image
                    ? Storage::disk('public')->url($doctor->profile_image)
                    : null,
            ],
        ]);
    }

    /**
     * Update a doctor's profile by query param doctor_id.
     */
    public function update(Request $request)
    {
        $doctorId = $request->query('doctor_id');
        if (!$doctorId) {
            return response()->json(['message' => 'doctor_id is required'], 422);
        }

        $doctor = User::find($doctorId);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($doctor->id),
            ],
            'phone' => ['sometimes', 'required', 'regex:/^[0-9]{10}$/'],
            'college_company' => 'sometimes|nullable|string|max:255',
            'qualification' => 'sometimes|nullable|string|max:255',
            'profile_image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validated['profile_image'] = $path;
        } else {
            unset($validated['profile_image']);
        }

        $doctor->fill($validated);
        $doctor->save();

        return response()->json([
            'message' => 'Profile updated successfully.',
            'data' => [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'college_company' => $doctor->college_company,
                'qualification' => $doctor->qualification,
                'profile_image' => $doctor->profile_image
                    ? Storage::disk('public')->url($doctor->profile_image)
                    : null,
            ],
        ]);
    }
}
