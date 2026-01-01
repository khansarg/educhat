@php
    use Illuminate\Support\Facades\Route;

    $route = Route::currentRouteName();
    $isLearning = in_array($route, ['dashboard', 'course.show', 'course.clo', 'chat.show']);
    $isHistory  = $route === 'history';
    
    // Ambil data user yang sedang login
    $user = auth()->user();
    // Ambil inisial dari nama (2 huruf pertama dari kata pertama)
    $nameParts = explode(' ', $user->name);
    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0], 1, 1)));
@endphp

<aside class="flex flex-col items-center justify-between w-16 bg-white dark:bg-slate-900 shadow-[0_10px_35px_rgba(15,23,42,0.10)] overflow-visible relative">
    {{-- Top logo + nav --}}
    <div class="mt-6 flex flex-col items-center gap-6">
        <div class="w-10 h-10 rounded-2xl bg-[#B8352E]/10 flex items-center justify-center text-xs font-semibold text-[#B8352E]">
            &lt;/&gt;
        </div>

        <nav class="flex flex-col items-center gap-4 mt-4">
            {{-- Learning Path (active untuk dashboard, course, chat) --}}
            <a href="{{ route('dashboard') }}"
               class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shadow-sm
                      {{ $isLearning ? 'bg-[#B8352E] text-white' : 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span class="text-base">📚</span>
            </a>

            {{-- History --}}
            <a href="{{ route('history') }}"
               class="w-9 h-9 rounded-xl flex items-center justify-center text-lg
                      {{ $isHistory ? 'bg-[#B8352E] text-white' : 'text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <span class="text-base">💬</span>
            </a>
        </nav>
    </div>

    {{-- Bottom icons: dark mode, language (optional), profile --}}
    <div class="mb-6 flex flex-col items-center gap-4 text-slate-400 dark:text-slate-300">

        <button id="darkModeToggle"
                class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
            <i class="fa-regular fa-moon"></i>
        </button>

        <div class="relative">
            <div id="profileOverlay" class="fixed inset-0 bg-black/20 hidden z-40"></div>

            <button id="profileBtn"
                    type="button"
                    class="relative z-50 w-9 h-9 rounded-2xl bg-[#B8352E] flex items-center justify-center overflow-hidden
                           ring-0 hover:ring-4 hover:ring-[#B8352E]/15 transition">
                <span class="text-xs font-semibold text-white">{{ $initials }}</span>
            </button>

            <div id="profilePopup"
                 class="hidden z-50 absolute left-12 bottom-0 w-72
                        bg-white dark:bg-slate-900
                        border border-slate-200/70 dark:border-slate-800
                        rounded-2xl shadow-[0_18px_50px_rgba(15,23,42,0.22)]
                        p-4">

                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center overflow-hidden">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $initials }}</span>
                    </div>

                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 leading-tight">
                            {{ $user->name }}
                        </p>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{ ucfirst($user->role) }} • EduChat
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
                                       text-[#B8352E] font-semibold">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</aside>


