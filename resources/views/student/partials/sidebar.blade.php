{{-- resources/views/student/partials/sidebar.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;
    $route = Route::currentRouteName();
    $isLearning = in_array($route, ['dashboard', 'course.show', 'course.clo', 'chat.show']);
    $isHistory  = $route === 'history';
@endphp

<aside class="flex flex-col items-center justify-between w-16 bg-white dark:bg-slate-900 shadow-[0_10px_35px_rgba(15,23,42,0.10)]">
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

    {{-- Bottom icons: dark mode, language, profile --}}
    <div class="mb-6 flex flex-col items-center gap-4 text-slate-400 dark:text-slate-300">
        <button id="darkModeToggle"
                class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
            <span class="text-lg">🌙</span>
        </button>

        <button class="w-9 h-9 rounded-xl flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800">
            <span class="text-lg">🌐</span>
        </button>

        <div class="w-9 h-9 rounded-2xl bg-[#B8352E] flex items-center justify-center overflow-hidden">
            <span class="text-xs font-semibold text-white">AH</span>
        </div>
    </div>
</aside>
