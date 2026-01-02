<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\ChatSession;
use App\Models\Course;

class HistoryController extends Controller
{
    private function sidebarCourses()
    {
        return Course::with('clos')->get();
    }

    /**
     * List sidebar sessions (mapped) for current user
     */
    private function listSessionsForSidebar($userId)
    {
        return ChatSession::where('user_id', $userId)
            ->with(['clo.course', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($s) {
                $lastMsg = $s->messages->first();

                return [
                    'id'      => $s->id,
                    'title'   => $s->title ?? 'Percakapan',
                    'course'  => $s->clo?->course?->name ?? '-',
                    'clo'     => $s->clo?->title ?? '-',
                    'snippet' => $lastMsg?->message ? mb_strimwidth($lastMsg->message, 0, 60, '...') : '',
                    // ✅ time dihapus (kamu bilang ga perlu)
                ];
            });
    }

    /**
     * Convert session messages to view format
     */
    private function mapMessages($session)
    {
        return $session->messages->map(fn($m) => [
            'role' => $m->sender === 'bot' ? 'bot' : 'user',
            'content' => $m->message,
        ])->toArray();
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $sessions = $this->listSessionsForSidebar($user->id);

        // ✅ auto pilih session terbaru (first) kalau ada
        $activeId = $sessions->first()['id'] ?? null;

        $activeSession = $activeId
            ? $sessions->first()
            : [
                'id' => null,
                'title' => 'Pilih percakapan',
                'course' => '-',
                'clo' => '-',
              ];

        $messages = [];
        $infoText = '';

        // ✅ kalau ada active session, load full messages
        if ($activeId) {
            $session = ChatSession::where('user_id', $user->id)
                ->with(['clo.course', 'messages' => fn($q) => $q->orderBy('id')])
                ->findOrFail($activeId);

            $messages = $this->mapMessages($session);
            $infoText = $session->clo?->summary ?? $session->clo?->description ?? 'Tidak ada info.';
        }

        return view('student.history', [
            'courses' => $this->sidebarCourses(),
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'messages' => $messages,   // ✅ sekarang tidak kosong kalau ada session
            'infoText' => $infoText,
        ]);
    }

    public function show(Request $request, $id): View
    {
        $user = $request->user();

        $session = ChatSession::where('user_id', $user->id)
            ->with(['clo.course', 'messages' => fn($q) => $q->orderBy('id')])
            ->findOrFail($id);

        $sessions = $this->listSessionsForSidebar($user->id);

        $activeSession = [
            'id'     => $session->id,
            'title'  => $session->title ?? 'Percakapan',
            'course' => $session->clo?->course?->name ?? '-',
            'clo'    => $session->clo?->title ?? '-',
            // ✅ time dihapus
        ];

        $messages = $this->mapMessages($session);

        $infoText = $session->clo?->summary ?? $session->clo?->description ?? 'Tidak ada info.';

        return view('student.history', [
            'courses' => $this->sidebarCourses(),
            'sessions' => $sessions,
            'activeSession' => $activeSession,
            'messages' => $messages,
            'infoText' => $infoText,
        ]);
    }
}
