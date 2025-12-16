{{-- resources/views/layouts/admin.blade.php --}}
@extends('layouts.base')

@section('body')
<div class="flex h-screen">

    {{-- ICON SIDEBAR --}}
    @include('admin.partials.sidebar')

    {{-- COURSE LIST --}}
    @include('admin.partials.course-list')

    {{-- MAIN CONTENT --}}
    <main class="flex-1 overflow-y-auto px-10 py-10 bg-[#F4F7FB]">
        @yield('content')
    </main>

</div>
@endsection
