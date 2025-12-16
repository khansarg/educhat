@extends('layouts.dosen')

@section('title')
    {{ $mode === 'edit' ? 'Edit Materi' : 'Tambah Materi' }} - EduChat
@endsection

@section('content')
@php
    $title = $mode === 'edit' ? 'Edit Materi' : 'Tambah Materi';
@endphp

<div class="max-w-5xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">
            {{ $title }}
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            CLO {{ $clo }} • Strategi Algoritma
        </p>
    </div>

    {{-- CARD FORM --}}
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 p-8 space-y-6">

        {{-- JUDUL MATERI --}}
        <div>
            <label class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                Judul Materi
            </label>
            <input
                type="text"
                placeholder="Contoh: Divide and Conquer"
                class="mt-2 w-full h-12 rounded-2xl border border-slate-200 dark:border-slate-700
                       px-4 text-sm bg-white dark:bg-slate-900
                       focus:outline-none focus:ring-2 focus:ring-[#9D1535]/40">
        </div>

        {{-- UPLOAD FILE --}}
        <div>
            <label class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                File Materi (PDF)
            </label>

            <div class="mt-3 h-40 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700
                        flex flex-col items-center justify-center text-slate-400 dark:text-slate-500
                        cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                <span class="text-2xl">📄</span>
                <p class="text-sm mt-2">Drag & drop file PDF di sini</p>
                <p class="text-xs">atau klik untuk upload</p>
            </div>
        </div>

        {{-- DESKRIPSI --}}
        <div>
            <label class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                Deskripsi Materi
            </label>
            <textarea
                rows="5"
                placeholder="Ringkasan singkat materi..."
                class="mt-2 w-full rounded-2xl border border-slate-200 dark:border-slate-700
                       px-4 py-3 text-sm bg-white dark:bg-slate-900
                       focus:outline-none focus:ring-2 focus:ring-[#9D1535]/40"></textarea>
        </div>

        {{-- ACTION --}}
        <div class="flex justify-end gap-4 pt-4">
            <a href="{{ route('dosen.dashboard', ['clo' => $clo]) }}"
               class="px-6 h-11 flex items-center rounded-2xl border border-slate-200 dark:border-slate-700
                      text-sm font-semibold text-slate-600 dark:text-slate-300
                      hover:bg-slate-50 dark:hover:bg-slate-800">
                Batal
            </a>

            <button
                type="button"
                class="px-6 h-11 flex items-center rounded-2xl bg-[#9D1535] text-white
                       text-sm font-semibold hover:bg-[#82112c] shadow-sm">
                {{ $mode === 'edit' ? 'Simpan Perubahan' : 'Simpan Materi' }}
            </button>
        </div>
    </div>
</div>
@endsection
