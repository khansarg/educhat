{{-- resources/views/admin/course.blade.php --}}
@extends('layouts.admin')

@section('title', 'Course Detail - Admin')

@section('content')

{{-- COURSE HEADER --}}
<div class="bg-white rounded-3xl border border-slate-200 p-8 mb-8">
    <h1 class="text-xl font-semibold mb-4">Strategi Algoritma</h1>

    <p class="text-sm font-semibold mb-2">Ringkasan Materi</p>
    <div contenteditable="true" class="rounded-2xl border p-4 text-sm text-slate-600">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit...
    </div>
</div>

{{-- DOSEN --}}
<div class="bg-white rounded-3xl border border-slate-200 p-8 mb-8">

    <div class="flex justify-between items-center mb-4">
        <p class="text-sm font-semibold">Daftar Dosen</p>
        <button
            id="openAddDosen"
            class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm">
            + Tambah Dosen
        </button>
    </div>

    <table class="w-full text-sm border rounded-xl overflow-hidden">
        <thead class="bg-pink-100">
            <tr>
                <th class="p-3 text-left">Nama</th>
                <th class="p-3 text-left">Email Dosen</th>
                <th class="p-3 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-t">
                <td class="p-3">Nama Dosen</td>
                <td class="p-3">aaa@email.com</td>
                <td class="p-3 text-red-600 cursor-pointer">Hapus</td>
            </tr>
        </tbody>
    </table>

</div>

{{-- CLO --}}
<div class="bg-white rounded-3xl border border-slate-200 p-8">

    <p class="text-sm font-semibold mb-4">Pilih CLO</p>

   <div class="flex gap-3 mb-6" id="cloButtons">
        <button data-clo="1" class="clo-btn px-6 py-2 rounded-full bg-[#9D1535] text-white">CLO 1</button>
        <button data-clo="2" class="clo-btn px-6 py-2 rounded-full border">CLO 2</button>
        <button data-clo="3" class="clo-btn px-6 py-2 rounded-full border">CLO 3</button>
    </div>

    <p class="text-sm font-semibold mb-2">Ringkasan Materi</p>
    <div contenteditable="true" class="rounded-2xl border p-4 text-sm text-slate-600 mb-6">
        Lorem ipsum ringkasan CLO...
    </div>

    <div class="flex justify-between items-center">
        <p class="text-sm font-semibold">Daftar Materi</p>
        <button
            id="openAddMateri"
            class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm">
            + Tambah Materi
        </button>

    </div>

</div>

{{-- MODAL TAMBAH DOSEN --}}
<div id="addDosenModal"
     class="fixed inset-0 z-50 hidden items-center justify-center">

    {{-- Overlay --}}
    <div id="addDosenOverlay"
         class="absolute inset-0 bg-black/40"></div>

    {{-- Modal --}}
    <div class="relative bg-white rounded-3xl w-full max-w-md p-6 z-10">

        <h2 class="text-lg font-semibold mb-4">
            Tambah Dosen
        </h2>

        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium">Nama Dosen</label>
                <input type="text"
                       class="mt-1 w-full px-4 py-2 rounded-xl border">
            </div>

            <div>
                <label class="text-sm font-medium">Email Dosen</label>
                <input type="email"
                       class="mt-1 w-full px-4 py-2 rounded-xl border">
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button id="closeAddDosen"
                    class="px-4 py-2 rounded-xl border text-sm">
                Batal
            </button>

            <button
                class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm opacity-70 cursor-not-allowed">
                Simpan
            </button>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH MATERI --}}
<div id="addMateriModal"
     class="fixed inset-0 z-50 hidden items-center justify-center">

    <div id="addMateriOverlay"
         class="absolute inset-0 bg-black/40"></div>

    <div class="relative bg-white rounded-3xl w-full max-w-md p-6 z-10">
        <h2 class="text-lg font-semibold mb-4">
            Tambah Materi
        </h2>

        <div class="space-y-4">
            <div>
                <label class="text-sm font-medium">Judul Materi</label>
                <input type="text"
                       class="mt-1 w-full px-4 py-2 rounded-xl border">
            </div>

            <div>
                <label class="text-sm font-medium">Deskripsi</label>
                <textarea
                    class="mt-1 w-full px-4 py-2 rounded-xl border"
                    rows="3"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button id="closeAddMateri"
                    class="px-4 py-2 rounded-xl border text-sm">
                Batal
            </button>

            <button
                class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm opacity-70 cursor-not-allowed">
                Simpan
            </button>
        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const openBtn  = document.getElementById("openAddDosen");
    const modal    = document.getElementById("addDosenModal");
    const overlay  = document.getElementById("addDosenOverlay");
    const closeBtn = document.getElementById("closeAddDosen");

    if (!openBtn || !modal || !overlay || !closeBtn) return;

    const openModal = () => {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    const closeModal = () => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    };

    openBtn.addEventListener("click", openModal);
    closeBtn.addEventListener("click", closeModal);
    overlay.addEventListener("click", closeModal);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeModal();
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const cloButtons = document.querySelectorAll(".clo-btn");

    cloButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            // reset semua
            cloButtons.forEach(b => {
                b.classList.remove("bg-[#9D1535]", "text-white");
                b.classList.add("border");
            });

            // aktifkan yang diklik
            btn.classList.add("bg-[#9D1535]", "text-white");
            btn.classList.remove("border");

            console.log("CLO aktif:", btn.dataset.clo);
        });
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const openBtn  = document.getElementById("openAddMateri");
    const modal    = document.getElementById("addMateriModal");
    const overlay  = document.getElementById("addMateriOverlay");
    const closeBtn = document.getElementById("closeAddMateri");

    if (!openBtn || !modal || !overlay || !closeBtn) return;

    const openModal = () => {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    const closeModal = () => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    };

    openBtn.addEventListener("click", openModal);
    closeBtn.addEventListener("click", closeModal);
    overlay.addEventListener("click", closeModal);

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeModal();
    });
});
</script>

@endpush

@endsection