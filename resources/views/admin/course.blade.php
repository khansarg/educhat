{{-- resources/views/admin/course.blade.php --}}
@extends('layouts.admin')

@section('title', 'Course Detail - Admin')

@section('content')

{{-- ================= COURSE HEADER ================= --}}
<div class="bg-white rounded-3xl border border-slate-200 p-8 mb-8"
     data-course-id="{{ $course->id }}">

    <h1 class="text-xl font-semibold mb-4">
        {{ $course->name }}
    </h1>

    <p class="text-sm font-semibold mb-2">Ringkasan Materi</p>

    <div id="courseDesc"
         contenteditable="true"
         class="rounded-2xl border p-4 text-sm text-slate-600">
        {{ $course->description ?? '' }}
    </div>

    <p id="courseDescStatus" class="text-xs text-slate-400 mt-2 hidden">
        Menyimpan...
    </p>
</div>

{{-- ================= DOSEN ================= --}}
{{-- <div class="bg-white rounded-3xl border border-slate-200 p-8 mb-8">

    <div class="flex justify-between items-center mb-4">
        <p class="text-sm font-semibold">Daftar Dosen</p>
        <button id="openAddDosen"
                class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm">
            + Tambah Dosen
        </button>
    </div>

    <table class="w-full text-sm border rounded-xl overflow-hidden">
        <thead class="bg-pink-100">
            <tr>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody id="dosenTableBody">
            @forelse($course->coordinators as $dosen)
                <tr class="border-t" data-user-id="{{ $dosen->id }}">
                    <td class="p-3">{{ $dosen->name }}</td>
                    <td class="p-3">{{ $dosen->email }}</td>
                    <td class="p-3 text-red-600 cursor-pointer js-remove-dosen">
                        Hapus
                    </td>
                </tr>
            @empty
                <tr class="js-empty-dosen">
                    <td colspan="3" class="p-3 text-slate-400">
                        Belum ada dosen
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div> --}}

{{-- ================= CLO + MATERI ================= --}}
<div class="bg-white rounded-3xl border border-slate-200 p-8">

    <div class="flex justify-between items-center mb-4">
        <p class="text-sm font-semibold">Pilih CLO</p>

        <button id="openAddClo"
                class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm">
            + Tambah CLO
        </button>
    </div>

    <div class="flex gap-3 mb-6 flex-wrap" id="cloButtons">
        @foreach($course->clos as $i => $clo)
            <button data-clo-id="{{ $clo->id }}"
                    type="button"
                    class="clo-btn px-6 py-2 rounded-full {{ $i === 0 ? 'bg-[#9D1535] text-white' : 'border' }}">
                {{ $clo->title ?? 'CLO '.($i+1) }}
            </button>
        @endforeach

        @if($course->clos->count() === 0)
            <span class="text-sm text-slate-400">Belum ada CLO</span>
        @endif
    </div>

    <p class="text-sm font-semibold mb-2">Ringkasan CLO</p>

    <div id="cloSummary"
         contenteditable="true"
         class="rounded-2xl border p-4 text-sm text-slate-600 mb-2">
        {{ optional($course->clos->first())->summary }}
    </div>

    <p id="cloSummaryStatus" class="text-xs text-slate-400 hidden">
        Menyimpan...
    </p>

    <div class="flex justify-between items-center mt-6 mb-3">
        <p class="text-sm font-semibold">Daftar Materi</p>

        <button id="openAddMateri"
                type="button"
                class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm">
            + Tambah Materi
        </button>
    </div>

    <div class="border rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50">
                <tr>
                    <th class="p-3 text-left">Judul</th>
                    <th class="p-3 text-left">File</th>
                    <th class="p-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody id="materiTableBody">
                {{-- dirender via JS supaya ikut CLO aktif --}}
                <tr>
                    <td colspan="3" class="p-3 text-slate-400">Pilih CLO untuk melihat materi.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

{{-- ================= MODALS ================= --}}

{{-- MODAL TAMBAH DOSEN
<div id="addDosenModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div id="addDosenOverlay" class="absolute inset-0 bg-black/40"></div>

    <div class="relative bg-white rounded-3xl w-full max-w-md p-6 z-10">
        <h2 class="text-lg font-semibold mb-4">Tambah Dosen</h2>

        <div class="space-y-4">
            <div class="relative">
                <label class="text-sm font-medium">Nama Dosen</label>
                <input id="dosenNameInput" type="text"
                       class="mt-1 w-full px-4 py-2 rounded-xl border"
                       placeholder="Ketik nama/email">

                <p class="text-xs text-slate-400 mt-1">
                    Ketik minimal 3 karakter untuk cari dosen.
                </p>

                <div id="dosenSuggest"
                     class="hidden absolute left-0 right-0 mt-2 bg-white border rounded-xl overflow-hidden z-20">
                </div>
            </div>

            <div>
                <label class="text-sm font-medium">Email Dosen</label>
                <input id="dosenEmailInput" type="email"
                       class="mt-1 w-full px-4 py-2 rounded-xl border"
                       placeholder="Terisi otomatis setelah pilih dosen">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button id="closeAddDosen" class="px-4 py-2 rounded-xl border text-sm">
                Batal
            </button>

            <button id="saveDosenBtn"
                    disabled
                    class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm opacity-70 cursor-not-allowed">
                Simpan
            </button>
        </div>
    </div>
</div> --}}

