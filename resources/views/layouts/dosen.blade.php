{{-- resources/views/layouts/dosen.blade.php --}}
@extends('layouts.base')

@section('body')
<div class="flex h-screen">

    {{-- Sidebar ikon kiri (khusus dosen) --}}
    @include('dosen.partials.sidebar')

    {{-- Konten utama dosen --}}
    <main class="flex-1 overflow-y-auto px-10 py-10">
        @yield('content')
    </main>

</div>
@endsection
