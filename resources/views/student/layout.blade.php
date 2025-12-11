{{-- resources/views/student/layout.blade.php --}}
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
</head>
<body class="min-h-screen bg-[#F4F7FB] font-sans text-slate-900 dark:bg-slate-950 dark:text-slate-100">

<div class="flex h-screen">
    {{-- Sidebar ikon (kiri paling) --}}
    @include('student.partials.sidebar')

    {{-- Learning Path (sidebar tengah) --}}
    @include('student.partials.course-list')

    {{-- Main content --}}
    <main class="flex-1 overflow-y-auto px-10 py-10">
        @yield('content')
    </main>
</div>

</body>
</html>
