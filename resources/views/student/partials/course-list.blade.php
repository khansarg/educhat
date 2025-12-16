{{-- resources/views/student/partials/course-list.blade.php --}}
@php
    $currentCourse = $currentCourse ?? null; // 1..4
    $currentClo    = $currentClo ?? null;    // 1..N

    $courses = [
        1 => 'Algoritma Pemrograman',
        2 => 'Algoritma Pemrograman (Lanjutan)',
        3 => 'Analisis Kompleksitas Algoritma',
        4 => 'Strategi Algoritma',
    ];

    $courseClos = [
        1 => [
            1 => 'CLO 1: Pengenalan materi dasar',
            2 => 'CLO 2: Struktur kontrol & logika',
            3 => 'CLO 3: Implementasi algoritma sederhana',
        ],
        2 => [
            1 => 'CLO 1: Rekursi',
            2 => 'CLO 2: Sorting & Searching',
            3 => 'CLO 3'
        ],
        3 => [
            1 => 'CLO 1: Notasi Big-O',
            2 => 'CLO 2: Analisis iteratif',
            3 => 'CLO 3: Analisis rekursif',
        ],
        4 => [
            1 => 'CLO 1: Divide and Conquer',
            2 => 'CLO 2: Greedy',
        ],
    ];
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
        @foreach($courses as $id => $courseName)
            @php
                $isActiveCourse = $currentCourse === $id;
            @endphp

            <details class="group border-b border-slate-100 dark:border-slate-800" {{ $isActiveCourse ? 'open' : '' }}>
                <summary class="flex items-center justify-between px-6 h-14 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/60">
                    <a href="{{ route('course.show', $id) }}" class="flex items-center gap-3 flex-1">
                        <div class="w-7 h-7 rounded-xl bg-[#B8352E]/10 flex items-center justify-center">
                            <span class="text-[13px] text-[#B8352E]">&lt;/&gt;</span>
                        </div>
                        <span class="text-sm font-medium {{ $isActiveCourse ? 'text-[#B8352E]' : 'text-slate-900 dark:text-slate-50' }}">
                            {{ $courseName }}
                        </span>
                    </a>

                    <span class="ml-2 text-xs text-slate-400 dark:text-slate-500 transition-transform group-open:rotate-180">
                        ⌄
                    </span>
                </summary>

                {{-- CLO buttons --}}
                @if(isset($courseClos[$id]))
                    <div class="px-6 pb-4 flex flex-col gap-2">
                        @foreach($courseClos[$id] as $cloId => $cloLabel)
                            @php
                                $isActiveClo = $isActiveCourse && $currentClo === $cloId;
                            @endphp

                            <a href="{{ route('course.clo', ['course' => $id, 'clo' => $cloId]) }}"
                                
                               class="w-full flex items-center gap-3 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors
                                      {{ $isActiveClo
                                            ? 'bg-[#B8352E] border-[#B8352E] text-white shadow-sm'
                                            : 'border-slate-200 dark:border-slate-700 text-[#B8352E] hover:bg-[#B8352E]/10 dark:hover:bg-[#B8352E]/20' }}">

                                {{-- ICON --}}
                                <span class="text-base {{ $isActiveClo ? 'text-white' : 'text-[#B8352E]' }}">
                                    @if ($cloId === 1)
                                        🎓
                                    @elseif ($cloId === 2)
                                        📘
                                    @elseif ($cloId === 3)
                                        💬
                                    @else
                                        ⭐
                                    @endif
                                </span>

                                {{-- LABEL --}}
                                <span class="{{ $isActiveClo ? 'text-white' : 'text-[#B8352E]' }}">
                                    {{ $cloLabel }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </details>
        @endforeach
    </div>
</aside>
