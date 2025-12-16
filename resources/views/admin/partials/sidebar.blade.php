{{-- resources/views/admin/partials/sidebar.blade.php --}}
@php
    use Illuminate\Support\Facades\Route;
    $route = Route::currentRouteName();
    $isDashboard = str_starts_with($route, 'admin.');
@endphp

<aside class="flex flex-col items-center justify-between w-16 bg-white border-r shadow overflow-visible">
    {{-- Top --}}
    <div class="mt-6 flex flex-col items-center gap-6">
        <div class="w-10 h-10 rounded-2xl bg-[#B8352E]/10 flex items-center justify-center text-xs font-semibold text-[#B8352E]">
            &lt;/&gt;
        </div>

        <nav class="flex flex-col items-center gap-4 mt-4">
            <a href="{{ route('admin.dashboard') }}"
               class="w-9 h-9 rounded-xl flex items-center justify-center text-lg
               {{ $isDashboard ? 'bg-[#B8352E] text-white' : 'text-slate-400 hover:bg-slate-100' }}">
                📚
            </a>
        </nav>
    </div>

    {{-- Bottom --}}
    <div class="mb-6 flex flex-col items-center gap-4 text-slate-400">
        <button class="w-9 h-9 rounded-xl hover:bg-slate-100">🌙</button>
        <div class="w-9 h-9 rounded-full bg-slate-300"></div>
    </div>
</aside>