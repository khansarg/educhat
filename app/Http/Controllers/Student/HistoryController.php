<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Models\Course;

class HistoryController extends Controller
{
   

   public function index(Request $request): View
{
    $user = $request->user();

    $sessions = ChatSession::where('user_id', $user->id)
        ->with(['clo.course', 'messages' => fn($q) => $q->latest()->limit(1)])
        ->orderByDesc('updated_at')
        ->get()
        ->map(function ($s) {
            $lastMsg = $s->messages->first();

            return [
                'id'      => $s->id,
                'title'   => $s->title ?? 'Percakapan',
                'course'  => $s->clo?->course?->name ?? '-',
                'clo'     => $s->clo?->name ?? '-',
                'snippet' => $lastMsg?->message ? mb_strimwidth($lastMsg->message, 0, 60, '...') : '',
                'time'    => optional($s->updated_at)->format('d M H:i'),
            ];
        });

    $activeSession = $sessions->first() ?? [
        'id' => null,
        'title' => 'Pilih percakapan',
        'course' => '-',
        'clo' => '-',
        'time' => null,
    ];

    $courses = Course::with('clos')->get();

    return view('student.history', [
        'courses' => $courses,          // ✅ penting biar course-list gak error
        'sessions' => $sessions,
        'activeSession' => $activeSession,
        'messages' => [],
        'infoText' => '',
    ]);
}

public function show(Request $request, $id): View
{
    $user = $request->user();

    $session = ChatSession::where('user_id', $user->id)
        ->with(['clo.course', 'messages' => fn($q) => $q->orderBy('id')])
        ->findOrFail($id);

    $activeSession = [
        'id'     => $session->id,
        'title'  => $session->title ?? 'Percakapan',
        'course' => $session->clo?->course?->name ?? '-',
        'clo'    => $session->clo?->name ?? '-',
        'time'   => optional($session->updated_at)->format('d M H:i'),
    ];

    $sessions = ChatSession::where('user_id', $user->id)
        ->with(['clo.course', 'messages' => fn($q) => $q->latest()->limit(1)])
        ->orderByDesc('updated_at')
        ->get()
        ->map(function ($s) {
            $lastMsg = $s->messages->first();
            return [
                'id'      => $s->id,
                'title'   => $s->title ?? 'Percakapan',
                'course'  => $s->clo?->course?->name ?? '-',
                'clo'     => $s->clo?->name ?? '-',
                'snippet' => $lastMsg?->message ? mb_strimwidth($lastMsg->message, 0, 60, '...') : '',
                'time'    => optional($s->updated_at)->format('d M H:i'),
            ];
        });

    $messages = $session->messages->map(fn($m) => [
        'role' => $m->sender === 'bot' ? 'bot' : 'user',
        'content' => $m->message,
    ])->toArray();

    $courses = Course::with('clos')->get();

    $infoText = $session->clo?->summary ?? $session->clo?->description ?? 'Tidak ada info.';

    return view('student.history', [
        'courses' => $courses,          // ✅ konsisten
        'sessions' => $sessions,
        'activeSession' => $activeSession,
        'messages' => $messages,
        'infoText' => $infoText,        // ✅ kalau history.blade pakai ini
    ]);
}



}
