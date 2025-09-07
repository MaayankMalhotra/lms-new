<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Batch;
use App\Models\InternshipBatch;
use App\Models\Internship;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Models\Student;
use App\Models\Registration;
use App\Models\InternshipEnrollment;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with('course', 'teacher')->get();
        return view('admin.batch_listing', compact('batches'));
    }
   public function indexInt()
{
    $batches = InternshipBatch::with(['internship', 'teacher'])->paginate(9); // 9 cards per page
    return view('admin.batch_listing_int', compact('batches'));
}


    public function create()
    {
        $teachers = User::where('role', 2)->get();
        $courses = Course::all();
        return view('admin.add-batch', compact('teachers', 'courses'));
    }
     public function createInt()
    {
        //dd('createInt');
        $teachers = User::where('role', 2)->get();
        $courses = Internship::all();
        return view('admin.add-batch-int', compact('teachers', 'courses'));
    }

    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     Log::info('Batch store request:', $request->all());
    
    //     try {
    //         $validated = $request->validate([
    //             'start_date' => 'required|date',
    //             'status' => 'required|in:Batch Started,Upcoming,Soon',
    //             'days' => 'required',
    //             'duration' => 'required|string',
    //             'time_slot' => 'required|string',
    //             'price' => 'required|numeric|min:0',
    //             'discount_info' => 'nullable|string',
    //             'slots_available' => 'required|numeric|min:1',
    //             'slots_filled' => 'required|numeric|min:0',
    //             'course_id' => 'required|exists:courses,id',
    //             'teacher_id' => 'required|exists:users,id',
    //             'emi_available' => 'nullable|in:on,1,0,true,false',
    //             'emi_plans' => 'required_if:emi_available,on|array',
    //             'emi_plans.*.installments' => 'required_if:emi_available,on|integer|min:2',
    //             'emi_plans.*.amount' => 'required_if:emi_available,on|numeric|min:0',
    //             'emi_plans.*.interval_months' => 'required_if:emi_available,on|integer|min:1', // Add validation for interval_months
    //         ]);
    
    //         $batchData = $validated;
    //         $batchData['emi_available'] = in_array($request->emi_available, ['on', '1', 'true'], true);
    
    //         // Ensure emi_plans is set correctly
    //         if ($batchData['emi_available'] && !empty($validated['emi_plans'])) {
    //             $batchData['emi_plans'] = array_map(function ($plan) {
    //                 return [
    //                     'installments' => (int) $plan['installments'],
    //                     'amount' => (float) $plan['amount'],
    //                     'interval_months' => (int) $plan['interval_months'], // Include interval_months
    //                 ];
    //             }, $validated['emi_plans']);
    
    //             // Validate total EMI amount
    //             foreach ($batchData['emi_plans'] as $plan) {
    //                 $total = $plan['installments'] * $plan['amount'];
    //                 if (abs($total - $batchData['price']) > 0.01) {
    //                     return back()->withErrors(['emi_plans' => 'Total EMI amount must equal the batch price (₹' . $batchData['price'] . ').']);
    //                 }
    //             }
    //         } else {
    //             $batchData['emi_plans'] = null;
    //         }
    
    //         Log::info('Batch data before creation:', $batchData);
    
    //         $batch = Batch::create($batchData);
    
    //         Log::info('Batch created:', ['id' => $batch->id, 'emi_available' => $batch->emi_available, 'emi_plans' => $batch->emi_plans]);
    
    //         return redirect()->route('admin.batches.add')->with('success', 'Batch added successfully!');
    //     } catch (\Exception $e) {
    //         Log::error('Failed to create batch:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    //         return back()->withErrors(['error' => 'Failed to create batch: ' . $e->getMessage()]);
    //     }
    // }

    public function store(Request $request)
{
    Log::info('Batch store request:', $request->all());

    try {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'status' => 'required|in:Batch Started,Upcoming,Soon',
            'days' => 'required',
            'duration' => 'required|string',
            'time_slot' => 'required|string',
            'price' => 'required|numeric|min:0',
            'emi_price' => 'nullable|numeric|min:0',
            'discount_info' => 'nullable|string',
            'slots_available' => 'required|numeric|min:1',
            'slots_filled' => 'required|numeric|min:0',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'emi_available' => 'nullable|in:on,1,0,true,false',
            'emi_plans' => 'nullable:emi_available,on|array',
            'emi_plans.*.installments' => 'nullable:emi_available,on|integer|min:2',
            'emi_plans.*.amount' => 'nullable:emi_available,on|numeric|min:0',
            'emi_plans.*.interval_months' => 'nullable:emi_available,on|integer|min:1',
        ]);

        $batchData = $validated;
        $batchData['emi_available'] = in_array($request->emi_available, ['on', '1', 'true'], true);
        $batchData['discount_info'] = $request->discount ?? 0;
        $batchData['discounted_price'] = $batchData['price'] - ($batchData['price'] * ($batchData['discount_info'] / 100));

      // Handle EMI plans
      if ($batchData['emi_available']) {
        $batchData['emi_plans'] = array_map(function ($plan) {
            return [
                'installments' => (int) $plan['installments'],
                'amount' => round((float) $plan['amount'], 2),
                'interval_months' => (int) $plan['interval_months'],
            ];
        }, $validated['emi_plans']);

        // Validate total EMI amount matches discounted price
       // Validate total EMI amount matches discounted price with a tolerance of 0.01
       foreach ($batchData['emi_plans'] as $index => $plan) {
        $total = round($plan['installments'] * $plan['amount'], 2);
        $emiPrice = round($batchData['emi_price'], 2);
        
        if (abs($total - $emiPrice) > 0.5) { // Increased tolerance
            Log::warning('EMI plan validation failed:', [
                'plan_index' => $index,
                'total' => $total,
                'emi_price' => $emiPrice,
                'difference' => abs($total - $emiPrice),
                'installments' => $plan['installments'],
                'amount' => $plan['amount'],
            ]);
            return back()->withErrors([
                'emi_plans' => "Total EMI amount for plan " . ($index + 1) . " (₹{$total}) does not match the EMI price (₹{$emiPrice}).",
            ]);
        }
    }

    } else {
        $batchData['emi_plans'] = null;
    }
    // Remove discounted_price from batchData as it's a generated column
    // unset($batchData['discounted_price']);

        Log::info('Batch data before creation:', $batchData);

        $batch = Batch::create($batchData);

        Log::info('Batch created:', [
            'id' => $batch->id,
            'emi_available' => $batch->emi_available,
            'emi_plans' => $batch->emi_plans,
        ]);

        return redirect()->route('admin.batches.add')->with('success', 'Batch added successfully!');
    } catch (\Exception $e) {
        dd($e);
        Log::error('Failed to create batch:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return back()->withErrors(['error' => 'Failed to create batch: ' . $e->getMessage()]);
    }
}
public function storeInt(Request $request)
{
    Log::info('Batch store request:', $request->all());

    try {
        $validated = $request->validate([
            'batch_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'status' => 'required|in:Batch Started,Upcoming,Soon',
            'days' => 'required',
            'duration' => 'required|string',
            'time_slot' => 'required|string',
            'price' => 'required|numeric|min:0',
            'emi_price' => 'nullable|numeric|min:0',
            'discount_info' => 'nullable|string',
            'slots_available' => 'required|numeric|min:1',
            'slots_filled' => 'required|numeric|min:0',
            'internship_id' => 'required|',
            'teacher_id' => 'required|exists:users,id',
            'emi_available' => 'nullable|in:on,1,0,true,false',
            'emi_plans' => 'nullable:emi_available,on|array',
            'emi_plans.*.installments' => 'nullable:emi_available,on|integer|min:2',
            'emi_plans.*.amount' => 'nullable:emi_available,on|numeric|min:0',
            'emi_plans.*.interval_months' => 'nullable:emi_available,on|integer|min:1',
        ]);

        $batchData = $validated;
        $batchData['emi_available'] = in_array($request->emi_available, ['on', '1', 'true'], true);
        $batchData['discount_info'] = $request->discount ?? 0;
        $batchData['discounted_price'] = $batchData['price'] - ($batchData['price'] * ($batchData['discount_info'] / 100));

      // Handle EMI plans
      if ($batchData['emi_available']) {
        $batchData['emi_plans'] = array_map(function ($plan) {
            return [
                'installments' => (int) $plan['installments'],
                'amount' => round((float) $plan['amount'], 2),
                'interval_months' => (int) $plan['interval_months'],
            ];
        }, $validated['emi_plans']);

        // Validate total EMI amount matches discounted price
       // Validate total EMI amount matches discounted price with a tolerance of 0.01
       foreach ($batchData['emi_plans'] as $index => $plan) {
        $total = round($plan['installments'] * $plan['amount'], 2);
        $emiPrice = round($batchData['emi_price'], 2);
        
        if (abs($total - $emiPrice) > 0.5) { // Increased tolerance
            Log::warning('EMI plan validation failed:', [
                'plan_index' => $index,
                'total' => $total,
                'emi_price' => $emiPrice,
                'difference' => abs($total - $emiPrice),
                'installments' => $plan['installments'],
                'amount' => $plan['amount'],
            ]);
            return back()->withErrors([
                'emi_plans' => "Total EMI amount for plan " . ($index + 1) . " (₹{$total}) does not match the EMI price (₹{$emiPrice}).",
            ]);
        }
    }

    } else {
        $batchData['emi_plans'] = null;
    }
    // Remove discounted_price from batchData as it's a generated column
    // unset($batchData['discounted_price']);

        Log::info('Batch data before creation:', $batchData);

        $batch = InternshipBatch::create($batchData);

        Log::info('Batch created:', [
            'id' => $batch->id,
            'emi_available' => $batch->emi_available,
            'emi_plans' => $batch->emi_plans,
        ]);

        return redirect()->route('admin.batches.add.int')->with('success', ' Internship Batch added successfully!');
    } catch (\Exception $e) {
        dd($e);
        Log::error('Failed to create batch:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return back()->withErrors(['error' => 'Failed to create batch: ' . $e->getMessage()]);
    }
}

    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();
        return redirect()->route('admin.batches.index')->with('success', 'Batch deleted successfully!');
    }

    public function edit($id)
    {
        $batch = Batch::findOrFail($id);
        $teachers = User::where('role', 2)->get();
        $courses = Course::all();
        return response()->json([
            'batch' => $batch,
            'teachers' => $teachers,
            'courses' => $courses,
        ]);
    }
    public function register_teacher(Request $request)
    {
               // Directly create a new user with the provided data
               $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'college_company' => $request->input('college_company'),
                'qualification' => $request->input('qualification'),
                'password' => Hash::make($request->input('password')),
                'profile_image' => null, // No profile image provided
                'role' => 2, // Default role
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Redirect to login page with success message
            return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }
    public function update(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'status' => 'required|in:Batch Started,Upcoming,Soon',
            'course_id' => 'required|exists:courses,id',
            //'teacher_id' => 'required|exists:teachers,id',
            'days' => 'required|in:SAT - SUN,MON - FRI',
            'duration' => 'required|string|max:255',
            'time_slot' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'emi_price' => 'nullable|numeric|min:0',
            'emi_available' => 'nullable|boolean',
            'slots_available' => 'required|integer|min:1',
            'slots_filled' => 'required|integer|min:0|lte:slots_available',
            'discount_info' => 'nullable|string|max:255',
            'emi_plans.*.installments' => 'nullable|integer|min:1',
            'emi_plans.*.amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed: ' . $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate EMI total if EMI is available
        if ($request->emi_available && $request->emi_plans) {
            $emiTotal = 0;
            foreach ($request->emi_plans as $plan) {
                if (isset($plan['installments']) && isset($plan['amount'])) {
                    $emiTotal += $plan['installments'] * $plan['amount'];
                }
            }
            if (abs($emiTotal - $request->price) > 0.01) {
                return response()->json([
                    'error' => 'EMI total does not match batch price.',
                    'errors' => ['emi_plans' => ['Total EMI amount must equal the batch price.']],
                ], 422);
            }
        }

        try {
            // Update the batch
            DB::table('batches')->where('id', $id)->update([
                'start_date' => $request->start_date,
                'status' => $request->status,
                'course_id' => $request->course_id,
                'teacher_id' => $request->teacher_id,
                'days' => $request->days,
                'duration' => $request->duration,
                'time_slot' => $request->time_slot,
                'price' => $request->price,
                'emi_price' => $request->emi_price,
                'emi_available' => $request->emi_available ? 1 : 0,
                'slots_available' => $request->slots_available,
                'slots_filled' => $request->slots_filled,
                'discount_info' => $request->discount_info,
                'emi_plans' => $request->emi_plans ? json_encode($request->emi_plans) : null,
                'updated_at' => now(),
            ]);

            // Fetch course and teacher names for response
            $course = DB::table('courses')->where('id', $request->course_id)->first();
            //$teacher = DB::table('teachers')->where('id', $request->teacher_id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Batch updated successfully.',
                'course_name' => $course->name ?? 'N/A',
               // 'teacher_name' => $teacher->name ?? 'N/A',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update batch: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getBatchesByCourse(Request $request)
    {
        $courseId  = $request->query('id');
        if (!$courseId) {
            return response()->json(['error' => 'Course ID is required'], 400);
        }

        $batches = Batch::where('course_id', $courseId)
            ->with('course', 'teacher')
            ->get() 
            ->map(function ($batch) {
                return [
                    'id' => $batch->id,
                    'date' => $batch->start_date->format('d M'),
                    'price' => $batch->price,
                    'slotsAvailable' => $batch->slots_available,
                    'slotsFilled' => $batch->slots_filled,
                    'mode' => $batch->course->mode ?? 'Online',
                    'status' => $batch->status === 'Batch Started' ? 'started' : ($batch->status === 'Upcoming' ? 'upcoming' : 'soon'),
                    'startDate' => $batch->start_date->toISOString(),
                    'discount_info' => $batch->discount_info,
                    'emi_available' => $batch->emi_available,
                    'emi_plans' => $batch->emi_plans ?? [],
                ];
            });

        return response()->json($batches);
    }
    public function getBatchesByCourseInt(Request $request)
    {
        $courseId  = $request->query('id');
        if (!$courseId) {
            return response()->json(['error' => 'Course ID is required'], 400);
        }

        $batches = InternshipBatch::where('internship_id', $courseId)
            ->with('internship', 'teacher')
            ->get() 
            ->map(function ($batch) {
                return [
                    'id' => $batch->id,
                    'date' => $batch->start_date->format('d M'),
                    'price' => $batch->price,
                    'slotsAvailable' => $batch->slots_available,
                    'slotsFilled' => $batch->slots_filled,
                    'mode' => $batch->course->mode ?? 'Online',
                    'status' => $batch->status === 'Batch Started' ? 'started' : ($batch->status === 'Upcoming' ? 'upcoming' : 'soon'),
                    'startDate' => $batch->start_date->toISOString(),
                    'discount_info' => $batch->discount_info,
                    'emi_available' => $batch->emi_available,
                    'emi_plans' => $batch->emi_plans ?? [],
                ];
            });
           // dd( $batches);

        return response()->json($batches);
    }

    // public function show(Request $request)
    // {
    //     $batchId = $request->query('batch_id');
    //     if (!$batchId) {
    //         return redirect()->back()->withErrors(['error' => 'Batch ID is required']);
    //     }

    //     $batch = Batch::with('course')->findOrFail($batchId);
    //     $batchData = [
    //         'id' => $batch->id,
    //         'date' => $batch->start_date->format('d M'),
    //         'price' => $batch->price,
    //         'slotsAvailable' => $batch->slots_available,
    //         'slotsFilled' => $batch->slots_filled,
    //         'mode' => $batch->course->mode ?? 'Online',
    //         'status' => $batch->status === 'Batch Started' ? 'started' : ($batch->status === 'Upcoming' ? 'upcoming' : 'soon'),
    //         'startDate' => $batch->start_date->toISOString(),
    //         'emi_available' => $batch->emi_available,
    //         'emi_plans' => $batch->emi_plans ?? [],
    //         'course_name' => $batch->course->name,
    //     ];

    //     return view('register', compact('batchData'));
    // }

//     public function show(Request $request)
// {
//     // Get batch ID from session
//     $batchId = session('current_batch_id');
    
//     if (!$batchId) {
//         return redirect()->back()->withErrors(['error' => 'Please select a batch first']);
//     }

//     // Clear the session data
//     session()->forget('current_batch_id');

//     // Fetch all data from database
//     $batch = Batch::with('course')->findOrFail($batchId);
    
//     $batchData = [
//         'id' => $batch->id,
//         'date' => $batch->start_date->format('d M'),
//         'price' => $batch->price,
//         'slotsAvailable' => $batch->slots_available,
//         'slotsFilled' => $batch->slots_filled,
//         'mode' => $batch->course->mode ?? 'Online',
//         'status' => $batch->status === 'Batch Started' ? 'started' : ($batch->status === 'Upcoming' ? 'upcoming' : 'soon'),
//         'startDate' => $batch->start_date->toISOString(),
//         'emi_available' => $batch->emi_available,
//         'emi_plans' => $batch->emi_plans ?? [],
//         'course_name' => $batch->course->name,
//     ];

//     return view('register', compact('batchData'));
// }
// public function show(Request $request)
// {
//     $batchId = session('current_batch_id');
    
//     if (!$batchId) {
//         return redirect()->back()->withErrors(['error' => 'Please select a batch first']);
//     }

//     session()->forget('current_batch_id');

//     $batch = Batch::with('course')->findOrFail($batchId);
    
//     // Parse discount_info to extract the percentage
//     $discountPercentage = 0;
//     if ($batch->discount_info && preg_match('/(\d+)%/', $batch->discount_info, $matches)) {
//         $discountPercentage = (float) $matches[1];
//     }

//     // Calculate discounted price
//     $originalPrice = $batch->price;
//     $discountAmount = ($originalPrice * $discountPercentage) / 100;
//     $discountedPrice = $originalPrice - $discountAmount;

//     // Adjust EMI plans based on the discounted price
//     $emiPlans = $batch->emi_plans ?? [];
//     if (!empty($emiPlans)) {
//         $emiPlans = array_map(function ($plan) use ($discountedPrice) {
//             $totalInstallments = $plan['installments'];
//             $newEmiAmount = $discountedPrice / $totalInstallments;
//             // Round to 2 decimal places for precise calculations
//             $roundedAmount = round($newEmiAmount, 2);
//             return [
//                 'installments' => $plan['installments'],
//                 'amount' => $roundedAmount,
//                 'interval_months' => $plan['interval_months'] ?? 1,
//             ];
//         }, $emiPlans);
//     }

//     $batchData = [
//         'id' => $batch->id,
//         'date' => $batch->start_date->format('d M'),
//         'price' => $discountedPrice,
//         'original_price' => $originalPrice,
//         'discount_percentage' => $discountPercentage,
//         'slotsAvailable' => $batch->slots_available,
//         'slotsFilled' => $batch->slots_filled,
//         'mode' => $batch->course->mode ?? 'Online',
//         'status' => $batch->status === 'Batch Started' ? 'started' : ($batch->status === 'Upcoming' ? 'upcoming' : 'soon'),
//         'startDate' => $batch->start_date->toISOString(),
//         'emi_available' => $batch->emi_available,
//         'emi_plans' => $emiPlans,
//         'course_name' => $batch->course->name,
//     ];

//     return view('register', compact('batchData'));
// }

public function show(Request $request)
{
    $batch = Batch::with('course')->findOrFail(session('current_batch_id'));
    
    // Parse discount info
    $discountPercentage = 0;
    if (is_numeric($batch->discount_info)) {
        $discountPercentage = (float) $batch->discount_info;
    }

    // Calculate prices
    $originalPrice = $batch->price;
    $discountedPrice = $originalPrice * (1 - $discountPercentage/100);
    $emiPrice= $batch->emi_price;
    // dd($discountedPrice);

    // Adjust EMI plans based on discounted price
    $emiPlans = $batch->emi_plans ?? [];
    if (!empty($emiPlans)) {
        $emiPlans = array_map(function ($plan) use ($emiPrice) {
            $totalInstallments = $plan['installments'];
            $newEmiAmount = $emiPrice / $totalInstallments;
            return [
                'installments' => $plan['installments'],
                'amount' => round($newEmiAmount, 2),
                'interval_months' => $plan['interval_months'] ?? 1,
            ];
        }, $emiPlans);
    }

    $batchData = [
        'id' => $batch->id,
        'date' => $batch->start_date->format('d M'),
        'slotsAvailable' => $batch->slots_available,
        'slotsFilled' => $batch->slots_filled,
        'mode' => $batch->course->mode ?? 'Online',
        'status' => $batch->status === 'Batch Started' ? 'started' : ($batch->status === 'Upcoming' ? 'upcoming' : 'soon'),
        'startDate' => $batch->start_date->toISOString(),
        'emi_available' => $batch->emi_available,
        'course_name' => $batch->course->name,
        'price' => $discountedPrice,
        'emi_price' => $emiPrice,
        'original_price' => $originalPrice,
        'discount_percentage' => $discountPercentage,
        'emi_plans' => $emiPlans,
    ];

    return view('register', compact('batchData'));
}


public function showInt(Request $request)
{
    $batch = InternshipBatch::with('internship')->findOrFail(session('current_batch_int_id'));
   // dd($batch);
    // Parse discount info
    $discountPercentage = 0;
    if (is_numeric($batch->discount_info)) {
        $discountPercentage = (float) $batch->discount_info;
    }

    // Calculate prices
    $originalPrice = $batch->price;
    $discountedPrice = $originalPrice * (1 - $discountPercentage/100);
    $emiPrice= $batch->emi_price;
    // dd($discountedPrice);

    // Adjust EMI plans based on discounted price
    $emiPlans = $batch->emi_plans ?? [];
    if (!empty($emiPlans)) {
        $emiPlans = array_map(function ($plan) use ($emiPrice) {
            $totalInstallments = $plan['installments'];
            $newEmiAmount = $emiPrice / $totalInstallments;
            return [
                'installments' => $plan['installments'],
                'amount' => round($newEmiAmount, 2),
                'interval_months' => $plan['interval_months'] ?? 1,
            ];
        }, $emiPlans);
    }

    $batchData = [
        'id' => $batch->id,
        'date' => $batch->start_date->format('d M'),
        'slotsAvailable' => $batch->slots_available,
        'slotsFilled' => $batch->slots_filled,
        'mode' => $batch->internship->mode ?? 'Online',
        'status' => $batch->status === 'Batch Started' ? 'started' : ($batch->status === 'Upcoming' ? 'upcoming' : 'soon'),
        'startDate' => $batch->start_date->toISOString(),
        'emi_available' => $batch->emi_available,
        'course_name' => $batch->internship->name,
        'price' => $discountedPrice,
        'emi_price' => $emiPrice,
        'original_price' => $originalPrice,
        'discount_percentage' => $discountPercentage,
        'emi_plans' => $emiPlans,
    ];

    return view('website.internship-register', compact('batchData'));
}

// public function submitr(Request $request)
// {
//     $batch = Batch::findOrFail($request->batch_id);
    
//     // Apply discount
//     $discountPercentage = 0;
//     if ($batch->discount_info && preg_match('/(\d+)%/', $batch->discount_info, $matches)) {
//         $discountPercentage = (float) $matches[1];
//     }
//     $discountedPrice = $batch->price * (1 - $discountPercentage/100);

//     $paymentAmount = $request->price;
//     $emiPlan = null;

//     if ($request->payment_method === 'emi') {
//         $selectedPlanIndex = $request->emi_plan;
//         $originalPlan = $batch->emi_plans[$selectedPlanIndex];
        
//         // Calculate based on discounted price
//         $totalInstallments = $originalPlan['installments'];
//         $newEmiAmount = $discountedPrice / $totalInstallments;
//         $paymentAmount = round($newEmiAmount, 2);
        
//         $emiPlan = [
//             'installments' => $totalInstallments,
//             'amount' => $paymentAmount,
//             'interval_months' => $originalPlan['interval_months'] ?? 1
//         ];
//     }

//     // Verify payment amount matches expected
//     $expectedAmount = (int) round($paymentAmount * 100);
//     $receivedAmount = $razorpayPayment->amount;
    
//     if (abs($expectedAmount - $receivedAmount) > 1) {
//         throw new \Exception(sprintf(
//             'Amount mismatch: expected %d (%s), got %d (%s)',
//             $expectedAmount,
//             number_format($expectedAmount/100, 2),
//             $receivedAmount,
//             number_format($receivedAmount/100, 2)
//         ));
//     }

//     // ... rest of the payment processing ...
// }

 

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:registrations,email',
            'phone' => 'required|string|max:15',
            'batch_date' => 'required',
            'batch_status' => 'required',
            'mode' => 'required',
            'price' => 'required|numeric',
            'slots_available' => 'required|numeric',
            'slots_filled' => 'required|numeric',
        ]);

        Registration::create($validated);

        return redirect()->route('register')->with('success', 'Registration successful!');
    }

