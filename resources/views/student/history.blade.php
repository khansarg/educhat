@extends('layouts.student')

@section('title', 'Riwayat Percakapan - EduChat')

@section('sidebar_secondary')
    @include('student.partials.history-list')
@endsection

@section('content')
 
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Percakapan - EduChat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F4F7FB] font-sans text-slate-900 dark:bg-slate-950 dark:text-slate-100">

<div class="flex h-screen">

    {{-- 3) KOLOM TENGAH: CHAT --}}
    <main class="flex-1 flex flex-col border-r border-slate-100 dark:border-slate-800 px-8 py-6">
        {{-- Header chat --}}
        <header class="flex items-center justify-between bg-white dark:bg-slate-900 rounded-3xl px-6 py-4 mb-4 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('history') }}"
                   class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
                    <span class="text-lg">&lt;</span>
                </a>

                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="text-[#B8352E] text-lg">&lt;/&gt;</span>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                            {{ $activeSession['title'] }} - {{ $activeSession['course'] }}
                        </p>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        CLO {{ $activeSession['clo'] }}
                    </p>
                </div>
            </div>

            <button
                id="infoToggleBtn"
                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800"
                type="button">
                <span class="text-sm">📘</span>
            </button>
        </header>

        {{-- Daftar pesan --}}
        <div class="flex-1 overflow-y-auto space-y-4 pr-2 pt-2">
            @forelse($messages as $message)
                @if($message['role'] === 'bot')
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">
                            🤖
                        </div>
                        <div class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
                            <p>{{ $message['content'] }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start justify-end gap-3">
                        <div class="max-w-xl rounded-2xl bg-[#B8352E] text-white shadow-sm px-4 py-3 text-sm">
                            <p>{{ $message['content'] }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-full bg-slate-300 flex items-center justify-center text-xs font-semibold">
                            AH
                        </div>
                    </div>
                @endif
            @empty
                <p class="text-xs text-slate-400">Belum ada percakapan untuk sesi ini.</p>
            @endforelse
        </div>

        {{-- Suggest + input --}}
        <div class="mt-4">
            <div class="flex flex-wrap gap-3 mb-3">
                <button
                    class="px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                    Suggest satu
                </button>
                <button
                    class="px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                    Suggest satu
                </button>
                <button
                    class="px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                    Suggest satu
                </button>
            </div>

            <div
                class="bg-white dark:bg-slate-900 rounded-[32px] shadow-[0_12px_40px_rgba(15,23,42,0.10)] px-5 py-3 flex items-end gap-3">
                <textarea
                    class="flex-1 resize-none border-none focus:ring-0 focus:outline-none text-sm bg-transparent placeholder:text-slate-400 dark:placeholder:text-slate-500"
                    rows="2"
                    placeholder="Tanya pertanyaan disini..."></textarea>

                <button
                    type="button"
                    class="w-10 h-10 rounded-full bg-[#B8352E] flex items-center justify-center text-white shadow-md hover:bg-[#8f251f]">
                    <span class="text-sm">↑</span>
                </button>
            </div>
        </div>
    </main>

    {{-- 4) PANEL KANAN: MATA KULIAH --}}
    <aside
        id="cloInfoPanel"
        class="w-80 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-6">
        <h2 class="text-sm font-semibold text-[#B8352E] mb-3">
            Mata Kuliah
        </h2>

        <div class="text-xs leading-relaxed text-slate-700 dark:text-slate-300 space-y-3">
            <p>{{ $infoText }}</p>
            {{-- kalau mau, bisa tambah paragraf dummy lain --}}
        </div>
    </aside>

</div>

</body>
</html>
@endsection