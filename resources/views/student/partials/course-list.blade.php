{{-- resources/views/student/partials/course-list.blade.php --}}
@php
    $currentCourse = $currentCourse ?? null;
    $currentClo    = $currentClo ?? null;
@endphp

<aside class="w-80 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 flex flex-col shadow-[0_20px_40px_rgba(15,23,42,0.05)]">
    {{-- Header --}}
    <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800">
        <p class="text-xs font-semibold tracking-[0.18em] uppercase text-[#B8352E]">
            Learning Path
        </p>
        <h1 class="mt-1 text-lg font-semibold text-slate-900 dark:text-slate-50">
            Mata Kuliah
        </h1>
    </div>

    {{-- Course list --}}
    <div class="flex-1 overflow-y-auto">
        @forelse($courses as $course)
            @php
                $isActiveCourse = ($currentCourse === $course->id);
            @endphp

            <details class="group border-b border-slate-100 dark:border-slate-800" {{ $isActiveCourse ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-6 h-14 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/60">
                    {{-- Klik course -> course detail --}}
                    <a href="{{ route('course.show', $course->id) }}"
                       class="flex items-center gap-3 flex-1 min-w-0"
                       onclick="event.stopPropagation();">
                        <div class="w-7 h-7 rounded-xl bg-[#B8352E]/10 flex items-center justify-center">
                            <span class="text-[13px] text-[#B8352E]">&lt;/&gt;</span>
                        </div>
                        <span class="text-sm font-medium truncate {{ $isActiveCourse ? 'text-[#B8352E]' : 'text-slate-900 dark:text-slate-50' }}">
                            {{ $course->name }}
                        </span>
                    </a>

                    <span class="ml-2 text-xs text-slate-400 dark:text-slate-500 transition-transform group-open:rotate-180">
                        ⌄
                    </span>
                </summary>

                {{-- CLO list --}}
                @if($course->clos->count() > 0)
                    <div class="px-6 pb-4 flex flex-col gap-2">
                        @foreach($course->clos as $index => $clo)
                            @php
                                $isActiveClo = $isActiveCourse && ($currentClo === $clo->id);
                                $icons = ['🎓', '📘', '💬', '⭐', '🎯', '📚', '✨'];
                                $icon = $icons[$index % count($icons)];
                                $cloLabel = $clo->title ?? $clo->name ?? ('CLO '.($clo->order ?? $index+1));
                            @endphp

                            <a
                                href="{{ route('course.clo', ['courseId' => $course->id, 'cloId' => $clo->id]) }}"
                                class="w-full flex items-center gap-3 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors border
                                    {{ $isActiveClo
                                        ? 'bg-[#B8352E] border-[#B8352E] text-white shadow-sm'
                                        : 'border-slate-200 dark:border-slate-700 text-[#B8352E] hover:bg-[#B8352E]/10 dark:hover:bg-[#B8352E]/20' }}">

                                <span class="text-base {{ $isActiveClo ? 'text-white' : 'text-[#B8352E]' }}">
                                    {{ $icon }}
                                </span>

                                <span class="truncate {{ $isActiveClo ? 'text-white' : 'text-[#B8352E]' }}">
                                    {{ $cloLabel }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </details>
        @empty
            <div class="px-6 py-8 text-center text-sm text-slate-500">
                Belum ada mata kuliah tersedia.
            </div>
        @endforelse
    </div>
</aside>
