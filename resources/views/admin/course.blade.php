{{-- resources/views/admin/course.blade.php --}}
@extends('layouts.admin')

@section('title', 'Course Detail - Admin')

@section('content')

{{-- COURSE HEADER --}}
<div class="bg-white rounded-3xl border border-slate-200 p-8 mb-8">
    <h1 class="text-xl font-semibold mb-4">Strategi Algoritma</h1>

    <p class="text-sm font-semibold mb-2">Ringkasan Materi</p>
    <div class="rounded-2xl border p-4 text-sm text-slate-600">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit...
    </div>
</div>

{{-- DOSEN --}}
<div class="bg-white rounded-3xl border border-slate-200 p-8 mb-8">

    <div class="flex justify-between items-center mb-4">
        <p class="text-sm font-semibold">Daftar Dosen</p>
        <button class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm">
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

    <div class="flex gap-3 mb-6">
        <button class="px-6 py-2 rounded-full bg-[#9D1535] text-white">CLO 1</button>
        <button class="px-6 py-2 rounded-full border">CLO 2</button>
        <button class="px-6 py-2 rounded-full border">CLO 3</button>
    </div>

    <p class="text-sm font-semibold mb-2">Ringkasan Materi</p>
    <div class="rounded-2xl border p-4 text-sm text-slate-600 mb-6">
        Lorem ipsum ringkasan CLO...
    </div>

    <div class="flex justify-between items-center">
        <p class="text-sm font-semibold">Daftar Materi</p>
        <button class="px-4 py-2 rounded-xl bg-[#9D1535] text-white text-sm">
            + Tambah Materi
        </button>
    </div>

</div>

@endsection