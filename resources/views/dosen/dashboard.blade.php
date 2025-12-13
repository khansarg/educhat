{{-- resources/views/dosen/dashboard.blade.php --}}
@extends('layouts.dosen')

@section('title', 'Dashboard Dosen - EduChat')

@section('content')
@php
    // ====== DUMMY STATE (frontend only) ======
    $courseTitle = 'Strategi Algoritma';

    // aktif CLO ambil dari query param (?clo=1/2/3) biar bisa “diakses”
    $activeClo = (int) request('clo', 1);
    if (!in_array($activeClo, [1,2,3])) $activeClo = 1;

    $ringkasanUmum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget nunc vel quam suscipit tincidunt. Vivamus nibh elit, tempor non est eu, rhoncus ornare nisl. Vestibulum massa magna, efficitur eu pellentesque vitae, fermentum ac orci. Mauris rutrum dolor at lacus molestie ultricies. Maecenas eget tincidunt tortor. Praesent vitae ipsum sit amet mauris commodo suscipit. Donec a velit sed quam convallis feugiat eget ac sapien. Integer dui erat, consequat eu euismod quis, faucibus et metus.';

    $ringkasanClo = [
        1 => 'Ringkasan untuk CLO 1. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer dui erat, consequat eu euismod quis, faucibus et metus.',
        2 => 'Ringkasan untuk CLO 2. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent vitae ipsum sit amet mauris commodo suscipit.',
        3 => 'Ringkasan untuk CLO 3. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas eget tincidunt tortor. Mauris rutrum dolor at lacus.',
    ];

    $materi = [
        ['id'=>1, 'judul'=>'Brute Force', 'pdf'=>'aaa.pdf', 'updated'=>'2025-12-10'],
        ['id'=>2, 'judul'=>'Judul Materi', 'pdf'=>'Judul Materi.pdf', 'updated'=>'2025-12-09'],
        ['id'=>3, 'judul'=>'Judul Materi', 'pdf'=>'Judul Materi.pdf', 'updated'=>'2025-12-08'],
        ['id'=>4, 'judul'=>'Judul Materi', 'pdf'=>'Judul Materi.pdf', 'updated'=>'2025-12-07'],
    ];

@endphp

