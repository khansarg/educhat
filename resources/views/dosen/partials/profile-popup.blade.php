{{-- overlay --}}
<div id="profileOverlay" class="fixed inset-0 bg-black/30 hidden z-40"></div>

{{-- popup card --}}
<div id="profilePopup"
     class="fixed left-20 bottom-8 w-[280px] bg-white dark:bg-slate-900 rounded-2xl shadow-[0_20px_50px_rgba(15,23,42,0.25)]
            p-5 hidden z-50 border border-slate-100 dark:border-slate-800">
    <div class="flex items-center gap-3">
        <div class="w-12 h-12 rounded-full bg-slate-200 dark:bg-slate-700"></div>
        <div>
            <p class="text-sm font-semibold">Nama Dosen</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Dosen · Strategi Algoritma</p>
        </div>
    </div>

    <div class="mt-4">
        <button class="w-full flex items-center justify-center gap-2 rounded-xl border border-slate-200 dark:border-slate-700
                       py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">
            <span class="text-[#B8352E]">⎋</span> Logout
        </button>
    </div>
</div>
