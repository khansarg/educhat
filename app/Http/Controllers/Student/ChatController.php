<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Clo;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function ask(Request $request, $courseId, $cloId)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'session_id' => 'nullable|integer',
        ]);

        $user = $request->user();

        $course = Course::findOrFail($courseId);

        // ✅ FIX: where + where + firstOrFail
        $clo = Clo::where('course_id', $courseId)
            ->where('id', $cloId)
            ->firstOrFail();

        // 1) Ambil session kalau ada & milik user, kalau tidak buat baru
        $session = null;

        if ($request->session_id) {
            $session = ChatSession::where('id', $request->session_id)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$session) {
            $session = ChatSession::create([
                'user_id' => $user->id,
                'clo_id'  => $clo->id,
                'title'   => ($course->name ?? 'Course') . ' - ' . ($clo->title ?? 'CLO'),
                'last_activity_at' => now(), // pastikan kolom ada
            ]);
        }

        $userText = $request->message;

        // 2) Save pesan user
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'user',
            'message' => $userText,
        ]);

        // ✅ FIX: ambil 12 terakhir, lalu urutkan
        $history = ChatMessage::where('chat_session_id', $session->id)
            ->latest('id')
            ->take(12)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($m) {
                return [
                    'role' => $m->sender === 'bot' ? 'assistant' : 'user',
                    'content' => $m->message,
                ];
            })
            ->toArray();

        $system = "Kamu adalah asisten pembelajaran untuk mahasiswa.
Fokus hanya pada CLO berikut:
Course: {$course->name}
CLO: {$clo->title}
Deskripsi CLO: " . ($clo->summary ?? $clo->description ?? 'Tidak ada deskripsi') . "
Jawab ringkas, jelas, dan edukatif dalam Bahasa Indonesia.
Jika perlu gunakan poin bernomor/bullet agar rapi.
ATURAN KETAT (WAJIB DIPATUHI):
1) Jawaban HARUS relevan langsung dengan CLO ini
2) Jika pertanyaan TIDAK sesuai CLO ini, JANGAN menjawab materinya.
3) Jika tidak sesuai, balas dengan format berikut saja:\n
   'Maaf, pertanyaan itu di luar CLO ini ({$clo->title}).\n
    Saya bisa bantu jika Anda bertanya tentang: <beri 3 contoh topik yang sesuai CLO>.
4) Jangan memberi penjelasan tambahan di luar format penolakan itu.";

        // 4) Call OpenAI
        $res = Http::withToken(env('OPENAI_API_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => env('OPENAI_MODEL_CHAT', 'gpt-4o-mini'),
                'messages' => array_merge(
                    [['role' => 'system', 'content' => $system]],
                    $history
                ),
                'temperature' => 0.4,
            ]);

        if (!$res->ok()) {
            return response()->json([
                'message' => 'AI error',
                'error' => $res->json() ?? $res->body(),
            ], 500);
        }

        $reply = data_get($res->json(), 'choices.0.message.content')
            ?? 'Maaf, aku belum bisa menjawab saat ini.';

        // 5) Save pesan bot
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'bot',
            'message' => $reply,
        ]);

        // 6) Update last_activity_at
        $session->update(['last_activity_at' => now()]);

        return response()->json([
            'session_id' => $session->id,
            'reply' => $reply,
        ]);
    }
    public function askFromSession(Request $request, $id)
{
    $request->validate([
        'message' => 'required|string|max:2000',
    ]);

    $user = $request->user();

    $session = ChatSession::where('id', $id)
        ->where('user_id', $user->id)
        ->with(['clo.course'])
        ->firstOrFail();

    $clo = $session->clo;
    $course = $clo?->course;

    // save user message
    ChatMessage::create([
        'chat_session_id' => $session->id,
        'sender' => 'user',
        'message' => $request->message,
    ]);

    // ✅ history context: ambil 12 PESAN TERBARU, lalu urutkan lama -> baru
    $history = ChatMessage::where('chat_session_id', $session->id)
        ->latest('created_at')
        ->take(12)
        ->get()
        ->reverse()
        ->values()
        ->map(function ($m) {
            $role = in_array($m->sender, ['bot', 'assistant'], true) ? 'assistant' : 'user';
            return [
                'role' => $role,
                'content' => $m->message,
            ];
        })
        ->toArray();

    $cloTitle = $clo->title ?? $clo->name ?? '-';
    $courseName = $course->name ?? '-';
    $cloDesc = $clo->summary ?? $clo->description ?? 'Tidak ada deskripsi';

    $system = <<<SYS
Kamu adalah asisten pembelajaran untuk mahasiswa.

Fokus hanya pada CLO berikut:
Course: {$courseName}
CLO: {$cloTitle}
Deskripsi CLO: {$cloDesc}

Jawab ringkas, jelas, dan edukatif dalam Bahasa Indonesia.
Jika perlu gunakan poin bernomor/bullet agar rapi.

ATURAN KETAT (WAJIB DIPATUHI):
1) Jawaban HARUS relevan langsung dengan CLO ini.
2) Jika pertanyaan TIDAK sesuai CLO ini, JANGAN menjawab materinya.
3) Jika tidak sesuai, balas dengan format berikut saja (TANPA tambahan apa pun):

Maaf, pertanyaan itu di luar CLO ini ({$cloTitle}).
Saya bisa bantu jika Anda bertanya tentang:
- <contoh topik 1 yang sesuai CLO>
- <contoh topik 2 yang sesuai CLO>
- <contoh topik 3 yang sesuai CLO>

4) Jangan memberi penjelasan tambahan di luar format penolakan itu.
SYS;

    $res = Http::withToken(env('OPENAI_API_KEY'))
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => env('OPENAI_MODEL_CHAT', 'gpt-4o-mini'),
            'messages' => array_merge(
                [['role' => 'system', 'content' => $system]],
                $history
            ),
            'temperature' => 0.4,
        ]);

    if (!$res->ok()) {
        return response()->json([
            'message' => 'AI error',
            'error' => $res->body(),
        ], 500);
    }

    $reply = data_get($res->json(), 'choices.0.message.content')
        ?? 'Maaf, aku belum bisa menjawab saat ini.';

    ChatMessage::create([
        'chat_session_id' => $session->id,
        'sender' => 'bot', // ✅ konsisten, tapi kalau kamu mau tetap "bot" juga boleh
        'message' => $reply,
    ]);

    // update activity
    try { $session->update(['last_activity_at' => now()]); } catch (\Throwable $e) {}

    return response()->json([
        'reply' => $reply,
    ]);
}

}
