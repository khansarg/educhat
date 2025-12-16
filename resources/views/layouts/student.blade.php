{{-- resources/views/layouts/student.blade.php --}}
@extends('layouts.base')

@section('body')
<div class="flex h-screen">

    {{-- Sidebar ikon kiri --}}
    @include('student.partials.sidebar')

    {{-- Sidebar kedua (default course list, bisa diganti history) --}}
    @hasSection('sidebar_secondary')
        @yield('sidebar_secondary')
    @else
        @include('student.partials.course-list')
    @endif

    {{-- Konten utama --}}
    <main class="flex-1 overflow-y-auto px-10 py-10">
        @yield('content')
    </main>

</div>
@endsection
