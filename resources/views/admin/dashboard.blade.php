@extends('layouts.admin')

@section('title', 'Admin Dashboard - EduChat')

@section('content')
<div class="flex-1 flex items-center justify-center">
    <div class="max-w-3xl w-full bg-white dark:bg-slate-900 rounded-[32px]
        shadow-[0_22px_60px_rgba(15,23,42,0.10)]
        px-16 py-12 flex flex-col items-center">
        <div class="w-[420px] max-w-full mb-8">
            <img
                src="{{ asset('images/dashboard_background.png') }}"
                alt="Ilustrasi tidak ada chat"
                class="w-full h-auto object-contain select-none"
                onerror="this.style.display='none';"
            >
        </div>
        <div class="text-center">
            <p class="text-sm font-semibold text-[#B8352E]">
Belum ada course yang dipilih.
            </p>
<p class="text-sm text-slate-600 dark:text-slate-300">
            
            Silakan pilih course di sidebar untuk mulai mengelola.
        </p>
        </div>
        
        
    </div>
</div>
@endsection