{{-- MODAL TAMBAH CLO --}}
<div id="addCloModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div id="addCloOverlay" class="absolute inset-0 bg-black/40"></div>

    <div class="relative bg-white rounded-3xl w-full max-w-md p-6 z-10">
        <h2 class="text-lg font-semibold mb-4">Tambah CLO</h2>

        <input id="cloTitleInput"
               type="text"
               class="w-full px-4 py-2 rounded-xl border"
               placeholder="Contoh: CLO 4">

        <div class="mt-6 flex justify-end gap-3">
            <button id="closeAddClo" class="px-4 py-2 rounded-xl border text-sm">
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
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
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

    const jsonFetch = async (url, options = {}) => {
        const res = await fetch(url, {
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                ...(options.headers || {}),
            },
            ...options,
        });

        const ct = res.headers.get('content-type') || '';
        const data = ct.includes('application/json')
            ? await res.json().catch(() => null)
            : await res.text();

        if (!res.ok) {
            const msg = data?.message || (typeof data === 'string' ? data : 'Request gagal');
            throw new Error(msg);
        }
        return data;
    };

    // =========================
    // 1) Autosave Course Summary
    // =========================
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
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ description: courseDescEl.innerText.trim() }),
                });
            } catch (e) {
                console.error(e);
                alert('Gagal menyimpan ringkasan course: ' + e.message);
            } finally {
                hide(courseDescStatus);
            }
        }, 700);
    });

    // =========================
    // 2) CLO Switching + Render Materi
    // =========================
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
              <tr class="border-t">
                <td class="p-3">${m.title ?? '-'}</td>
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

            // update data lokal agar UI langsung berubah
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

    // =========================
    // 3) Autosave CLO Summary
    // =========================
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
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ summary }),
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

    // =========================
    // 4) Modal Tambah CLO + POST
    // =========================
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
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title }),
            });

            closeCloModal();
            location.reload();
        } catch (e) {
            console.error(e);
            alert('Gagal tambah CLO: ' + e.message);
        }
    });

    // =========================
    // 5) Modal Tambah Dosen + Search + Assign
    // =========================
    // const addDosenModal = document.getElementById('addDosenModal');
    // const addDosenOverlay = document.getElementById('addDosenOverlay');
    // const openAddDosen = document.getElementById('openAddDosen');
    // const closeAddDosen = document.getElementById('closeAddDosen');

    // const dosenNameInput = document.getElementById('dosenNameInput');
    // const dosenEmailInput = document.getElementById('dosenEmailInput');
    // const dosenSuggest = document.getElementById('dosenSuggest');

    // const saveDosenBtn = document.getElementById('saveDosenBtn');
    // let selectedLecturer = null;

    // function openDosenModal() {
    //     addDosenModal?.classList.remove('hidden');
    //     addDosenModal?.classList.add('flex');
    //     dosenNameInput?.focus();
    // }
    // function closeDosenModal() {
    //     addDosenModal?.classList.add('hidden');
    //     addDosenModal?.classList.remove('flex');
    //     selectedLecturer = null;
    //     if (dosenNameInput) dosenNameInput.value = '';
    //     if (dosenEmailInput) dosenEmailInput.value = '';
    //     hide(dosenSuggest);
    //     dosenSuggest.innerHTML = '';
    //     toggleSaveDosen();
    // }

    // openAddDosen?.addEventListener('click', openDosenModal);
    // closeAddDosen?.addEventListener('click', closeDosenModal);
    // addDosenOverlay?.addEventListener('click', closeDosenModal);

    // function toggleSaveDosen() {
    //     const ok = !!selectedLecturer?.id;
    //     saveDosenBtn.disabled = !ok;
    //     saveDosenBtn.classList.toggle('opacity-70', !ok);
    //     saveDosenBtn.classList.toggle('cursor-not-allowed', !ok);
    // }
    // toggleSaveDosen();

    // let dosenSearchTimer = null;

    // async function renderSuggest(list) {
    //     if (!Array.isArray(list) || list.length === 0) {
    //         dosenSuggest.innerHTML = `<div class="px-4 py-3 text-sm text-slate-400">Dosen tidak ditemukan</div>`;
    //         show(dosenSuggest);
    //         return;
    //     }

    //     dosenSuggest.innerHTML = list.map(x => `
    //         <button type="button"
    //                 class="w-full text-left px-4 py-3 hover:bg-slate-50 border-b last:border-b-0"
    //                 data-id="${x.id}"
    //                 data-name="${x.name}"
    //                 data-email="${x.email}">
    //             <div class="text-sm font-medium">${x.name}</div>
    //             <div class="text-xs text-slate-500">${x.email}</div>
    //         </button>
    //     `).join('');

    //     show(dosenSuggest);
    // }

    // async function searchLecturers(q) {
    //     const data = await jsonFetch(`/admin/lecturers?search=${encodeURIComponent(q)}`, { method: 'GET' });
    //     await renderSuggest(data);
    // }

    // dosenNameInput?.addEventListener('input', () => {
    //     selectedLecturer = null;
    //     toggleSaveDosen();

    //     const q = dosenNameInput.value.trim();
    //     if (q.length < 3) {
    //         hide(dosenSuggest);
    //         dosenSuggest.innerHTML = '';
    //         return;
    //     }

    //     clearTimeout(dosenSearchTimer);
    //     dosenSearchTimer = setTimeout(async () => {
    //         try {
    //             await searchLecturers(q);
    //         } catch (e) {
    //             console.error(e);
    //         }
    //     }, 300);
    // });

    // dosenSuggest?.addEventListener('click', (e) => {
    //     const btn = e.target.closest('button[data-id]');
    //     if (!btn) return;

    //     selectedLecturer = {
    //         id: btn.dataset.id,
    //         name: btn.dataset.name,
    //         email: btn.dataset.email,
    //     };

    //     if (dosenNameInput) dosenNameInput.value = selectedLecturer.name;
    //     if (dosenEmailInput) dosenEmailInput.value = selectedLecturer.email;

    //     hide(dosenSuggest);
    //     dosenSuggest.innerHTML = '';

    //     toggleSaveDosen();
    // });

    // saveDosenBtn?.addEventListener('click', async () => {
    //     if (!selectedLecturer?.id) return;

    //     try {
    //         await jsonFetch(`/admin/coordinator`, {
    //             method: 'POST',
    //             headers: { 'Content-Type': 'application/json' },
    //             body: JSON.stringify({
    //                 course_id: courseId,
    //                 user_id: selectedLecturer.id,
    //             }),
    //         });

    //         const tbody = document.getElementById('dosenTableBody');
    //         if (tbody) {
    //             tbody.querySelector('.js-empty-dosen')?.remove();

    //             if (!tbody.querySelector(`tr[data-user-id="${selectedLecturer.id}"]`)) {
    //                 const tr = document.createElement('tr');
    //                 tr.className = 'border-t';
    //                 tr.dataset.userId = selectedLecturer.id;
    //                 tr.innerHTML = `
    //                     <td class="p-3">${selectedLecturer.name}</td>
    //                     <td class="p-3">${selectedLecturer.email}</td>
    //                     <td class="p-3 text-red-600 cursor-pointer js-remove-dosen">Hapus</td>
    //                 `;
    //                 tbody.appendChild(tr);
    //             }
    //         }

    //         closeDosenModal();
    //     } catch (e) {
    //         console.error(e);
    //         alert('Gagal tambah dosen: ' + e.message);
    //     }
    // });

    // document.getElementById('dosenTableBody')?.addEventListener('click', async (e) => {
    //     const target = e.target;
    //     if (!target.classList.contains('js-remove-dosen')) return;

    //     const row = target.closest('tr');
    //     const userId = row?.dataset.userId;
    //     if (!userId) return;

    //     if (!confirm('Hapus dosen dari course ini?')) return;

    //     try {
    //         await jsonFetch(`/admin/course/${courseId}/coordinator/${userId}`, {
    //             method: 'DELETE'
    //         });
    //         row.remove();

    //         const tbody = document.getElementById('dosenTableBody');
    //         if (tbody && tbody.querySelectorAll('tr').length === 0) {
    //             tbody.innerHTML = `<tr class="js-empty-dosen"><td colspan="3" class="p-3 text-slate-400">Belum ada dosen</td></tr>`;
    //         }
    //     } catch (err) {
    //         console.error(err);
    //         alert('Gagal hapus dosen: ' + err.message);
    //     }
    // });

    // =========================
    // 6) Tambah Materi => redirect ke page create materi (admin)
    // =========================
    document.getElementById('openAddMateri')?.addEventListener('click', () => {
        if (!activeCloId) {
            alert('Pilih CLO terlebih dulu.');
            return;
        }
        window.location.href = `/admin/course/${courseId}/clo/${activeCloId}/materi/create`;
    });

    // =========================
    // Global: ESC close modals
    // =========================
    document.addEventListener('keydown', (e) => {
        if (e.key !== 'Escape') return;
        closeCloModal();
        closeDosenModal();
    });
});
</script>
@endpush
