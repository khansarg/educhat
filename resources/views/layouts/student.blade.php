{{-- resources/views/layouts/student.blade.php --}}
@extends('layouts.base')


@section('body')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="flex h-screen">

    {{-- Sidebar ikon kiri --}}
    @include('student.partials.sidebar')

    {{-- Sidebar kedua (default course list, bisa diganti history) --}}
    {{-- SIDEBAR TENGAH --}}
@hasSection('middle_sidebar')
  @yield('middle_sidebar')
@else
  {{-- fallback default (kalau halaman nggak set) --}}
  @include('student.partials.course-list', [
    'courses' => $courses ?? collect(),
    'currentCourse' => $currentCourse ?? null,
    'currentClo' => $currentClo ?? null,
  ])
@endif


    {{-- Konten utama --}}
    <main class="flex-1 overflow-y-auto px-10 py-10">
        @yield('content')
    </main>

</div>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
@stack('scripts') 
@endsection