//     public function submitr(Request $request)
// {
//     Log::info('Incoming registration request:', $request->all());

//     try {
//         // Validate request data
//         $validated = $request->validate([
//             'batch_id' => 'required|exists:batches,id',
//             'batch_date' => 'required|string',
//             'batch_status' => 'required|string',
//             'mode' => 'required|string',
//             'price' => 'required|numeric|min:0',
//             'slots_available' => 'required|integer|min:0',
//             'slots_filled' => 'required|integer|min:0',
//             'name' => 'required|string|max:255',
//             'email' => 'required|email|unique:users,email|max:255',
//             'phone' => 'required|string|max:15',
//             'payment_id' => 'required|string|max:255',
//             'payment_method' => 'required|in:full,emi',
//             'emi_plan' => 'required_if:payment_method,emi|integer|min:0',
//         ]);

//         Log::info('Validated data:', $validated);

//         // Fetch batch and log EMI plans
//         $batch = Batch::findOrFail($validated['batch_id']);
//         $batch->emi_plans = $batch->emi_plans ?? [];
//         Log::info('Batch EMI plans:', ['batch_id' => $batch->id, 'emi_plans' => $batch->emi_plans]);

//         // Calculate payment amount and EMI plan
//         $paymentAmount = $validated['price'];
//         $emiPlan = null;
//         if ($validated['payment_method'] === 'emi') {
//             if (empty($batch->emi_plans)) {
//                 throw new \Exception('No EMI plans available for this batch');
//             }
//             if (!array_key_exists($validated['emi_plan'], $batch->emi_plans)) {
//                 throw new \Exception('Invalid EMI plan selected: ' . $validated['emi_plan']);
//             }
//             $emiPlan = $batch->emi_plans[$validated['emi_plan']];
//             if (!isset($emiPlan['installments']) || !isset($emiPlan['amount'])) {
//                 throw new \Exception('Invalid EMI plan configuration');
//             }
//             $paymentAmount = $emiPlan['amount'];
//             Log::info('EMI plan selected:', ['emi_plan' => $emiPlan, 'payment_amount' => $paymentAmount]);
//         }

