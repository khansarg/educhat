{{-- resources/views/student/partials/history-list.blade.php --}}
<aside class="w-80 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 flex flex-col">
    <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800">
        <h1 class="text-sm font-semibold text-[#B8352E]">Riwayat Percakapan</h1>
    </div>

    <div class="flex-1 overflow-y-auto">
        @foreach($sessions as $session)
            @php $active = $activeSession['id'] === $session['id']; @endphp

            <a href="{{ route('history.show', $session['id']) }}"
               class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-slate-800
                      {{ $active ? 'bg-[#B8352E] text-white' : 'hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">
                ...
            </a>
        @endforeach
    </div>
</aside>
