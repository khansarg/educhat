{{-- resources/views/student/dashboard.blade.php --}}
@extends('layouts.student')

@section('title', 'Dashboard - EduChat')

@section('content')
    <div class="flex-1 flex items-center justify-center">
        <div class="max-w-3xl w-full bg-white dark:bg-slate-900 rounded-[32px] shadow-[0_22px_60px_rgba(15,23,42,0.10)] px-16 py-12 flex flex-col items-center">
            <div class="w-[420px] max-w-full mb-8">
                <img
                    src="{{ asset('images/dashboard-empty.png') }}"
                    alt="Ilustrasi tidak ada chat"
                    class="w-full h-auto object-contain select-none"
                    onerror="this.style.display='none';"
                >
            </div>

            <div class="text-center">
                <p class="text-sm font-semibold text-[#B8352E]">
                    Tidak Ada Chat Saat Ini
                </p>
                <p class="mt-1 text-xs text-[#B8352E]">
                    Silakan pilih pembelajaran untuk melanjutkan percakapan
                </p>
            </div>
        </div>
    </div>
@endsection
