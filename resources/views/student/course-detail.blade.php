{{-- resources/views/student/course-detail.blade.php --}}
@extends('student.layout')

@section('title', $course->name . ' - EduChat')

{{-- Middle Sidebar: Course List --}}
@section('middle_sidebar')
    @include('student.partials.course-list', [
        'courses' => $courses,
        'currentCourse' => $course->id
    ])
@endsection

@section('content')
<div class="max-w-5xl mx-auto bg-white dark:bg-slate-900 rounded-[28px] shadow-[0_20px_50px_rgba(15,23,42,0.08)] p-10">

    <h1 class="text-2xl font-semibold flex items-center gap-2 mb-6 text-slate-900 dark:text-slate-100">
        <span class="text-[#B8352E]">&lt;/&gt;</span>
        {{ $course->name }}
    </h1>

    <div class="rounded-2xl overflow-hidden shadow-md mb-10">
        <img
            src="{{ asset('images/course-' . $course->id . '.png') }}"
            alt="{{ $course->name }}"
            class="w-full object-cover"
            onerror="this.style.display='none';"
        >
    </div>

    <div class="text-sm leading-relaxed text-slate-700 dark:text-slate-300">
        <h2 class="text-base font-semibold text-slate-900 dark:text-white mb-2">Deskripsi</h2>
        <p class="mb-6">
            {{ $course->description ?? 'Deskripsi mata kuliah belum tersedia.' }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            @foreach ($course->clos as $index => $clo)
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-2">
                        Materi pada CLO {{ $index + 1 }}
                    </h3>
                   <div class="space-y-2">
  @if(($clo->materials?->count() ?? 0) === 0)
    <p class="text-sm text-slate-600 dark:text-slate-400">
      Belum ada materi untuk CLO ini.
    </p>
  @else
    <ul class="space-y-2">
      @foreach($clo->materials as $m)
        <li class="flex items-start justify-between gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white/60 dark:bg-slate-900/60">
          <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">
              {{ $m->title ?? $m->name ?? 'Materi' }}
            </p>

            @if(!empty($m->description))
              <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2">
                {{ $m->description }}
              </p>
            @endif
          </div>

          {{-- LINK PDF (files) --}}
          @if(($m->files?->count() ?? 0) === 0)
  <span class="shrink-0 text-xs text-slate-400">Tidak ada file</span>
@else
  <div class="shrink-0 flex flex-col items-end gap-1">
    @foreach($m->files as $f)
      @php
        $url = Storage::url($f->pdf_path); // This should generate the correct link

        $label = $f->name ?? 'PDF';
      @endphp
      <a href="{{ $url }}" target="_blank"
         class="text-xs font-medium text-[#B8352E] hover:underline">
        {{ $label }}
      </a>
    @endforeach
  </div>
@endif

        </li>
      @endforeach
    </ul>
  @endif
</div>


                </div>
            @endforeach
        </div>

       
    </div>

</div>
@endsection