//         // Verify Razorpay payment
//         $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
//         $razorpayPayment = $api->payment->fetch($validated['payment_id']);
//         Log::info('Razorpay payment details:', (array) $razorpayPayment);

//         $expectedAmount = (int) ($paymentAmount * 100); // Convert to paise
//         if ($razorpayPayment->amount !== $expectedAmount) {
//             throw new \Exception('Amount mismatch: expected ' . $expectedAmount . ', got ' . $razorpayPayment->amount);
//         }

//         DB::beginTransaction();

//         try {
//             // Create user
//             $user = User::create([
//                 'name' => $validated['name'],
//                 'email' => $validated['email'],
//                 'password' => Hash::make('123456'),
//                 'role' => 3,
//             ]);
//             Log::info('User created:', ['user_id' => $user->id]);

//             // Create registration
//             $registration = Registration::create([
//                 'user_id' => $user->id,
//                 'batch_date' => $validated['batch_date'],
//                 'batch_status' => $validated['batch_status'],
//                 'mode' => $validated['mode'],
//                 'price' => $validated['price'],
//                 'slots_available' => $validated['slots_available'],
//                 'slots_filled' => $validated['slots_filled'],
//             ]);
//             Log::info('Registration created:', ['registration_id' => $registration->id]);

//             // Create student
//             $student = Student::create([
//                 'user_id' => $user->id,
//                 'phone' => $validated['phone'],
//             ]);
//             Log::info('Student created:', ['student_id' => $student->id]);

