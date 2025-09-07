<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Enrollment;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        dd($request->all());
        Log::info('Incoming request data:', $request->all());

        // Validate request data
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:15',
                'batch_id' => 'required|integer|exists:batches,id',
                'user_id' => 'required|integer|exists:users,id',
                'payment_id' => 'required|string|max:255',
                'amount' => 'required|numeric|min:1',
            ]);
            Log::info('Validation passed');
        } catch (\Exception $e) {
            Log::error('Validation failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Validation failed: ' . $e->getMessage()], 422);
        }

        // Initialize Razorpay API
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        // Verify payment
        try {
            $razorpayPayment = $api->payment->fetch($request->payment_id);
            Log::info('Payment details:', (array) $razorpayPayment);

            if ($razorpayPayment->status !== 'captured') {
                return response()->json(['error' => 'Payment not captured'], 400);
            }

            if ($razorpayPayment->amount !== (int) ($request->amount * 100)) {
                return response()->json(['error' => 'Amount mismatch'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment verification failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()], 400);
        }

        // Create or update enrollment
        try {
            $enrollment = Enrollment::updateOrCreate(
                ['email' => $request->email, 'batch_id' => $request->batch_id],
                [
                    'user_id' => $request->user_id,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            Log::info('Enrollment saved:', ['enrollment_id' => $enrollment->id]);
        } catch (\Exception $e) {
            Log::error('Enrollment save failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Enrollment save failed: ' . $e->getMessage()], 500);
        }

        // Save payment details
        try {
            $payment = Payment::create([
                'enrollment_id' => $enrollment->id,
                'user_id' => $request->user_id,
                'batch_id' => $request->batch_id,
                'payment_id' => $request->payment_id,
                'amount' => $request->amount,
                'status' => 'completed',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            Log::info('Payment saved:', ['payment_id' => $payment->id]);
        } catch (\Exception $e) {
            Log::error('Payment save failed:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment save failed: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Registration and payment successful',
            'enrollment_id' => $enrollment->id,
        ], 200);
    }
}