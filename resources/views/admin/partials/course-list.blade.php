{{-- resources/views/admin/partials/course-list.blade.php --}}
@php
    $currentCourse = $currentCourse ?? null;

    $courses = [
        1 => 'Algoritma Pemrograman',
        2 => 'Algoritma Pemrograman Lanjutan',
        3 => 'Analisis Kompleksitas Algoritma',
        4 => 'Strategi Algoritma',
    ];
@endphp

<aside class="w-80 bg-white border-r flex flex-col">
    {{-- Header --}}
    <div class="px-6 pt-6 pb-4 border-b">
        <p class="text-xs font-semibold tracking-[0.18em] uppercase text-[#B8352E]">
            Admin Panel
        </p>
        <h1 class="mt-1 text-lg font-semibold">
            Course
        </h1>
    </div>

    {{-- Course list --}}
    <div class="flex-1 overflow-y-auto">
        @foreach($courses as $id => $name)
            <a href="{{ route('admin.course.show', $id) }}"
               class="flex items-center gap-3 px-6 h-14 border-b
               {{ $currentCourse === $id ? 'bg-[#B8352E]/10 text-[#B8352E]' : 'hover:bg-slate-50' }}">
                <div class="w-7 h-7 rounded-xl bg-[#B8352E]/10 flex items-center justify-center">
                    &lt;/&gt;
                </div>
                <span class="text-sm font-medium">
                    {{ $name }}
                </span>
            </a>
        @endforeach
    </div>
</aside>