//             // Create enrollment
//             $enrollment = Enrollment::create([
//                 'user_id' => $user->id,
//                 'email' => $user->email,
//                 'batch_id' => $validated['batch_id'],
//                 'status' => 'active',
//             ]);
//             Log::info('Enrollment created:', ['enrollment_id' => $enrollment->id]);

//             // Use payment creation date as start date for EMI schedule
//             $paymentDate = now()->toDateString();

//             // Create payment
//             $paymentData = [
//                 'enrollment_id' => $enrollment->id,
//                 'user_id' => $user->id,
//                 'batch_id' => $validated['batch_id'],
//                 'payment_id' => $validated['payment_id'],
//                 'amount' => $paymentAmount,
//                 'status' => 'completed',
//                 'payment_method' => $validated['payment_method'],
//                 'emi_installments' => $emiPlan ? $emiPlan['installments'] : null,
//                 'emi_amount' => $emiPlan ? $emiPlan['amount'] : null,
//                 'emi_schedule' => $emiPlan ? json_encode($this->generateEmiSchedule(
//                     $emiPlan['installments'],
//                     $emiPlan['amount'],
//                     $emiPlan['interval_months'] ?? 1,
//                     $paymentDate
//                 )) : null,
//             ];
//             Log::info('Payment data to be saved:', $paymentData);

