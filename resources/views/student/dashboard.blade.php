<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - EduChat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#F4F7FB] font-sans text-slate-900 dark:bg-slate-950 dark:text-slate-100">

<div class="flex h-screen">

    {{-- LEFT ICON SIDEBAR (lebar 80px biar lebih lega) --}}
    <aside class="fixed inset-y-0 left-0 w-20 bg-white dark:bg-slate-900 shadow-[0_10px_35px_rgba(15,23,42,0.10)] flex flex-col justify-between z-30">
        <div class="mt-6 flex flex-col items-center gap-8">
            <div class="w-11 h-11 rounded-2xl bg-[#B8352E]/10 flex items-center justify-center text-[#B8352E] font-bold text-sm">
                </> 
            </div>

            <nav class="flex flex-col items-center gap-5">
                {{-- Learning Path - Active --}}
                <button class="w-11 h-11 rounded-2xl bg-[#B8352E] text-white flex items-center justify-center shadow-lg">
                    <span class="text-2xl">open_book</span>
                </button>

                {{-- History --}}
                <a href="{{ route('history') }}"
                   class="w-11 h-11 rounded-2xl flex items-center justify-center text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                    <span class="text-2xl">chat_bubble</span>
                </a>
            </nav>
        </div>

        <div class="mb-6 flex flex-col items-center gap-5 text-slate-400">
            <button id="darkModeToggle" class="w-11 h-11 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <span class="text-xl">dark_mode</span>
            </button>
            <button class="w-11 h-11 rounded-2xl hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <span class="text-xl">language</span>
            </button>
            <div class="w-11 h-11 rounded-2xl bg-[#B8352E] flex items-center justify-center text-white font-bold text-sm overflow-hidden">
                AH
            </div>
        </div>
    </aside>

    {{-- HEADER ATAS --}}
    <header class="fixed top-0 left-20 right-0 h-16 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800 z-20 flex items-center px-8">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#B8352E]/10 flex items-center justify-center">
                <span class="text-sm font-bold text-[#B8352E]">Edu</span>
            </div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white">EduChat</h1>
        </div>
    </header>

    {{-- LEARNING PATH SIDEBAR --}}
    <aside class="fixed inset-y-0 left-20 w-80 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 shadow-[0_20px_40px_rgba(15,23,42,0.05)] flex flex-col z-10">
        {{-- Header Learning Path --}}
        <div class="px-7 pt-7 pb-5 border-b border-slate-100 dark:border-slate-800">
            <p class="text-xs font-bold tracking-[0.2em] uppercase text-[#B8352E]">Learning Path</p>
            <h2 class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">Mata Kuliah</h2>
        </div>

        {{-- Scrollable Course List --}}
        <div class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-300">
            @php
                $courses = [
                    'Algoritma Pemrograman',
                    'Algoritma Pemrograman (lanjutan)',
                    'Analisis Kompleksitas Algoritma',
                    'Strategi Algoritma',
                ];

                $courseClos = [
                    'Algoritma Pemrograman' => ['CLO 1: Pengenalan materi dasar', 'CLO 2: Struktur kontrol & logika', 'CLO 3: Implementasi algoritma sederhana'],
                    'Algoritma Pemrograman (lanjutan)' => ['CLO 1: Rekursi', 'CLO 2: Sorting & Searching'],
                    'Analisis Kompleksitas Algoritma' => ['CLO 1: Notasi Big-O', 'CLO 2: Analisis iteratif', 'CLO 3: Analisis rekursif'],
                    'Strategi Algoritma' => ['CLO 1: Divide and Conquer', 'CLO 2: Greedy', 'CLO 3: Dynamic Programming'],
                ];
            @endphp

            @foreach($courses as $index => $course)
                <details class="group border-b border-slate-100 dark:border-slate-800" {{ $loop->first ? 'open' : '' }}>
                    <summary class="flex items-center justify-between px-7 h-16 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/60 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 rounded-xl bg-[#B8352E]/10 flex items-center justify-center">
                                <span class="text-sm text-[#B8352E] font-medium">code</span>
                            </div>
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-100">
                                {{ $course }}
                            </span>
                        </div>
                        <span class="text-slate-400 transition-transform group-open:rotate-180">down_arrow</span>
                    </summary>

                    <div class="px-7 pb-5 pt-2 space-y-3">
                        @foreach($courseClos[$course] ?? [] as $cloIndex => $cloName)
                            <a href="{{ route('course.clo', ['course' => $index + 1, 'clo' => $cloIndex + 1]) }}"
                               class="flex items-center gap-4 px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 
                                      text-sm font-medium text-[#B8352E] hover:bg-[#B8352E]/10 dark:hover:bg-[#B8352E]/20 
                                      transition-all duration-200 group">
                                <span class="text-lg">
                                    {{ $cloIndex == 0 ? 'graduation_cap' : ($cloIndex == 1 ? 'book' : 'lightbulb') }}
                                </span>
                                <span>{{ $cloName }}</span>
                            </a>
                        @endforeach
                    </div>
                </details>
            @endforeach
        </div>
    </aside>

    {{-- MAIN CONTENT - EMPTY STATE --}}
    <main class="ml-80 flex-1 flex items-center justify-center pt-16">
        <div class="max-w-3xl w-full bg-white dark:bg-slate-900 rounded-3xl shadow-2xl px-20 py-16 flex flex-col items-center">
            <div class="w-96 mb-10">
                <img src="{{ asset('images/dashboard-empty.png') }}"
                     alt="Belum ada chat"
                     class="w-full h-auto"
                     onerror="this.style.display='none'">
            </div>

            <div class="text-center">
                <h3 class="text-2xl font-bold text-slate-600 dark:text-slate-400">
                    Tidak Ada Chat Saat Ini
                </h3>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400 max-w-md">
                    Silakan pilih pembelajaran untuk melanjutkan percakapan
                </p>
            </div>
        </div>
    </main>
</div>

{{-- Custom Scrollbar (optional, tambahkan di app.css kalau belum ada) --}}
<style>
    .scrollbar-thin::-webkit-scrollbar { width: 6px; }
    .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>

</body>
</html>