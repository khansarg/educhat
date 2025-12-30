{{-- resources/views/admin/partials/course-list.blade.php --}}
@php
  $courses = $courses ?? collect();
  $currentCourse = $currentCourse ?? null;
  $currentClo = $currentClo ?? null;
@endphp

<aside class="w-80 bg-white dark:bg-slate-900 border-r border-slate-200/70 dark:border-slate-800 flex flex-col">
    <div class="px-6 pt-6 pb-4 border-b border-slate-200/70 dark:border-slate-800">
        <p class="text-xs font-semibold tracking-[0.18em] uppercase text-[#B8352E]">
            Admin Panel
        </p>
        <h1 class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-50">
            Course
        </h1>
    </div>

    <a href="{{ route('admin.course.create') }}"
       class="mx-6 my-4 px-4 py-2 rounded-xl bg-[#B8352E] text-white text-sm text-center hover:opacity-95">
        + Tambah Course
    </a>

    <div class="flex-1 overflow-y-auto">
        @foreach($courses as $c)
            @php $active = (($currentCourse ?? null) == $c->id); @endphp

            <a href="{{ route('admin.course.show', $c->id) }}"
               class="flex items-center gap-3 px-6 h-14 border-b border-slate-200/70 dark:border-slate-800
                      {{ $active ? 'bg-[#B8352E]/10 text-[#B8352E]' : 'text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">

                <div class="w-7 h-7 rounded-xl bg-[#B8352E]/10 flex items-center justify-center">
                    &lt;/&gt;
                </div>

                <span class="text-sm font-medium truncate">
                    {{ $c->name }}
                </span>
            </a>
        @endforeach
    </div>
</aside>
