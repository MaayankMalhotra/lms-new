<?php

   namespace App\Http\Controllers;

   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\DB;
   use Illuminate\Support\Facades\Mail;
      use App\Models\InternshipEnrollment;
   use App\Mail\OfferLetter;

   class CourseToInternshipController extends Controller
   {
       public function index()
       {
           $enrollments = DB::table('enrollments')
               ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
               ->join('users', 'enrollments.user_id', '=', 'users.id')
               ->where('batches.start_date', '<', now())
               ->select(
                   'users.id as user_id',
                   'users.name',
                   'users.email',
                   'users.phone',
                   'users.internship'
               )
               ->distinct('enrollments.user_id')
               ->get();

           return view('enrollments.report', compact('enrollments'));
       }

      public function sendOfferLetter(Request $request)
{
    try {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        $user = DB::table('users')
            ->where('id', $request->user_id)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'error_code' => 'USER_NOT_FOUND'
            ], 404);
        }

        if ($user->internship) {
            return response()->json([
                'success' => false,
                'message' => 'Internship offer already sent to this user.',
                'error_code' => 'OFFER_ALREADY_SENT'
            ], 400);
        }

        // Check if enrollment already exists
        $existingEnrollment = InternshipEnrollment::where('user_id', $request->user_id)
            ->where('email', $request->email)
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Enrollment already exists for this user.',
                'error_code' => 'ENROLLMENT_EXISTS'
            ], 400);
        }

        // Create InternshipEnrollment record
        $enrollment = InternshipEnrollment::create([
            'user_id' => $request->user_id,
            'email' => $request->email,
            'name' => $request->name,
            'payment_id' => null,
            'amount' => 0,
            'status' => 'pending',
            'free_internship_after_course' => 1, 
        ]);

        // Update user internship status
        DB::table('users')
            ->where('id', $request->user_id)
            ->update(['internship' => 1]);

        // Uncomment when email functionality is ready
        // Mail::to($request->email)->send(new OfferLetter($request->name));

        return response()->json([
            'success' => true,
            'message' => 'Offer letter sent successfully.',
            'enrollment_id' => $enrollment->id
        ], 200);

    } catch (Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'error_code' => 'VALIDATION_ERROR',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Offer letter sending failed: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to send offer letter: ' . $e->getMessage(),
            'error_code' => 'SERVER_ERROR'
        ], 500);
    }
}

       public function sendTestEmail()
       {
           try {
               Mail::to('maayankmalhotra095@gmail.com')->send(new OfferLetter('Test User'));
               return response()->json([
                   'success' => true,
                   'message' => 'Test email sent successfully to maayankmalhotra095@gmail.com.',
               ]);
           } catch (\Exception $e) {
               return response()->json([
                   'success' => false,
                   'message' => 'Failed to send test email: ' . $e->getMessage(),
               ], 500);
           }
       }






       public function sendOfferLettertest(Request $request)
{
    try {
        // Static data for testing
        $data = [
            'user_id' => 1, // Assume a valid user ID for testing
            'email' => 'test@example.com',
            'name' => 'Test User',
        ];

        // Simulate validation (for testing, we'll check static data)
        if (!is_numeric($data['user_id']) || $data['user_id'] <= 0) {
            throw new \Exception('Invalid user ID', 400);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format', 400);
        }

        if (empty($data['name']) || strlen($data['name']) > 255) {
            throw new \Exception('Invalid name', 400);
        }

        // Simulate user lookup (static data for testing)
        $user = (object) [
            'id' => $data['user_id'],
            'internship' => false, // Assume no internship for testing
        ];

        // Check if user exists
        if (!$user) {
            \Log::error('User not found for ID: ' . $data['user_id']);
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
                'error_code' => 'USER_NOT_FOUND',
            ], 404);
        }

        // Check internship status
        if ($user->internship) {
            \Log::info('Offer already sent for user ID: ' . $data['user_id']);
            return response()->json([
                'success' => false,
                'message' => 'Internship offer already sent to this user.',
                'error_code' => 'OFFER_ALREADY_SENT',
            ], 400);
        }

        // Check for existing enrollment
        $existingEnrollment = InternshipEnrollment::where('user_id', $data['user_id'])
            ->where('email', $data['email'])
            ->first();

        if ($existingEnrollment) {
            \Log::info('Enrollment already exists for user ID: ' . $data['user_id']);
            return response()->json([
                'success' => false,
                'message' => 'Enrollment already exists for this user.',
                'error_code' => 'ENROLLMENT_EXISTS',
            ], 400);
        }

        // Create InternshipEnrollment record
        $enrollment = InternshipEnrollment::create([
            'user_id' => $data['user_id'],
            'email' => $data['email'],
            'name' => $data['name'],
            'payment_id' => null,
            'amount' => 0,
            'status' => 'pending',
            //'payment_status' => 'pending',
        ]);

        // Simulate updating user internship status
        // DB::table('users')->where('id', $data['user_id'])->update(['internship' => 1]);
        \Log::info('Simulated user update for ID: ' . $data['user_id']);

        // Simulate email sending (commented out for testing)
        // Mail::to($data['email'])->send(new OfferLetter($data['name']));
        \Log::info('Simulated email sent to: ' . $data['email']);

        return response()->json([
            'success' => true,
            'message' => 'Offer letter sent successfully (static test).',
            'enrollment_id' => $enrollment->id ?? 'test-' . rand(1000, 9999), // Fallback ID for testing
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Offer letter sending failed: ' . $e->getMessage(), [
            'code' => $e->getCode(),
            'trace' => $e->getTraceAsString(),
        ]);

        $statusCode = $e->getCode() ?: 500;
        if (!in_array($statusCode, [400, 404, 422])) {
            $statusCode = 500;
        }

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'error_code' => 'GENERAL_ERROR',
            'debug' => [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ],
        ], $statusCode);
    }
}
   }