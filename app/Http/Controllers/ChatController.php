<?php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index()
    {
        $currentUser = auth()->user();
        $teachers = collect();
        $students = collect();
        $selectedReceiverId = null;
        $errorMessage = null;

        if ($currentUser->role == '3') { // Student
            // Student hai, toh uska assigned teacher fetch karo
            $teacher = DB::table('enrollments')
                ->join('batches', 'enrollments.batch_id', '=', 'batches.id')
                ->join('users', 'batches.teacher_id', '=', 'users.id')
                ->where('enrollments.user_id', $currentUser->id)
                ->where('enrollments.status', 'active')
                ->where('users.role', '2')
                ->select('users.id', 'users.name')
                ->first();

            \Log::info('Student ID: ' . $currentUser->id . ', Teacher: ' . json_encode($teacher));

            if ($teacher) {
                $teachers = collect([$teacher]);
                $selectedReceiverId = $teacher->id;
            } else {
                // Fallback: Agar assigned teacher nahi hai, toh saare teachers fetch karo
                $teachers = User::where('role', '2')->get();
                if ($teachers->isNotEmpty()) {
                    $selectedReceiverId = $teachers->first()->id;
                } else {
                    $errorMessage = "No teachers available to chat with.";
                }
            }
        } elseif ($currentUser->role == '2') { // Teacher
            // Teacher hai, toh un students ko fetch karo jinhone teacher ko message bheja hai
            $students = User::where('role', '3')
                ->whereIn('id', function ($query) use ($currentUser) {
                    $query->select('sender_id')
                        ->from('messages')
                        ->where('receiver_id', $currentUser->id)

                        ->orderBy('id');
                })
                ->select('id', 'name')
                ->get();
               // dd($students);

            \Log::info('Teacher ID: ' . $currentUser->id . ', Students: ' . json_encode($students));

            if ($students->isNotEmpty()) {
                $selectedReceiverId = $students->first()->id;
            } else {
                $errorMessage = "No students have messaged you yet.";
            }
        }

        return view('chat.index', compact('teachers', 'students', 'selectedReceiverId', 'errorMessage'));
    }

    public function fetchMessages($receiverId)
    {
        $messages = Message::where(function ($query) use ($receiverId) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', auth()->id());
        })
        ->orderBy('id')
        ->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $receiverId = $request->query('receiver_id');
        $messageContent = $request->query('message');

        if (!$receiverId || !$messageContent) {
            return response()->json(['status' => 'Error', 'message' => 'Receiver ID or message cannot be empty'], 400);
        }

        // Additional validation for trainer: Ensure receiver_id is a student who messaged the trainer
        if (auth()->user()->role == '2') {
            $hasMessaged = Message::where('sender_id', $receiverId)
                ->where('receiver_id', auth()->id())
                ->exists();

            if (!$hasMessaged) {
                return response()->json(['status' => 'Error', 'message' => 'You can only reply to students who have messaged you.'], 403);
            }
        }

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiverId,
            'message' => $messageContent,
        ]);

        event(new MessageSent($message));

        return response()->json(['status' => 'Message Sent!', 'receiver_id' => $receiverId]);
    }
}