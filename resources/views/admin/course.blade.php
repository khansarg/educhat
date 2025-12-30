{{-- resources/views/admin/course.blade.php --}}
@extends('layouts.admin')

@section('title', 'Course Detail - Admin')

@section('content')

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8 mb-8"
     data-course-id="{{ $course->id }}">

    <h1 class="text-xl font-semibold mb-4 text-slate-900 dark:text-slate-50">
        {{ $course->name }}
    </h1>

    <p class="text-sm font-semibold mb-2 text-slate-900 dark:text-slate-100">Ringkasan Materi</p>

    <div id="courseDesc"
         contenteditable="true"
         class="rounded-2xl border border-slate-200 dark:border-slate-700 p-4 text-sm
                bg-white dark:bg-slate-950 text-slate-600 dark:text-slate-300">
        {{ $course->description ?? '' }}
    </div>

    <p id="courseDescStatus" class="text-xs text-slate-400 mt-2 hidden">
        Menyimpan...
    </p>
</div>

<div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-8">

    <div class="flex justify-between items-center mb-4">
        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Pilih CLO</p>

        <button id="openAddClo"
                class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm hover:opacity-95">
            + Tambah CLO
        </button>
    </div>

    <div class="flex gap-3 mb-6 flex-wrap" id="cloButtons">
        @foreach($course->clos as $i => $clo)
            <button data-clo-id="{{ $clo->id }}"
                    type="button"
                    class="clo-btn px-6 py-2 rounded-full
                           {{ $i === 0
                              ? 'bg-[#9D1535] text-white'
                              : 'border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60' }}">
                {{ $clo->title ?? 'CLO '.($i+1) }}
            </button>
        @endforeach

        @if($course->clos->count() === 0)
            <span class="text-sm text-slate-400">Belum ada CLO</span>
        @endif
    </div>

    <p class="text-sm font-semibold mb-2 text-slate-900 dark:text-slate-100">Ringkasan CLO</p>

    <div id="cloSummary"
         contenteditable="true"
         class="rounded-2xl border border-slate-200 dark:border-slate-700 p-4 text-sm
                bg-white dark:bg-slate-950 text-slate-600 dark:text-slate-300 mb-2">
        {{ optional($course->clos->first())->summary }}
    </div>

    <p id="cloSummaryStatus" class="text-xs text-slate-400 hidden">
        Menyimpan...
    </p>

    <div class="flex justify-between items-center mt-6 mb-3">
        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Daftar Materi</p>

        <button id="openAddMateri"
                type="button"
                class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm hover:opacity-95">
            + Tambah Materi
        </button>
    </div>

    <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-950/60">
                <tr>
                    <th class="p-3 text-left text-slate-700 dark:text-slate-200">Judul</th>
                    <th class="p-3 text-left text-slate-700 dark:text-slate-200">File</th>
                    <th class="p-3 text-left text-slate-700 dark:text-slate-200">Aksi</th>
                </tr>
            </thead>
            <tbody id="materiTableBody" class="bg-white dark:bg-slate-900">
                <tr>
                    <td colspan="3" class="p-3 text-slate-400">Pilih CLO untuk melihat materi.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="addCloModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div id="addCloOverlay" class="absolute inset-0 bg-black/40"></div>

    <div class="relative bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md p-6 z-10 border border-slate-200 dark:border-slate-800">
        <h2 class="text-lg font-semibold mb-4 text-slate-900 dark:text-slate-50">Tambah CLO</h2>

        <input id="cloTitleInput"
               type="text"
               class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700
                      bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100"
               placeholder="Contoh: CLO 4">

        <div class="mt-6 flex justify-end gap-3">
            <button id="closeAddClo" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-200">
                Batal
            </button>
            <button id="saveCloBtn"
                    disabled
                    class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm opacity-70 cursor-not-allowed">
                Simpan
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const courseId = document.querySelector('[data-course-id]')?.dataset.courseId;

    @php
      $closForJs = $course->clos
        ->sortBy('order')
        ->map(function($c){
          return [
            'id' => $c->id,
            'title' => $c->title,
            'summary' => $c->summary,
            'materials' => $c->materials
              ? $c->materials->map(function($m){
                  return [
                    'id' => $m->id,
                    'title' => $m->title,
                    'description' => $m->description,
                    'files' => $m->files
                      ? $m->files->map(fn($f) => [
                          'id' => $f->id,
                          'download_url' => $f->download_url,
                          'pdf_path' => $f->pdf_path,
                        ])->values()
                      : [],
                  ];
                })->values()
              : [],
          ];
        })->values();
    @endphp

    const clos = @json($closForJs);
    let activeCloId = clos?.[0]?.id ? String(clos[0].id) : null;

    function show(el) { el?.classList.remove('hidden'); }
    function hide(el) { el?.classList.add('hidden'); }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
      alert('CSRF token tidak ditemukan. Refresh halaman.');
      throw new Error('CSRF token missing');
    }

    const jsonFetch = async (url, options = {}) => {
      const res = await fetch(url, {
        method: options.method || 'POST',
        credentials: 'same-origin',
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          ...(options.headers || {}),
        },
        body: (options.body !== undefined) ? JSON.stringify(options.body) : undefined,
      });

      const ct = res.headers.get('content-type') || '';
      const data = ct.includes('application/json')
        ? await res.json().catch(() => null)
        : await res.text();

      if (!res.ok) {
        if (res.status === 419) alert('Session expired. Refresh halaman ya.');
        throw new Error((data && typeof data === 'object' && data.message) ? data.message : 'Request gagal');
      }
      return data;
    };

    // 1) Autosave Course Summary
    const courseDescEl = document.getElementById('courseDesc');
    const courseDescStatus = document.getElementById('courseDescStatus');
    let courseTimer = null;

    courseDescEl?.addEventListener('input', () => {
        clearTimeout(courseTimer);
        courseTimer = setTimeout(async () => {
            try {
                show(courseDescStatus);
                await jsonFetch(`/admin/course/${courseId}/summary`, {
                  method: 'PATCH',
                  body: { description: courseDescEl.innerText.trim() }
                });
            } catch (e) {
                console.error(e);
                alert('Gagal menyimpan ringkasan course: ' + e.message);
            } finally {
                hide(courseDescStatus);
            }
        }, 700);
    });

    // 2) CLO Switching + Render Materi
    const cloButtonsWrap = document.getElementById('cloButtons');
    const cloSummaryEl = document.getElementById('cloSummary');

    function renderMateriTable() {
        const tbody = document.getElementById('materiTableBody');
        if (!tbody) return;

        const cloData = clos.find(c => String(c.id) === String(activeCloId));
        const materials = cloData?.materials || [];

        if (!activeCloId) {
            tbody.innerHTML = `<tr><td colspan="3" class="p-3 text-slate-400">Pilih CLO untuk melihat materi.</td></tr>`;
            return;
        }

        if (materials.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="p-3 text-slate-400">Belum ada materi pada CLO ini.</td></tr>`;
            return;
        }

        tbody.innerHTML = materials.map(m => {
            const files = Array.isArray(m.files) ? m.files : [];
            const fileCount = files.length;
            const firstUrl = files[0]?.download_url || '';

            const fileCell = fileCount > 0
              ? `<a href="${firstUrl}" target="_blank" class="text-blue-600 hover:underline">
                    ${fileCount} file (lihat)
                 </a>`
              : `<span class="text-slate-400">Belum ada file</span>`;

            return `
              <tr class="border-t border-slate-200 dark:border-slate-800">
                <td class="p-3 text-slate-700 dark:text-slate-200">${m.title ?? '-'}</td>
                <td class="p-3">${fileCell}</td>
                <td class="p-3">
                  <div class="flex gap-3">
                    <a href="/admin/material/${m.id}/edit" class="text-blue-600 hover:underline">Edit</a>
                    <button type="button"
                            class="text-rose-600 hover:underline js-del-materi"
                            data-id="${m.id}">
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            `;
        }).join('');
    }

    function setActiveClo(cloId) {
        activeCloId = String(cloId);

        document.querySelectorAll('.clo-btn').forEach(b => {
            b.classList.remove('bg-[#9D1535]', 'text-white');
            b.classList.add('border');
        });

        const activeBtn = document.querySelector(`.clo-btn[data-clo-id="${activeCloId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('bg-[#9D1535]', 'text-white');
            activeBtn.classList.remove('border');
        }

        const cloData = clos.find(c => String(c.id) === activeCloId);
        if (cloSummaryEl) cloSummaryEl.innerText = cloData?.summary ?? '';

        renderMateriTable();
    }

    cloButtonsWrap?.addEventListener('click', (e) => {
        const btn = e.target.closest('.clo-btn');
        if (!btn) return;
        setActiveClo(btn.dataset.cloId);
    });

    if (activeCloId) setActiveClo(activeCloId);

    // Delete materi
    document.getElementById('materiTableBody')?.addEventListener('click', async (e) => {
        const btn = e.target.closest('.js-del-materi');
        if (!btn) return;

        const materialId = btn.dataset.id;
        if (!materialId) return;

        if (!confirm('Hapus materi ini?')) return;

        try {
            await jsonFetch(`/admin/material/${materialId}`, { method: 'DELETE' });

            const cloData = clos.find(c => String(c.id) === String(activeCloId));
            if (cloData?.materials) {
                cloData.materials = cloData.materials.filter(m => String(m.id) !== String(materialId));
            }
            renderMateriTable();

        } catch (err) {
            console.error(err);
            alert('Gagal hapus materi: ' + err.message);
        }
    });

    // 3) Autosave CLO Summary
    const cloSummaryStatus = document.getElementById('cloSummaryStatus');
    let cloTimer = null;

    cloSummaryEl?.addEventListener('input', () => {
        clearTimeout(cloTimer);
        cloTimer = setTimeout(async () => {
            if (!activeCloId) return;

            const summary = cloSummaryEl.innerText.trim();
            try {
                show(cloSummaryStatus);

                await jsonFetch(`/admin/clo/${activeCloId}/summary`, {
                  method: 'PATCH',
                  body: { summary }
                });

                const idx = clos.findIndex(c => String(c.id) === String(activeCloId));
                if (idx !== -1) clos[idx].summary = summary;

            } catch (e) {
                console.error(e);
                alert('Gagal menyimpan ringkasan CLO: ' + e.message);
            } finally {
                hide(cloSummaryStatus);
            }
        }, 700);
    });

    // 4) Modal Tambah CLO
    const addCloModal = document.getElementById('addCloModal');
    const addCloOverlay = document.getElementById('addCloOverlay');
    const openAddClo = document.getElementById('openAddClo');
    const closeAddClo = document.getElementById('closeAddClo');
    const cloTitleInput = document.getElementById('cloTitleInput');
    const saveCloBtn = document.getElementById('saveCloBtn');

    function openCloModal() {
        addCloModal?.classList.remove('hidden');
        addCloModal?.classList.add('flex');
        cloTitleInput?.focus();
    }
    function closeCloModal() {
        addCloModal?.classList.add('hidden');
        addCloModal?.classList.remove('flex');
        if (cloTitleInput) cloTitleInput.value = '';
        toggleSaveClo();
    }

    openAddClo?.addEventListener('click', openCloModal);
    closeAddClo?.addEventListener('click', closeCloModal);
    addCloOverlay?.addEventListener('click', closeCloModal);

    function toggleSaveClo() {
        const ok = (cloTitleInput?.value || '').trim().length > 0;
        if (!saveCloBtn) return;
        saveCloBtn.disabled = !ok;
        saveCloBtn.classList.toggle('opacity-70', !ok);
        saveCloBtn.classList.toggle('cursor-not-allowed', !ok);
    }
    cloTitleInput?.addEventListener('input', toggleSaveClo);
    toggleSaveClo();

    saveCloBtn?.addEventListener('click', async () => {
        const title = (cloTitleInput?.value || '').trim();
        if (!title) return;

        try {
            await jsonFetch(`/admin/course/${courseId}/clos`, {
              method: 'POST',
              body: { title }
            });

            closeCloModal();
            location.reload();
        } catch (e) {
            console.error(e);
            alert('Gagal tambah CLO: ' + e.message);
        }
    });

    // 6) Tambah Materi => redirect
    document.getElementById('openAddMateri')?.addEventListener('click', () => {
        if (!activeCloId) {
            alert('Pilih CLO terlebih dulu.');
            return;
        }
        window.location.href = `/admin/course/${courseId}/clo/${activeCloId}/materi/create`;
    });

    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        closeCloModal();
    });
});
</script>
@endpush