//             $payment = Payment::create($paymentData);
//             Log::info('Payment created:', ['payment_id' => $payment->id, 'emi_details' => [
//                 'payment_method' => $payment->payment_method,
//                 'emi_installments' => $payment->emi_installments,
//                 'emi_amount' => $payment->emi_amount,
//                 'emi_schedule' => $payment->emi_schedule,
//             ]]);

//             // Update batch slots
//             $batch->slots_filled += 1;
//             $batch->slots_available -= 1;
//             $batch->save();
//             Log::info('Batch slots updated:', ['batch_id' => $batch->id]);

//             DB::commit();

//             return redirect()->route('login')->with('message', 'Please check your email for credentials to log in.');

//         } catch (\Exception $e) {
//             DB::rollBack();
//             Log::error('Database operation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
//             throw new \Exception('Registration process failed: ' . $e->getMessage());
//         }
//     } catch (\Illuminate\Validation\ValidationException $e) {
//         Log::error('Validation failed:', $e->errors());
//         return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
//     } catch (\Exception $e) {
//         Log::error('Registration process failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
//         return response()->json(['error' => $e->getMessage()], 400);
//     }
// }
//     private function generateEmiSchedule($installments, $amount, $intervalMonths, $startDate = null)
//     {
//         $schedule = [];
//         $startDate = $startDate ? Carbon::parse($startDate) : now();
    
