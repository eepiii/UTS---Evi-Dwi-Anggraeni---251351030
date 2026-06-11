<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    // 1. Menampilkan halaman chat & riwayat pesan
    public function index()
    {
        $messages = ChatMessage::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('chat.index', compact('messages'));
    }

    // 2. Endpoint REST API menggunakan Groq (Llama 3) - BEBAS LIMIT 0
    public function stream(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:5000',
        ]);

        $prompt = $request->input('prompt');
        $apiKey = env('GROQ_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->withOptions([
            'verify' => false,
        ])->post("https://api.groq.com/openai/v1/chat/completions", [
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ]);

        if ($response->failed()) {
            return response()->json([
                'error' => 'Detail Eror Groq: ' . $response->body()
            ], 500);
        }

        $data = $response->json();
        $aiText = $data['choices'][0]['message']['content'] ?? null;

        if (!$aiText) {
            return response()->json([
                'error' => 'Format respons Groq kosong: ' . json_encode($data)
            ], 500);
        }

        ChatMessage::create([
            'user_id' => auth()->id(),
            'prompt' => $prompt,
            'response' => $aiText,
        ]);

        return response()->json([
            'text' => $aiText
        ]);
    }
}