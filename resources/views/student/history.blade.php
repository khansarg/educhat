{{-- resources/views/student/history.blade.php --}}
@extends('student.layout')

@section('title', 'Riwayat Chat - EduChat')

@section('content')
<div class="max-w-4xl mx-auto bg-white dark:bg-slate-900 rounded-[28px] shadow-[0_20px_50px_rgba(15,23,42,0.08)] p-8">
    <h1 class="text-xl font-semibold mb-4 text-slate-900 dark:text-slate-50">Riwayat Percakapan</h1>

    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">
        Ini adalah daftar percakapanmu dengan EduChat. Kamu bisa membuka kembali untuk melanjutkan diskusi.
    </p>

    {{-- Dummy list --}}
    <div class="space-y-3 text-sm">
        @for($i = 1; $i <= 4; $i++)
            <a href="#"
               class="flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/70 transition-colors">
                <div>
                    <p class="font-medium text-slate-900 dark:text-slate-50">
                        Sesi {{ $i }} — Strategi Algoritma (CLO 1)
                    </p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        Terakhir diakses: 12 Des 2025, 20.15
                    </p>
                </div>
                <span class="text-xs text-[#B8352E]">Lanjutkan &rarr;</span>
            </a>
        @endfor
    </div>
</div>
@endsection
