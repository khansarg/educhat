@extends('layouts.admin')

@section('title', 'Admin Dashboard - EduChat')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-6rem)]">
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-10 text-center max-w-md shadow-sm">
        <img src="{{ asset('images/empty_background.png') }}" class="mx-auto mb-4 w-40" alt="empty">
        <p class="text-sm text-slate-600 dark:text-slate-300">
            Belum ada course yang dipilih.
            Silakan pilih course di sidebar untuk mulai mengelola.
        </p>
    </div>
</div>
@endsection