{{-- WRAPPER CARD BESAR (kayak kanvas putih di screenshot) --}}
<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-10">

    {{-- TITLE --}}
    <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">
        {{ $courseTitle }}
    </h1>

    {{-- UPLOAD HEADER --}}
    <div class="mt-10">
        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 mb-3">
            Upload Foto Header Mata Kuliah
        </p>

        <div class="rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950/30 h-[170px]
                    flex items-center justify-center">
            <div class="flex items-center gap-3 text-slate-400 dark:text-slate-500 text-sm">
                <span class="text-lg">📷</span>
                <span>Upload foto disini</span>
            </div>
        </div>
    </div>

    {{-- RINGKASAN MATERI (UMUM) - EDITABLE --}}
    <div class="mt-8 rounded-2xl border border-slate-200/70 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-[0_6px_18px_rgba(15,23,42,0.06)]">
        <div class="flex items-start justify-between gap-4">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                Ringkasan Materi
            </p>

            <div class="flex items-center gap-2">
                {{-- mode view --}}
                <button id="summaryEditBtn"
                        type="button"
                        class="h-9 px-4 rounded-xl border border-slate-200 dark:border-slate-700
                            text-sm font-semibold text-[#9D1535]
                            hover:bg-slate-50 dark:hover:bg-slate-800/60 transition">
                    Edit
                </button>

                {{-- mode edit --}}
                <button id="summarySaveBtn"
                        type="button"
                        class="hidden h-9 px-4 rounded-xl bg-[#9D1535] text-white text-sm font-semibold
                            hover:bg-[#82112c] transition">
                    Simpan
                </button>

                <button id="summaryCancelBtn"
                        type="button"
                        class="hidden h-9 px-4 rounded-xl bg-slate-200 text-slate-700 text-sm font-semibold
                            hover:bg-slate-300 transition">
                    Batal
                </button>
            </div>
        </div>

        {{-- VIEW MODE --}}
        <p id="summaryText"
        class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-slate-300">
            {{ $ringkasanUmum }}
        </p>

        {{-- EDIT MODE --}}
        <textarea id="summaryTextarea"
                class="hidden mt-3 w-full min-h-[140px] rounded-2xl border border-slate-300 dark:border-slate-700
                        bg-white dark:bg-slate-950/30 px-4 py-3 text-sm text-slate-900 dark:text-slate-50
                        outline-none focus:ring-4 focus:ring-[#9D1535]/10 focus:border-[#9D1535]"
                placeholder="Tulis ringkasan materi di sini...">{{ $ringkasanUmum }}</textarea>

        <p id="summaryHint"
        class="hidden mt-2 text-xs text-slate-400">
            Tips: tulis ringkasan singkat, padat, dan mudah dipahami.
        </p>
    </div>


</div>

{{-- CARD BAWAH: PILIH CLO + RINGKASAN CLO + DAFTAR MATERI --}}
<div class="mt-8 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm p-10">

    {{-- PILIH CLO --}}
    <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 mb-4">
        Pilih CLO
    </p>

    <div class="grid grid-cols-3 gap-4">
        @foreach([1,2,3] as $clo)
            <a href="{{ url()->current() }}?clo={{ $clo }}"
               class="h-12 rounded-2xl flex items-center justify-center text-sm font-semibold border
                      transition
                      {{ $activeClo === $clo
                          ? 'bg-[#9D1535] border-[#9D1535] text-white shadow-sm'
                          : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-900 dark:text-slate-50 hover:bg-slate-50 dark:hover:bg-slate-800/40' }}">
                CLO {{ $clo }}
            </a>
        @endforeach
    </div>

    {{-- RINGKASAN MATERI (PER CLO) - EDITABLE --}}
    <div class="mt-6 rounded-2xl border border-slate-200/70 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-[0_6px_18px_rgba(15,23,42,0.06)]">
        <div class="flex items-start justify-between gap-4">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                Ringkasan Materi (CLO {{ $activeClo }})
            </p>

            <div class="flex items-center gap-2">
                {{-- VIEW MODE --}}
                <button id="cloSummaryEditBtn"
                        type="button"
                        class="h-9 px-4 rounded-xl border border-slate-200 dark:border-slate-700
                            text-sm font-semibold text-[#9D1535]
                            hover:bg-slate-50 dark:hover:bg-slate-800/60 transition">
                    Edit
                </button>

                {{-- EDIT MODE --}}
                <button id="cloSummarySaveBtn"
                        type="button"
                        class="hidden h-9 px-4 rounded-xl bg-[#9D1535] text-white text-sm font-semibold
                            hover:bg-[#82112c] transition">
                    Simpan
                </button>

                <button id="cloSummaryCancelBtn"
                        type="button"
                        class="hidden h-9 px-4 rounded-xl bg-slate-200 text-slate-700 text-sm font-semibold
                            hover:bg-slate-300 transition">
                    Batal
                </button>
            </div>
        </div>

        {{-- biar JS tau ini CLO berapa --}}
        <input type="hidden" id="activeCloValue" value="{{ $activeClo }}">

        {{-- VIEW MODE --}}
        <p id="cloSummaryText"
        class="mt-2 text-sm leading-relaxed text-slate-700 dark:text-slate-300">
            {{ $ringkasanClo[$activeClo] ?? $ringkasanClo[1] }}
        </p>

        {{-- EDIT MODE --}}
        <textarea id="cloSummaryTextarea"
                class="hidden mt-3 w-full min-h-[140px] rounded-2xl border border-slate-300 dark:border-slate-700
                        bg-white dark:bg-slate-950/30 px-4 py-3 text-sm text-slate-900 dark:text-slate-50
                        outline-none focus:ring-4 focus:ring-[#9D1535]/10 focus:border-[#9D1535]"
                placeholder="Tulis ringkasan CLO di sini...">{{ $ringkasanClo[$activeClo] ?? $ringkasanClo[1] }}</textarea>

        <p id="cloSummaryHint"
        class="hidden mt-2 text-xs text-slate-400">
            Ringkasan ini khusus untuk CLO {{ $activeClo }}.
        </p>
    </div>


    
    {{-- HEADER DAFTAR MATERI + BUTTON --}}
    <div class="mt-8 flex items-center justify-between">
        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">
            Daftar Materi
        </p>

        <a href="{{ route('dosen.materi.create', ['clo' => $activeClo]) }}"
        class="inline-flex items-center gap-2 px-5 h-11 rounded-2xl bg-[#9D1535] text-white font-semibold text-sm shadow-sm hover:bg-[#82112c] transition">
            <span class="text-lg leading-none">＋</span>
            Tambah Materi
        </a>

    </div>


    {{-- TABLE --}}
    <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200/70 dark:border-slate-800">
        <table class="w-full text-sm">
            <thead class="bg-[#F6D5DB] dark:bg-slate-800/60">
                <tr class="text-slate-900 dark:text-slate-100">
                    <th class="py-3 px-4 font-semibold">Judul Materi</th>
                    <th class="py-3 px-4 font-semibold">File PDF</th>
                    <th class="py-3 px-4 font-semibold">Terakhir diperbaharui</th>
                    <th class="py-3 px-4 font-semibold">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800">
                @foreach($materi as $row)
                    <tr class="bg-white dark:bg-slate-900">
                        <td class="py-3 px-4 text-slate-900 dark:text-slate-50">
                            {{ $row['judul'] }}
                        </td>
                        <td class="py-3 px-4 text-slate-700 dark:text-slate-300">
                            {{ $row['pdf'] }}
                        </td>
                        <td class="py-3 px-4 text-slate-700 dark:text-slate-300">
                            {{ $row['updated'] }}
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center justify-center gap-2">

                                {{-- EDIT -> ke page edit --}}
                                <a href="{{ route('dosen.materi.edit', ['id' => $row['id'], 'clo' => $activeClo]) }}"
                                class="inline-flex items-center gap-2 px-3 h-9 rounded-xl
                                        border border-slate-200 dark:border-slate-700
                                        hover:bg-slate-50 dark:hover:bg-slate-800/60
                                        text-slate-700 dark:text-slate-200">
                                    <span>✏️</span>
                                    <span class="text-sm font-medium">Edit</span>
                                </a>

                                {{-- HAPUS (dummy dulu) --}}
                                <form action="{{ route('dosen.materi.destroy', $row['id']) }}" method="POST"
                                    onsubmit="return confirm('Hapus materi ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-3 h-9 rounded-xl
                                                border border-red-200 dark:border-red-900/40
                                                hover:bg-red-50 dark:hover:bg-red-950/40
                                                text-red-600 dark:text-red-400">
                                        <span>🗑️</span>
                                        <span class="text-sm font-medium">Hapus</span>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
