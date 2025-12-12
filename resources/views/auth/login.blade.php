<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Masuk - EduChat</title>

    {{-- Google Fonts Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F5F7FA] min-h-screen flex flex-col">

    {{-- TOP BAR --}}
    <header class="w-full py-4 bg-white shadow-sm flex justify-center">
        <div class="text-xl font-semibold text-slate-900">&lt;/&gt;</div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex flex-1 items-center justify-center px-10">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center max-w-6xl w-full">

            {{-- LEFT ILLUSTRATION --}}
            <div class="flex justify-center">
                <img src="/images/robot.png" alt="Robot EduChat" class="w-[420px] h-auto">
                {{-- Ganti /images/robot.png sesuai lokasi ilustrasi kamu --}}
            </div>

            {{-- RIGHT LOGIN CARD --}}
            <div class="bg-white shadow-md rounded-2xl p-10 w-full max-w-md">

                <h1 class="text-xl font-semibold text-[#B8352E] mb-1">Masuk</h1>
                <p class="text-sm text-slate-500 mb-8">
                    Ayo masuk dan mulai jelajahi EduChat!
                </p>

                <form id="loginForm" class="space-y-5">

                    {{-- EMAIL --}}
                    <div>
                        <label class="text-sm font-medium text-slate-700">Email</label>
                        <input
                            type="email"
                            name="email"
                            required
                            placeholder="Masukkan Email Kampus"
                            class="mt-1 w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#B8352E] focus:outline-none"
                        >
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label class="text-sm font-medium text-slate-700">Kata Sandi</label>
                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="Masukkan Kata Sandi"
                            class="mt-1 w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-[#B8352E] focus:outline-none"
                        >
                    </div>

                    {{-- BUTTON --}}
                    <button
                        type="submit"
                        id="submitBtn"
                        class="w-full bg-[#B8352E] text-white py-3 rounded-xl text-sm font-medium hover:bg-[#8f251f] transition"
                    >
                        Masuk
                    </button>

                </form>

                <p class="mt-4 text-xs text-center text-slate-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-[#B8352E] font-medium">Buat Akun</a>
                </p>

            </div>
        </div>

    </main>

    {{-- PURE FRONTEND DEMO: BELUM BACKEND --}}
    <script>
        const form = document.getElementById('loginForm');
        const btn = document.getElementById('submitBtn');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            btn.disabled = true;
            btn.textContent = 'Masuk...';

            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 600);
        });
    </script>

</body>
</html>
