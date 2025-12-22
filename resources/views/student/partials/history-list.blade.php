{{-- resources/views/student/partials/history-list.blade.php --}}
<aside class="w-80 bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800 flex flex-col">
  <div class="px-6 pt-6 pb-4 border-b border-slate-100 dark:border-slate-800">
    <p class="text-[11px] tracking-widest text-slate-400">HISTORY</p>
    <h1 class="text-sm font-semibold text-[#B8352E]">Riwayat Percakapan</h1>
  </div>

  <div class="flex-1 overflow-y-auto">
    @forelse($sessions as $s)
      @php
        $active = ($activeSession['id'] ?? null) === ($s['id'] ?? null);
      @endphp

      <a href="{{ route('history.show', $s['id']) }}"
         class="block px-6 py-4 border-b border-slate-100 dark:border-slate-800 transition
                {{ $active ? 'bg-[#B8352E]/10' : 'hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">
        <div class="flex items-start gap-3">
          <div class="w-9 h-9 rounded-full bg-[#B8352E]/10 flex items-center justify-center text-[#B8352E] text-sm">
            💬
          </div>

          <div class="min-w-0 flex-1">
            <div class="flex items-center justify-between gap-2">
              <p class="text-sm font-semibold truncate {{ $active ? 'text-[#B8352E]' : 'text-slate-900 dark:text-slate-50' }}">
                {{ $s['title'] ?? 'Percakapan' }}
              </p>
              <span class="text-[11px] text-slate-400 whitespace-nowrap">
                {{ $s['time'] ?? '' }}
              </span>
            </div>

            <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
              {{ $s['snippet'] ?? '' }}
            </p>
          </div>
        </div>
      </a>
    @empty
      <div class="px-6 py-6">
        <p class="text-sm text-slate-500 dark:text-slate-400">
          Belum ada riwayat percakapan.
        </p>
      </div>
    @endforelse
  </div>
</aside>
