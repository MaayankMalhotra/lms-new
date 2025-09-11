<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
public function index()
{
    return view('chatbot');
}

 public function send(Request $request)
    {
        $userMessage = $request->input('message');
        dd($userMessage);

        // Call Ollama API
        $response = Http::post('http://127.0.0.1:11434/api/chat', [
            'model' => 'qwen2:1.5b',
            'messages' => [
                ['role' => 'user', 'content' => $userMessage],
            ],
            'stream' => false,
        ]);

        return response()->json([
            'reply' => $response->json()['message']['content'] ?? 'No response',
        ]);
    }

}
