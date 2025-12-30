@extends('layouts.admin')

@section('title', 'Tambah Course')

@section('content')
<div class="max-w-xl mx-auto bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
    <h1 class="text-lg font-semibold mb-6 text-slate-900 dark:text-slate-50">Tambah Course</h1>

    <form method="POST" action="{{ route('admin.course.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="text-sm font-medium text-slate-900 dark:text-slate-100">Kode Course</label>
            <input name="code"
                   class="w-full mt-1 px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl
                          bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100
                          focus:outline-none focus:ring-2 focus:ring-[#B8352E]/20">
        </div>

        <div>
            <label class="text-sm font-medium text-slate-900 dark:text-slate-100">Nama Course</label>
            <input name="name"
                   class="w-full mt-1 px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl
                          bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100
                          focus:outline-none focus:ring-2 focus:ring-[#B8352E]/20">
        </div>

        <div>
            <label class="text-sm font-medium text-slate-900 dark:text-slate-100">Deskripsi</label>
            <textarea name="description"
                class="w-full mt-1 px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl
                       bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100
                       focus:outline-none focus:ring-2 focus:ring-[#B8352E]/20"
                rows="3"></textarea>
        </div>

        <div class="flex justify-end">
            <button class="px-6 py-2 bg-[#B8352E] text-white rounded-xl hover:opacity-95">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