//         // Start from the second installment (first is paid)
//         for ($i = 1; $i < $installments; $i++) {
//             $schedule[] = [
//                 'installment_number' => $i + 1, // Start numbering from 2
//                 'amount' => $amount,
//                 'due_date' => $startDate->copy()->addMonths($i * $intervalMonths)->toDateString(),
//             ];
//         }
    
//         return $schedule;
//     }

public function submitrInt(Request $request)
{
  //   dd($request->all());
    Log::info('Incoming registration request:', $request->all());

    try {
        // Validate request data
        $validated = $request->validate([
            'batch_id' => 'required|exists:internship_batches,id',
            'batch_date' => 'required|string',
            'batch_status' => 'required|string',
            'mode' => 'required|string',
            'price' => 'required|numeric|min:0',
            'slots_available' => 'required|integer|min:0',
            'slots_filled' => 'required|integer|min:0',
            'name' => 'required|string|max:255',
            'email' => 'required|email:users,email|max:255',
            'phone' => 'required|string|max:15',
            'payment_id' => 'required|string|max:255',
            'payment_method' => 'required|in:full,emi',
            'emi_plan' => 'required_if:payment_method,emi|integer|min:0',
        ]);

        Log::info('Validated data:', $validated);

        // Fetch batch and log EMI plans
        $batch = InternshipBatch::findOrFail($validated['batch_id']);
        $batch->emi_plans = $batch->emi_plans ?? [];
        Log::info('Batch EMI plans:', ['batch_id' => $batch->id, 'emi_plans' => $batch->emi_plans]);

        // Calculate payment amount and EMI plan
        $paymentAmount = $validated['price'];
        $emiPlan = null;
        if ($validated['payment_method'] === 'emi') {
            if (empty($batch->emi_plans)) {
                throw new \Exception('No EMI plans available for this batch');
            }
            if (!array_key_exists($validated['emi_plan'], $batch->emi_plans)) {
                throw new \Exception('Invalid EMI plan selected: ' . $validated['emi_plan']);
            }
            $emiPlan = $batch->emi_plans[$validated['emi_plan']];
            if (!isset($emiPlan['installments']) || !isset($emiPlan['amount'])) {
                throw new \Exception('Invalid EMI plan configuration');
            }
            $paymentAmount = $emiPlan['amount'];
            Log::info('EMI plan selected:', ['emi_plan' => $emiPlan, 'payment_amount' => $paymentAmount]);
        }

        // Verify Razorpay payment
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $razorpayPayment = $api->payment->fetch($validated['payment_id']);
        Log::info('Razorpay payment details:', (array) $razorpayPayment);

        $expectedAmount = (int) ($paymentAmount * 100); // Convert to paise
        if ($razorpayPayment->amount !== $expectedAmount) {
            throw new \Exception('Amount mismatch: expected ' . $expectedAmount . ', got ' . $razorpayPayment->amount);
        }

        DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('123456'),
                'role' => 3,
                'internship' => '1',
            ]);
            Log::info('User created:', ['user_id' => $user->id]);

            // // Create registration
            // $registration = Registration::create([
            //     'user_id' => $user->id,
            //     'batch_date' => $validated['batch_date'],
            //     'batch_status' => $validated['batch_status'],
            //     'mode' => $validated['mode'],
            //     'price' => $validated['price'],
            //     'slots_available' => $validated['slots_available'],
            //     'slots_filled' => $validated['slots_filled'],
            // ]);
           // Log::info('Registration created:', ['registration_id' => $registration->id]);

            // Create student
            $student = Student::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
            ]);
            Log::info('Student created:', ['student_id' => $student->id]);

            // Create enrollment
            $enrollment = InternshipEnrollment::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'batch_id' => $validated['batch_id'],
                'status' => 'active',
                 'name' => $validated['name'],
                 'phone' => $validated['phone'],
                 'payment_id' => $validated['payment_id'],
                 'amount' => $paymentAmount,

            ]);
            Log::info('Enrollment created:', ['enrollment_id' => $enrollment->id]);

            // Use payment creation date as start date for EMI schedule
            $paymentDate = now()->toDateString();

            // Create payment
            $paymentData = [
                'enrollment_id' => $enrollment->id,
                'user_id' => $user->id,
                'batch_id' => $validated['batch_id'],
                'payment_id' => $validated['payment_id'],
                'amount' => $paymentAmount,
                'status' => 'completed',
                'payment_method' => $validated['payment_method'],
                'emi_installments' => $emiPlan ? $emiPlan['installments'] : null,
                'emi_amount' => $emiPlan ? $emiPlan['amount'] : null,
                'emi_schedule' => $emiPlan ? json_encode($this->generateEmiSchedule(
                    $emiPlan['installments'],
                    $emiPlan['amount'],
                    $emiPlan['interval_months'] ?? 1,
                    $paymentDate
                )) : null,
            ];
            Log::info('Payment data to be saved:', $paymentData);

            $payment = Payment::create($paymentData);
            Log::info('Payment created:', ['payment_id' => $payment->id, 'emi_details' => [
                'payment_method' => $payment->payment_method,
                'emi_installments' => $payment->emi_installments,
                'emi_amount' => $payment->emi_amount,
                'emi_schedule' => $payment->emi_schedule,
            ]]);

            // Update batch slots
            $batch->slots_filled += 1;
            $batch->slots_available -= 1;
            $batch->save();
            Log::info('Batch slots updated:', ['batch_id' => $batch->id]);

            DB::commit();

            return redirect()->route('login')->with('message', 'Please check your email for credentials to log in.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Database operation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw new \Exception('Registration process failed: ' . $e->getMessage());
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed:', $e->errors());
        return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
    } catch (\Exception $e) {
        Log::error('Registration process failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

public function submitr(Request $request)
{
    // dd($request->all());
    Log::info('Incoming registration request:', $request->all());

    try {
        // Validate request data
        $validated = $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'batch_date' => 'required|string',
            'batch_status' => 'required|string',
            'mode' => 'required|string',
            'price' => 'required|numeric|min:0',
            'slots_available' => 'required|integer|min:0',
            'slots_filled' => 'required|integer|min:0',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|string|max:15',
            'payment_id' => 'required|string|max:255',
            'payment_method' => 'required|in:full,emi',
            'emi_plan' => 'required_if:payment_method,emi|integer|min:0',
        ]);

        Log::info('Validated data:', $validated);

        // Fetch batch and log EMI plans
        $batch = Batch::findOrFail($validated['batch_id']);
        $batch->emi_plans = $batch->emi_plans ?? [];
        Log::info('Batch EMI plans:', ['batch_id' => $batch->id, 'emi_plans' => $batch->emi_plans]);

        // Calculate payment amount and EMI plan
        $paymentAmount = $validated['price'];
        $emiPlan = null;
        if ($validated['payment_method'] === 'emi') {
            if (empty($batch->emi_plans)) {
                throw new \Exception('No EMI plans available for this batch');
            }
            if (!array_key_exists($validated['emi_plan'], $batch->emi_plans)) {
                throw new \Exception('Invalid EMI plan selected: ' . $validated['emi_plan']);
            }
            $emiPlan = $batch->emi_plans[$validated['emi_plan']];
            if (!isset($emiPlan['installments']) || !isset($emiPlan['amount'])) {
                throw new \Exception('Invalid EMI plan configuration');
            }
            $paymentAmount = $emiPlan['amount'];
            Log::info('EMI plan selected:', ['emi_plan' => $emiPlan, 'payment_amount' => $paymentAmount]);
        }

        // Verify Razorpay payment
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $razorpayPayment = $api->payment->fetch($validated['payment_id']);
        Log::info('Razorpay payment details:', (array) $razorpayPayment);

        $expectedAmount = (int) ($paymentAmount * 100); // Convert to paise
        if ($razorpayPayment->amount !== $expectedAmount) {
            throw new \Exception('Amount mismatch: expected ' . $expectedAmount . ', got ' . $razorpayPayment->amount);
        }

        DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make('123456'),
                'role' => 3,
            ]);
            Log::info('User created:', ['user_id' => $user->id]);

            // Create registration
            $registration = Registration::create([
                'user_id' => $user->id,
                'batch_date' => $validated['batch_date'],
                'batch_status' => $validated['batch_status'],
                'mode' => $validated['mode'],
                'price' => $validated['price'],
                'slots_available' => $validated['slots_available'],
                'slots_filled' => $validated['slots_filled'],
            ]);
            Log::info('Registration created:', ['registration_id' => $registration->id]);

            // Create student
            $student = Student::create([
                'user_id' => $user->id,
                'phone' => $validated['phone'],
            ]);
            Log::info('Student created:', ['student_id' => $student->id]);

            // Create enrollment
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'email' => $user->email,
                'batch_id' => $validated['batch_id'],
                'status' => 'active',
            ]);
            Log::info('Enrollment created:', ['enrollment_id' => $enrollment->id]);

            // Use payment creation date as start date for EMI schedule
            $paymentDate = now()->toDateString();

            // Create payment
            $paymentData = [
                'enrollment_id' => $enrollment->id,
                'user_id' => $user->id,
                'batch_id' => $validated['batch_id'],
                'payment_id' => $validated['payment_id'],
                'amount' => $paymentAmount,
                'status' => 'completed',
                'payment_method' => $validated['payment_method'],
                'emi_installments' => $emiPlan ? $emiPlan['installments'] : null,
                'emi_amount' => $emiPlan ? $emiPlan['amount'] : null,
                'emi_schedule' => $emiPlan ? json_encode($this->generateEmiSchedule(
                    $emiPlan['installments'],
                    $emiPlan['amount'],
                    $emiPlan['interval_months'] ?? 1,
                    $paymentDate
                )) : null,
            ];
            Log::info('Payment data to be saved:', $paymentData);

            $payment = Payment::create($paymentData);
            Log::info('Payment created:', ['payment_id' => $payment->id, 'emi_details' => [
                'payment_method' => $payment->payment_method,
                'emi_installments' => $payment->emi_installments,
                'emi_amount' => $payment->emi_amount,
                'emi_schedule' => $payment->emi_schedule,
            ]]);

            // Update batch slots
            $batch->slots_filled += 1;
            $batch->slots_available -= 1;
            $batch->save();
            Log::info('Batch slots updated:', ['batch_id' => $batch->id]);

            DB::commit();

            return redirect()->route('login')->with('message', 'Please check your email for credentials to log in.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Database operation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw new \Exception('Registration process failed: ' . $e->getMessage());
        }
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation failed:', $e->errors());
        return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
    } catch (\Exception $e) {
        Log::error('Registration process failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['error' => $e->getMessage()], 400);
    }
}

