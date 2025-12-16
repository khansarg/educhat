<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function create(Request $request)
    {
        // dummy view form
        return view('dosen.materi.form', [
            'mode' => 'create',
            'clo'  => (int) $request->query('clo', 1),
        ]);
    }

    public function edit(Request $request, $id)
    {
        // dummy data materi
        $materi = [
            'id' => $id,
            'judul' => 'Brute Force',
            'pdf' => 'aaa.pdf',
        ];

        return view('dosen.materi.form', [
            'mode' => 'edit',
            'clo'  => (int) $request->query('clo', 1),
            'materi' => $materi,
        ]);
    }

    public function destroy($id)
    {
        // dummy: nanti kalau DB tinggal delete beneran
        return redirect()
            ->route('dosen.dashboard')
            ->with('success', "Materi #{$id} berhasil dihapus (dummy).");
    }
}

