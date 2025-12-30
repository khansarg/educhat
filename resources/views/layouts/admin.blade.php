{{-- resources/views/layouts/admin.blade.php --}}
@extends('layouts.base')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('body')
<div class="flex h-screen">

    {{-- ICON SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- COURSE LIST --}}
    @include('admin.partials.course-list')

    {{-- MAIN CONTENT --}}
   <main class="flex-1 overflow-y-auto px-10 py-10 bg-[#F4F7FB] dark:bg-slate-950">
    @yield('content')
</main>


</div>
@endsection
