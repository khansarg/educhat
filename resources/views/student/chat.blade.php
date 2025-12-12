{{-- resources/views/student/chat.blade.php --}}
@extends('student.layout')

@section('title', 'Percakapan Pembelajaran - EduChat')

@section('content')
@php
    $courseNames = [
        1 => 'Algoritma Pemrograman',
        2 => 'Algoritma Pemrograman (Lanjutan)',
        3 => 'Analisis Kompleksitas Algoritma',
        4 => 'Strategi Algoritma',
    ];

    $courseName = $courseNames[$course] ?? 'Mata Kuliah';
@endphp

<div class="flex gap-6 h-[calc(100vh-5rem)]">

    {{-- ====== LEFT: CHAT AREA ====== --}}
    <div class="flex flex-col flex-1">

        {{-- HEADER ATAS --}}
        <header
            class="flex items-center justify-between bg-white dark:bg-slate-900 rounded-3xl px-6 py-4 mb-4 shadow-sm border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3">
                {{-- icon back (dekorasi aja) --}}
                <button class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
                    <span class="text-lg">&lt;</span>
                </button>

                <div class="flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="text-[#B8352E] text-lg">&lt;/&gt;</span>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                            {{ $courseName }}
                        </p>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400">
                        CLO {{ $clo }}
                    </p>
                </div>
            </div>

            {{-- icon buku: TOGGLE RIGHT SIDEBAR --}}
            <button
                id="infoToggleBtn"
                class="w-9 h-9 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-slate-800"
                type="button">
                <span class="text-sm">📘</span>
            </button>
        </header>

        {{-- CHAT AREA --}}
        <section class="flex-1 bg-transparent rounded-3xl flex flex-col">

            {{-- LIST PESAN --}}
            <div id="chatMessages" class="flex-1 overflow-y-auto pr-2 space-y-4 pt-2">

                {{-- BOT MESSAGE 1 --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">
                        🤖
                    </div>
                    <div
                        class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
                        <p><span class="font-semibold">EduChat:</span> Halo Aqila! Ada yang bisa saya bantu?</p>
                    </div>
                </div>

                {{-- BOT MESSAGE 2 --}}
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#B8352E] flex items-center justify-center text-white text-sm">
                        🤖
                    </div>
                    <div
                        class="max-w-xl rounded-2xl bg-white dark:bg-slate-900 shadow-sm px-4 py-3 text-sm text-slate-800 dark:text-slate-100">
                        <p><span class="font-semibold">EduChat:</span> Kamu bisa bertanya seputar materi pada CLO {{ $clo }},
                            misalnya konsep dasar, contoh soal, atau langkah penyelesaian.</p>
                    </div>
                </div>

                {{-- USER MESSAGE --}}
                <div class="flex items-start justify-end gap-3">
                    <div
                        class="max-w-xl rounded-2xl bg-[#B8352E] text-white shadow-sm px-4 py-3 text-sm">
                        <p>Halo! Aku mau tanya tentang Divide and Conquer dong.</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-slate-300 flex items-center justify-center text-xs font-semibold">
                        AH
                    </div>
                </div>

            </div>

            {{-- SUGGEST MESSAGE + INPUT --}}
            <div class="mt-4">
            {{-- SUGGEST --}}
            <div class="flex flex-wrap gap-3 mb-3">
                <button
                       class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                   Jelaskan konsep Divide and Conquer
                </button>

                <button
                        class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                    Beri contoh soal & pembahasan
                </button>

                <button
                    class="suggest-msg px-5 py-2 rounded-full text-xs font-medium bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-800">
                    Bandingkan dengan Greedy
                </button>
            </div>
        </div>


                {{-- INPUT BAR --}}
                <div
                    class="bg-white dark:bg-slate-900 rounded-[32px] shadow-[0_12px_40px_rgba(15,23,42,0.10)] px-5 py-3 flex items-end gap-3">
                    <textarea
                        id="chatInput"
                        class="flex-1 resize-none border-none focus:ring-0 focus:outline-none text-sm bg-transparent placeholder:text-slate-400 dark:placeholder:text-slate-500"
                        rows="2"
                        placeholder="Tanya pertanyaanmu disini..."></textarea>

                    <button
                        id="sendBtn"
                        type="button"
                        class="w-10 h-10 rounded-full bg-[#B8352E] flex items-center justify-center text-white shadow-md hover:bg-[#8f251f]">
                        <span class="text-sm">↑</span>
                    </button>
                </div>
            </div>
        </section>
    </div>

    {{-- ====== RIGHT: INFO PANEL (MATA KULIAH) ====== --}}
   
    <aside
        id="cloInfoPanel"
        class="w-80 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 hidden">
        <h2 class="text-sm font-semibold text-[#B8352E] mb-3">
            Mata Kuliah
        </h2>

        <div class="text-xs leading-relaxed text-slate-700 dark:text-slate-300 space-y-3">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam efficitur varius eros. Aenean lobortis
                ipsum ac turpis fringilla, vel sodales felis semper. Aliquam varius hendrerit ipsum, finibus gravida erat
                venenatis vitae.
            </p>
            <p>
                Curabitur convallis vel tortor quis egestas. Sed a tellus molestie, venenatis dui vitae, semper massa.
                Praesent bibendum non risus et ornare. Cras sit amet erat eu orci fermentum posuere.
            </p>
            <p>
                Praesent feugiat fermentum ipsum id interdum. Integer sapien eros, auctor fermentum tincidunt nec, luctus
                quis turpis. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
            </p>
        </div>
    </aside>

</div>
@endsection
