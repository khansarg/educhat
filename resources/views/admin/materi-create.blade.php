{{-- resources/views/admin/materi-create.blade.php --}}
@extends('layouts.admin')

@section('title', ($material ?? false) ? 'Edit Materi - Admin' : 'Tambah Materi - Admin')

@section('content')
@php
  $isEdit = isset($material) && $material;

  $existingFilesPhp = [];
  if ($isEdit) {
    $existingFilesPhp = $material->files->map(function ($f) {
      return [
        'id' => $f->id,
        'name' => $f->original_name ?? basename($f->pdf_path ?? 'file.pdf'),
        'download_url' => $f->download_url,
      ];
    })->values()->all();
  }
@endphp

<div class="bg-white dark:bg-slate-900 rounded-[28px] border border-slate-200 dark:border-slate-800 p-10"
     data-course-id="{{ $course->id }}"
     data-clo-id="{{ $clo->id }}"
     data-material-id="{{ $isEdit ? $material->id : '' }}">

  <div class="flex items-start justify-between gap-6 mb-10">
    <div>
      <h1 class="text-3xl font-semibold text-slate-900 dark:text-slate-50">
        {{ $isEdit ? 'Edit Materi' : 'Tambah Materi' }}
      </h1>

      <div class="mt-4 flex items-center gap-2">
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-200">
          {{ $course->name }}
        </span>
        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-500/15 dark:text-rose-200">
          {{ $clo->title ?? ('CLO '.$clo->order) }}
        </span>
      </div>

      <p id="saveStatusMateri" class="mt-3 text-sm text-slate-500 dark:text-slate-400 hidden">Menyimpan...</p>
    </div>
  </div>

  <div class="space-y-8">
    <div class="max-w-2xl">
      <label class="text-sm font-semibold text-slate-900 dark:text-slate-100">Judul Materi</label>
      <input
        id="materiTitleInputMateri"
        type="text"
        value="{{ $isEdit ? $material->title : '' }}"
        class="mt-2 w-full px-4 py-3 rounded-2xl border border-slate-200 dark:border-slate-700
               bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100
               focus:outline-none focus:ring-2 focus:ring-[#9D1535]/20"
        placeholder="Masukkan judul materi..." />
      <p id="titleErrorMateri" class="mt-2 text-sm text-rose-600 hidden">
        Judul materi wajib diisi.
      </p>
    </div>

    <div>
      <label class="text-sm font-semibold text-slate-900 dark:text-slate-100">Upload File (PDF)</label>

      <div class="mt-3 bg-slate-50 dark:bg-slate-950/50 rounded-3xl border border-slate-200 dark:border-slate-800 p-6">
        <div id="fileGridMateri" class="grid grid-cols-6 gap-6"></div>

        <p id="emptyHintMateri" class="text-sm text-slate-400">
          Belum ada file. Upload dulu ya.
        </p>
        <p id="fileErrorMateri" class="text-sm text-rose-600 hidden mt-2">
          Minimal upload 1 file PDF.
        </p>
      </div>

      <div
        id="dropzoneMateri"
        class="mt-6 rounded-3xl border-2 border-dashed border-slate-300 dark:border-slate-700
               bg-white dark:bg-slate-950 p-10 text-center cursor-pointer
               hover:bg-slate-50 dark:hover:bg-slate-900/60 transition">
        <div class="flex flex-col items-center gap-3">
          <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">📄</div>
          <p class="text-sm text-slate-500 dark:text-slate-300">Upload File disini</p>
          <p class="text-xs text-slate-400">Klik atau drag & drop (PDF)</p>
        </div>

        <input id="fileInputMateri" type="file" class="hidden" accept=".pdf,application/pdf" multiple />
      </div>
    </div>

    <div class="pt-8 flex items-center justify-center gap-8">
      <button
        id="saveBtnMateri"
        type="button"
        class="w-[420px] py-4 rounded-full bg-[#9D1535] text-white font-semibold shadow hover:opacity-95">
        Save and Display
      </button>

      <a
        href="{{ route('admin.course.show', $course->id) }}"
        class="w-[420px] py-4 rounded-full bg-slate-300 dark:bg-slate-700 text-white font-semibold text-center hover:bg-slate-400 dark:hover:bg-slate-600">
        Back
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
  const root = document.querySelector('[data-course-id][data-clo-id]');
  const courseId = root?.dataset.courseId;
  const cloId = root?.dataset.cloId;
  const materialId = (root?.dataset.materialId || '').trim();

  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

  const dropzone = document.getElementById("dropzoneMateri");
  const fileInput = document.getElementById("fileInputMateri");
  const fileGrid = document.getElementById("fileGridMateri");
  const emptyHint = document.getElementById("emptyHintMateri");

  const saveBtn = document.getElementById("saveBtnMateri");
  const saveStatus = document.getElementById("saveStatusMateri");

  const titleInput = document.getElementById("materiTitleInputMateri");
  const titleError = document.getElementById("titleErrorMateri");
  const fileError = document.getElementById("fileErrorMateri");

  if (!root || !courseId || !cloId || !csrf) return;

  const show = (el) => el?.classList.remove('hidden');
  const hide = (el) => el?.classList.add('hidden');

  const setSaving = (saving) => {
    if (!saveBtn) return;
    saveBtn.disabled = saving;
    saveBtn.classList.toggle('opacity-70', saving);
    saveBtn.classList.toggle('cursor-not-allowed', saving);
    saving ? show(saveStatus) : hide(saveStatus);
  };

  const existingFiles = @json($existingFilesPhp);
  const newFiles = [];

  async function safeJsonFetch(url, options = {}) {
    const res = await fetch(url, {
      method: options.method || 'GET',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        ...(options.headers || {}),
      },
      body: options.body,
    });

    const ct = res.headers.get('content-type') || '';
    const data = ct.includes('application/json')
      ? await res.json().catch(() => null)
      : await res.text();

    if (!res.ok) {
      const msg =
        (data && typeof data === 'object' && data.message) ? data.message :
        (typeof data === 'string' ? data : 'Request gagal');
      throw new Error(msg);
    }

    return data;
  }

  async function uploadPdf(mid, file) {
    const form = new FormData();
    form.append('file', file);

    const res = await fetch(`/admin/material/${mid}/upload`, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'X-CSRF-TOKEN': csrf,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      },
      body: form
    });

    const ct = res.headers.get('content-type') || '';
    const data = ct.includes('application/json')
      ? await res.json().catch(() => null)
      : await res.text();

    if (!res.ok) {
      const msg =
        (data && typeof data === 'object' && data.message) ? data.message :
        (typeof data === 'string' ? data : 'Upload gagal');
      throw new Error(msg);
    }
    return data;
  }

  function render() {
    if (!fileGrid) return;
    fileGrid.innerHTML = '';

    const total = existingFiles.length + newFiles.length;
    if (total === 0) emptyHint?.classList.remove('hidden');
    else emptyHint?.classList.add('hidden');

    existingFiles.forEach((f) => {
      const item = document.createElement('div');
      item.className = "flex flex-col items-center text-center gap-2";

      item.innerHTML = `
        <div class="w-12 h-12 flex items-center justify-center"><span class="text-4xl">📕</span></div>
        <a href="${f.download_url}" target="_blank"
           class="text-xs font-semibold text-blue-600 hover:underline truncate w-full"
           title="${f.name}">
          ${f.name}
        </a>
        <button type="button" class="text-xs text-rose-600 hover:underline" data-remove-existing="${f.id}">
          Hapus
        </button>
      `;
      fileGrid.appendChild(item);
    });

    newFiles.forEach((file, idx) => {
      const item = document.createElement('div');
      item.className = "flex flex-col items-center text-center gap-2";

      item.innerHTML = `
        <div class="w-12 h-12 flex items-center justify-center"><span class="text-4xl">📕</span></div>
        <p class="text-xs font-semibold text-slate-900 dark:text-slate-100 truncate w-full" title="${file.name}">
          ${file.name}
        </p>
        <button type="button" class="text-xs text-rose-600 hover:underline" data-remove-new="${idx}">
          Hapus
        </button>
      `;
      fileGrid.appendChild(item);
    });

    fileGrid.querySelectorAll('[data-remove-new]').forEach(btn => {
      btn.addEventListener('click', () => {
        const i = parseInt(btn.getAttribute('data-remove-new'), 10);
        newFiles.splice(i, 1);
        render();
      });
    });

    fileGrid.querySelectorAll('[data-remove-existing]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const fileId = btn.getAttribute('data-remove-existing');
        if (!fileId) return;
        if (!confirm('Hapus file ini?')) return;

        try {
          await safeJsonFetch(`/admin/material-file/${fileId}`, { method: 'DELETE' });
          const idx = existingFiles.findIndex(x => String(x.id) === String(fileId));
          if (idx !== -1) existingFiles.splice(idx, 1);
          render();
        } catch (e) {
          console.error(e);
          alert('Gagal hapus file: ' + e.message);
        }
      });
    });
  }

  function addFiles(fileList) {
    const incoming = Array.from(fileList || []);
    const pdfs = incoming.filter(f => f.type === "application/pdf" || f.name.toLowerCase().endsWith(".pdf"));
    if (!pdfs.length) return;
    pdfs.forEach(f => newFiles.push(f));
    render();
  }

  dropzone?.addEventListener("click", () => fileInput?.click());
  fileInput?.addEventListener("change", (e) => {
    addFiles(e.target.files);
    fileInput.value = "";
  });

  dropzone?.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropzone.classList.add("ring-4", "ring-[#9D1535]/10");
  });

  dropzone?.addEventListener("dragleave", () => {
    dropzone.classList.remove("ring-4", "ring-[#9D1535]/10");
  });

  dropzone?.addEventListener("drop", (e) => {
    e.preventDefault();
    dropzone.classList.remove("ring-4", "ring-[#9D1535]/10");
    addFiles(e.dataTransfer.files);
  });

  saveBtn?.addEventListener('click', async () => {
    hide(titleError);
    hide(fileError);

    const title = (titleInput?.value || '').trim();
    if (!title) { show(titleError); return; }

    if (!materialId && (existingFiles.length + newFiles.length === 0)) {
      show(fileError);
      return;
    }

    setSaving(true);

    try {
      let mid = materialId;

      if (!mid) {
        const created = await safeJsonFetch(`/admin/clo/${cloId}/materials`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ title, description: '' })
        });

        mid = created?.material?.id;
        if (!mid) throw new Error('Material ID tidak ditemukan dari response backend.');
      } else {
        await safeJsonFetch(`/admin/material/${mid}`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ title, description: '' })
        });
      }

      for (const f of newFiles) {
        await uploadPdf(mid, f);
      }

      window.location.href = `/admin/course/${courseId}`;

    } catch (e) {
      console.error(e);
      alert('Gagal menyimpan: ' + e.message);
    } finally {
      setSaving(false);
    }
  });

  render();
});
</script>
@endpush
