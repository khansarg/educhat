<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    // list chat history seperti sidebar ChatGPT
    public function index(Request $request)
    {
        $sessions = $request->user()
            ->chatSessions()
            ->with('clo.course')
            ->orderByDesc('updated_at')
            ->get();

        return response()->json($sessions);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'clo_id' => 'required|exists:clos,id',
            'title'  => 'nullable|string',
        ]);

        $session = ChatSession::create([
            'user_id' => $request->user()->id,
            'clo_id'  => $data['clo_id'],
            'title'   => $data['title'] ?? 'New chat',
        ]);

        return response()->json($session, 201);
    }

    public function show(ChatSession $chatSession, Request $request)
    {
        if ($chatSession->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $chatSession->load('clo', 'messages');
        return response()->json($chatSession);
    }

    // kirim pesan ke OpenAI
    public function sendMessage(Request $request, ChatSession $chatSession)
    {
        if ($chatSession->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validate([
            'message' => 'required|string',
        ]);

        // simpan pesan user
        $userMessage = ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'sender'          => 'user',
            'message'         => $data['message'],
        ]);

        $clo = $chatSession->clo()->with('course', 'materials.files')->first();

        $materialsText = $clo->materials->map(function ($m) {
            $fileList = $m->files->map(fn($f) => $f->download_url)->implode(', ');
            return "- {$m->title}: {$m->description} (file: {$fileList})";
        })->implode("\n");

        $systemPrompt =
            "Kamu adalah asisten pembelajaran untuk mata kuliah {$clo->course->name}. ".
            "Fokus pada CLO: {$clo->title}.\n\n".
            "Deskripsi CLO: {$clo->description}\n\n".
            "Ringkasan CLO: {$clo->summary}\n\n".
            "Daftar materi:\n{$materialsText}\n\n".
            "Jawab HANYA berdasarkan konteks di atas. ".
            "Jika pertanyaan di luar CLO ini, katakan bahwa kamu hanya dibatasi pada CLO ini.";

        $history = $chatSession->messages()
            ->orderBy('created_at')
            ->get()
            ->map(function ($m) {
                return [
                    'role'    => $m->sender === 'user' ? 'user' : 'assistant',
                    'content' => $m->message,
                ];
            })
            ->toArray();

        array_unshift($history, [
            'role' => 'system',
            'content' => $systemPrompt,
        ]);

        $response = Http::withToken(config('services.openai.key'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model'    => 'gpt-4o-mini',
                'messages' => $history,
            ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to contact OpenAI',
                'error'   => $response->body(),
            ], 500);
        }

        $assistantText = $response->json('choices.0.message.content');

        $assistantMessage = ChatMessage::create([
            'chat_session_id' => $chatSession->id,
            'sender'          => 'assistant',
            'message'         => $assistantText,
        ]);

        $chatSession->touch();

        return response()->json([
            'user_message'      => $userMessage,
            'assistant_message' => $assistantMessage,
        ]);
    }
    
}
