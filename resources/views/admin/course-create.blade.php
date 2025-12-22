@extends('layouts.admin')

@section('title', 'Tambah Course')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-3xl border p-8">
    <h1 class="text-lg font-semibold mb-6">Tambah Course</h1>

    <form method="POST" action="{{ route('admin.course.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="text-sm font-medium">Kode Course</label>
            <input name="code" class="w-full mt-1 px-4 py-2 border rounded-xl">
        </div>

        <div>
            <label class="text-sm font-medium">Nama Course</label>
            <input name="name" class="w-full mt-1 px-4 py-2 border rounded-xl">
        </div>

        <div>
            <label class="text-sm font-medium">Deskripsi</label>
            <textarea name="description"
                class="w-full mt-1 px-4 py-2 border rounded-xl"
                rows="3"></textarea>
        </div>

        <div class="flex justify-end">
            <button class="px-6 py-2 bg-[#B8352E] text-white rounded-xl">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