private function generateEmiSchedule($installments, $amount, $intervalMonths, $startDate = null)
{
    $schedule = [];
    $startDate = $startDate ? Carbon::parse($startDate) : now();

    // Add first EMI (paid)
    $schedule[] = [
        'installment_number' => 1,
        'amount' => $amount,
        'paid_date' => $startDate->toDateString(),
        'status' => 'paid',
    ];

    // Add future EMIs
    for ($i = 1; $i < $installments; $i++) {
        $schedule[] = [
            'installment_number' => $i + 1,
            'amount' => $amount,
            'due_date' => $startDate->copy()->addMonths($i * $intervalMonths)->toDateString(),
            'status' => 'pending',
        ];
    }

    return $schedule;
}

// BatchController.php
public function storeBatchData(Request $request)
{
    $request->validate([
        'batch_id' => 'required|exists:batches,id'
    ]);

    // Store only the batch ID in session
    session(['current_batch_id' => $request->batch_id]);

    return response()->json(['success' => true]);
}

public function storeBatchDataInt(Request $request)
{
    $request->validate([
        'batch_id' => 'required|exists:internship_batches,id'
    ]);

    // Store only the batch ID in session
    session(['current_batch_int_id' => $request->batch_id]);

    return response()->json(['success' => true]);
}

public function profile(Request $request)
{
    $user = Auth::user();
    return view('admin.profile', compact('user'));
}

// Edit Profile method
public function editProfile()
{
    $user = Auth::user();
    return view('admin.edit-profile', compact('user'));
}

// Update Profile method
public function updateProfile(Request $request)
{
    $user = Auth::user();

    // Validate the incoming request data
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'required|string|regex:/^[0-9]{10}$/',
        'college_company' => 'required|string|max:255',
        'qualification' => 'required|string|max:255',
    ]);

    // Update the user data
    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'college_company' => $validated['college_company'],
        'qualification' => $validated['qualification'],
        'updated_at' => now(),
    ]);

    // Redirect back to the profile page with a success message
    return redirect()->route('profile')->with('success', 'Profile updated successfully!');
}
}



