{{-- resources/views/layouts/student.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'EduChat')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="h-screen overflow-hidden bg-[#F4F7FB] font-sans text-slate-900 dark:bg-slate-950 dark:text-slate-100">

<div class="flex h-full">
    {{-- Sidebar ikon (kiri paling) --}}
    @include('student.partials.sidebar')

    {{-- Sidebar tengah (default: course-list, bisa diganti di halaman tertentu) --}}
    @hasSection('middle_sidebar')
        @yield('middle_sidebar')
    @else
        {{-- Fallback: tampilkan course-list jika ada data $courses --}}
        @if(isset($courses))
            @include('student.partials.course-list', ['courses' => $courses])
        @endif
    @endif

    {{-- Main content --}}
    <main class="flex-1 flex flex-col overflow-hidden px-10 py-10">
        @yield('content')
    </main>
</div>

{{-- Scripts --}}
<script>
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const html = document.documentElement;

    // Load saved theme
    if (localStorage.getItem('theme') === 'dark') {
        html.classList.add('dark');
    }

    darkModeToggle?.addEventListener('click', () => {
        html.classList.toggle('dark');
        localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    });

    // Profile popup
    const profileBtn = document.getElementById('profileBtn');
    const profilePopup = document.getElementById('profilePopup');
    const profileOverlay = document.getElementById('profileOverlay');

    profileBtn?.addEventListener('click', () => {
        profilePopup.classList.toggle('hidden');
        profileOverlay.classList.toggle('hidden');
    });

    profileOverlay?.addEventListener('click', () => {
        profilePopup.classList.add('hidden');
        profileOverlay.classList.add('hidden');
    });

    // Info panel toggle (untuk chat page)
    const infoToggleBtn = document.getElementById('infoToggleBtn');
    const cloInfoPanel = document.getElementById('cloInfoPanel');

    infoToggleBtn?.addEventListener('click', () => {
        cloInfoPanel.classList.toggle('hidden');
    });
</script>
@stack('scripts')  <!-- Pastikan ini ada di bawah, tepat sebelum penutupan </body> -->

</body>
</html>