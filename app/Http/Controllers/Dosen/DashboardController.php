<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // frontend dummy
        $activeClo = (int) $request->query('clo', 1);
        if (!in_array($activeClo, [1,2,3])) $activeClo = 1;

        $courseTitle = 'Strategi Algoritma';

        $ringkasanUmum = 'Lorem ipsum...';
        $ringkasanClo = [
            1 => 'Ringkasan untuk CLO 1...',
            2 => 'Ringkasan untuk CLO 2...',
            3 => 'Ringkasan untuk CLO 3...',
        ];

        // Materi dipisah per CLO (ini yang nanti paling gampang pindah ke DB)
        $materiByClo = [
            1 => [
                ['judul' => 'Brute Force', 'pdf' => 'bruteforce.pdf', 'updated' => '2025-12-10', 'aksi' => 'Edit / Hapus'],
            ],
            2 => [
                ['judul' => 'Greedy Basics', 'pdf' => 'greedy.pdf', 'updated' => '2025-12-09', 'aksi' => 'Edit / Hapus'],
            ],
            3 => [
                ['judul' => 'Dynamic Programming', 'pdf' => 'dp.pdf', 'updated' => '2025-12-08', 'aksi' => 'Edit / Hapus'],
            ],
        ];

        $materi = $materiByClo[$activeClo] ?? [];

        return view('dosen.dashboard', compact(
            'courseTitle',
            'activeClo',
            'ringkasanUmum',
            'ringkasanClo',
            'materi'
        ));
    }
}
