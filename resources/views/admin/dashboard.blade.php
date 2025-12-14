@extends('layouts.admin')

@section('title', 'Admin Dashboard - EduChat')

@section('content')
<div class="flex items-center justify-center h-full">
    <div class="bg-white rounded-3xl border p-10 text-center max-w-md">
        <img src="{{ asset('images/empty_background.png') }}" class="mx-auto mb-4 w-40">
        <p class="text-sm text-slate-600">
            Belum ada course yang dipilih.  
            Silakan pilih course di sidebar untuk mulai mengelola.
        </p>
    </div>
</div>
@endsection