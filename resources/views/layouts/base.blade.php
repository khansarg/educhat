{{-- resources/views/layouts/base.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'EduChat')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Extra head (optional, per page) --}}
    @stack('head')
</head>

<body
    class="min-h-screen font-sans bg-[#F4F7FB] text-slate-900
           dark:bg-slate-950 dark:text-slate-100">

    {{-- GLOBAL APP WRAPPER --}}
    <div id="app" class="min-h-screen">

        {{-- CONTENT FROM CHILD LAYOUT --}}
        @yield('body')

    </div>

    {{-- GLOBAL SCRIPTS --}}
    @stack('scripts')
</body>
</html>
