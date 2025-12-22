{{-- resources/views/admin/partials/course-list.blade.php --}}
@php
  $courses = $courses ?? collect();
  $currentCourse = $currentCourse ?? null;
  $currentClo = $currentClo ?? null;
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

    <a href="{{ route('admin.course.create') }}"
       class="mx-6 my-4 px-4 py-2 rounded-xl bg-[#B8352E] text-white text-sm text-center">
        + Tambah Course
    </a>

    {{-- Course list --}}
    <div class="flex-1 overflow-y-auto">
        @foreach($courses as $c)
            <a href="{{ route('admin.course.show', $c->id) }}"
               class="flex items-center gap-3 px-6 h-14 border-b
               {{ ($currentCourse ?? null) == $c->id ? 'bg-[#B8352E]/10 text-[#B8352E]' : 'hover:bg-slate-50' }}">

                <div class="w-7 h-7 rounded-xl bg-[#B8352E]/10 flex items-center justify-center">
                    &lt;/&gt;
                </div>

                <span class="text-sm font-medium">
                    {{ $c->name }}
                </span>
            </a>
        @endforeach
    </div>
</aside>
