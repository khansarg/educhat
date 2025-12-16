{{-- resources/views/dosen/partials/sidebar.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;

    $route = Route::currentRouteName();
    // anggap route dosen dashboard bernama: dosen.dashboard
    $isHome = in_array($route, ['dosen.dashboard', 'dosen.course', 'dosen.clo']);
@endphp

<aside class="flex flex-col items-center justify-between w-16 bg-white dark:bg-slate-900
              border-r border-slate-100 dark:border-slate-800
              shadow-[0_10px_35px_rgba(15,23,42,0.08)]
              overflow-visible relative">

    {{-- TOP: logo --}}
    <div class="mt-6 flex flex-col items-center gap-6">
        <div class="w-10 h-10 rounded-2xl bg-[#B8352E]/10 flex items-center justify-center
                    text-xs font-semibold text-[#B8352E]">
            &lt;/&gt;
        </div>

        {{-- NAV --}}
        <nav class="flex flex-col items-center gap-4 mt-3">
            {{-- HOME --}}
            <a href="{{ route('dosen.dashboard') }}"
               class="w-11 h-11 rounded-none flex items-center justify-center
                      transition
                      {{ $isHome
                          ? 'bg-[#9D1535] text-white'
                          : 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span class="text-xl">🏠</span>
            </a>
        </nav>
    </div>

    {{-- BOTTOM: actions --}}
    <div class="mb-6 flex flex-col items-center gap-5 text-slate-400 dark:text-slate-300">

        {{-- Dark Mode Toggle --}}
        <button id="darkModeToggle"
                type="button"
                class="w-10 h-10 flex items-center justify-center
                       hover:bg-slate-100 dark:hover:bg-slate-800 transition">
            <span class="text-xl">☾</span>
        </button>

        {{-- Avatar (bisa popup kalau mau) --}}
        <div class="relative">
            {{-- overlay --}}
            <div id="profileOverlay"
                 class="fixed inset-0 bg-black/20 hidden z-40"></div>

            <button id="profileBtn"
                    type="button"
                    class="relative z-50 w-10 h-10 rounded-full bg-slate-300
                           overflow-hidden flex items-center justify-center
                           ring-0 hover:ring-4 hover:ring-slate-300/40 transition">
                <span class="text-sm font-semibold text-slate-700">AH</span>
            </button>

            {{-- Popup panel --}}
            <div id="profilePopup"
                 class="hidden z-50 absolute left-14 bottom-0 w-72
                        bg-white dark:bg-slate-900
                        border border-slate-200/70 dark:border-slate-800
                        rounded-2xl shadow-[0_18px_50px_rgba(15,23,42,0.22)]
                        p-4">

                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800
                                flex items-center justify-center overflow-hidden">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">AH</span>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 leading-tight">
                            Nama Dosen
                        </p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Dosen • EduChat
                        </p>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full text-sm px-3 py-2 rounded-xl
                                       border border-slate-200 dark:border-slate-700
                                       hover:bg-slate-50 dark:hover:bg-slate-800/60
                                       text-[#9D1535] font-semibold">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</aside>
