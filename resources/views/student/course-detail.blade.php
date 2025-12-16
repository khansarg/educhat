{{-- resources/views/student/course-detail.blade.php --}}
@extends('layouts.student')

@section('title', 'Detail Mata Kuliah - EduChat')

@section('content')
@php
    $courseNames = [
        1 => 'Algoritma Pemrograman',
        2 => 'Algoritma Pemrograman (Lanjutan)',
        3 => 'Analisis Kompleksitas Algoritma',
        4 => 'Strategi Algoritma',
    ];

    $name = $courseNames[$course] ?? 'Mata Kuliah';
@endphp

<div class="max-w-5xl mx-auto bg-white dark:bg-slate-900 rounded-[28px] shadow-[0_20px_50px_rgba(15,23,42,0.08)] p-10">
    <h1 class="text-2xl font-semibold flex items-center gap-2 mb-6 text-slate-900 dark:text-slate-100">
        <span class="text-[#B8352E]">&lt;/&gt;</span>
        {{ $name }}
    </h1>

    <div class="rounded-2xl overflow-hidden shadow-md mb-10">
        <img
            src="{{ asset('images/course-'.$course.'.png') }}"
            alt="{{ $name }}"
            class="w-full object-cover"
            onerror="this.style.display='none';"
        >
    </div>

    <div class="text-sm leading-relaxed text-slate-700 dark:text-slate-300">
        <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-2">Deskripsi</h2>
        <p class="mb-6">
            Strategi untuk memecahkan masalah dengan efisien. Mata kuliah ini membantu kamu memahami bagaimana
            merancang algoritma yang terstruktur, optimal, dan mudah dipelihara.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Materi pada CLO 1</h3>
                <ol class="list-decimal ml-5 space-y-1">
                    <li>Brute Force</li>
                    <li>Greedy</li>
                    <li>Divide &amp; Conquer</li>
                </ol>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">Materi pada CLO 2</h3>
                <ol class="list-decimal ml-5 space-y-1">
                    <li>Backtracking</li>
                    <li>Dynamic Programming (DP)</li>
                    <li>Branch and Bound (BnB)</li>
                </ol>
            </div>
        </div>

        <h3 class="font-semibold mt-10 mb-2 text-slate-900 dark:text-white">Apa yang akan kamu pelajari?</h3>
        <p>
            Kamu akan diajak memahami berbagai pendekatan pemecahan masalah dalam ilmu komputer,
            mulai dari konsep sederhana hingga teknik optimasi tingkat lanjut, sehingga siap
            menghadapi studi lanjut maupun tantangan dunia kerja.
        </p>
    </div>
</div>
@endsection
