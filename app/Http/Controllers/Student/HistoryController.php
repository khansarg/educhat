<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class HistoryController extends Controller
{
    /**
     * Dummy data semua sesi history.
     */
    private function sessions(): array
    {
        return [
            1 => [
                'id'       => 1,
                'title'    => 'History chat',
                'subtitle' => 'SA · CLO 1',
                'course'   => 'SA',
                'clo'      => 1,
                'info'     => 'Deskripsi singkat mata kuliah Strategi Algoritma / SA.',
                'messages' => [
                    ['role' => 'bot',  'content' => 'Halo Aqila! Ada yang bisa saya bantu?'],
                    ['role' => 'user', 'content' => 'Halo, aku mau tanya Divide and Conquer dong.'],
                    ['role' => 'bot',  'content' => 'Ini contoh percakapan yang pernah terjadi pada sesi ini.'],
                ],
            ],
            2 => [
                'id'       => 2,
                'title'    => 'History chat',
                'subtitle' => 'AP · CLO 2',
                'course'   => 'AP',
                'clo'      => 2,
                'info'     => 'Deskripsi singkat mata kuliah Algoritma Pemrograman (CLO 2).',
                'messages' => [
                    ['role' => 'bot',  'content' => 'Halo, ini history untuk AP CLO 2.'],
                    ['role' => 'user', 'content' => 'Tolong jelasin tentang struktur kontrol ya.'],
                ],
            ],
            3 => [
                'id'       => 3,
                'title'    => 'History chat',
                'subtitle' => 'AP Lanjutan · CLO 1',
                'course'   => 'APL',
                'clo'      => 1,
                'info'     => 'Deskripsi singkat mata kuliah AP Lanjutan.',
                'messages' => [
                    ['role' => 'bot',  'content' => 'Selamat datang di history AP Lanjutan CLO 1.'],
                ],
            ],
            4 => [
                'id'       => 4,
                'title'    => 'History chat',
                'subtitle' => 'AK · CLO 1',
                'course'   => 'AK',
                'clo'      => 1,
                'info'     => 'Deskripsi singkat mata kuliah Analisis Kompleksitas.',
                'messages' => [
                    ['role' => 'bot',  'content' => 'Ini history untuk Analisis Kompleksitas CLO 1.'],
                    ['role' => 'user', 'content' => 'Aku mau review tentang notasi Big-O.'],
                ],
            ],
        ];
    }

    public function index()
    {
        $sessions = $this->sessions();
        $activeSession = reset($sessions);              // sesi pertama sebagai default
        $messages = $activeSession['messages'] ?? [];
        $infoText = $activeSession['info'] ?? '';

        return view('student.history', compact(
            'sessions',
            'activeSession',
            'messages',
            'infoText'
        ));
    }

    public function show(int $id)
    {
        $sessions = $this->sessions();

        // kalau id tidak ada, fallback ke pertama
        $activeSession = $sessions[$id] ?? reset($sessions);
        $messages = $activeSession['messages'] ?? [];
        $infoText = $activeSession['info'] ?? '';

        return view('student.history', compact(
            'sessions',
            'activeSession',
            'messages',
            'infoText'
        ));
    }
}
