<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Akun - EduChat</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #F5F9FF;
        }
        .topbar {
            width: 100%;
            padding: 20px 0;
            display: flex;
            justify-content: center;
            font-size: 24px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    {{-- Top Bar --}}
    <header class="topbar">
        <div class="text-xl font-semibold">&lt;/&gt;</div>
    </header>

    <main class="w-full flex justify-center px-10 py-6">
        <div class="flex w-full max-w-7xl items-center justify-between">

            {{-- LEFT: Robot Illustration --}}
            <div class="flex-1 flex justify-center">
                <img src="{{ asset('images/robot.png') }}" alt="Robot" class="w-[420px] h-auto">
            </div>

            {{-- RIGHT: Signup Card --}}
            <div class="flex-1 flex justify-center">
                <div class="bg-white rounded-3xl shadow-md p-10 w-[420px] border border-slate-200">

                    <h1 class="text-xl font-semibold text-[#B8352E] mb-1">Buat Akun</h1>
                    <p class="text-sm text-slate-700 mb-6">
                        Buat akun untuk dapat mengakses EduChat
                    </p>

                    <form id="registerForm">

                        {{-- NAMA --}}
                        <div class="mb-4">
                            <label class="text-sm font-medium">Nama</label>
                            <input
                                type="text"
                                placeholder="Masukkan Nama Lengkap"
                                class="w-full mt-1 px-4 py-2 rounded-xl border border-slate-300 focus:ring-[#B8352E] focus:border-[#B8352E]"
                                required
                            >
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-4">
                            <label class="text-sm font-medium">Email</label>
                            <input
                                type="email"
                                placeholder="Masukkan Email Kampus"
                                class="w-full mt-1 px-4 py-2 rounded-xl border border-slate-300 focus:ring-[#B8352E] focus:border-[#B8352E]"
                                required
                            >
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-4">
                            <label class="text-sm font-medium">Kata Sandi</label>
                            <input
                                type="password"
                                placeholder="Masukkan Kata Sandi"
                                class="w-full mt-1 px-4 py-2 rounded-xl border border-slate-300 focus:ring-[#B8352E] focus:border-[#B8352E]"
                                required
                            >
                        </div>

                        {{-- CONFIRM PASSWORD --}}
                        <div class="mb-6">
                            <label class="text-sm font-medium">Konfirmasi Kata Sandi</label>
                            <input
                                type="password"
                                placeholder="Masukkan Konfirmasi Kata Sandi"
                                class="w-full mt-1 px-4 py-2 rounded-xl border border-slate-300 focus:ring-[#B8352E] focus:border-[#B8352E]"
                                required
                            >
                        </div>

                        {{-- SUBMIT BUTTON --}}
                        <button
                            type="submit"
                            class="w-[140px] mx-auto block bg-[#B8352E] text-white py-2 rounded-xl hover:bg-[#982c27] transition">
                            Buat akun
                        </button>

                    </form>

                    {{-- Already have account --}}
                    <p class="mt-5 text-center text-sm text-slate-700">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-[#B8352E] font-medium">Masuk</a>
                    </p>

                </div>
            </div>
        </div>
    </main>

</body>
</html>