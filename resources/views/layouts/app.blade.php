<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Saya</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <meta name="csrf-token"
      content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">

<div class="min-h-screen p-3 md:p-5">

    <!-- MOBILE HEADER -->
    <div class="lg:hidden mb-4">

        <div class="bg-white rounded-2xl shadow p-4 flex justify-between items-center">

            <h1 class="font-bold text-xl">
                Glowver POS
            </h1>

            <button id="menuBtn">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>

        </div>

    </div>

    <div class="flex gap-5">

        <!-- SIDEBAR -->
        @include('layouts.aside')

        <!-- CONTENT -->
        <main class="flex-1">

            <!-- TOPBAR -->
            @include('layouts.navbar')


            @yield('content')

        </main>

    </div>

</div>

<script>
const sidebar = document.getElementById('sidebar');
const menuBtn = document.getElementById('menuBtn');
const closeBtn = document.getElementById('closeBtn');

menuBtn?.addEventListener('click', () => {
    sidebar.classList.remove('left-[-300px]');
    sidebar.classList.add('left-0');
});

closeBtn?.addEventListener('click', () => {
    sidebar.classList.remove('left-0');
    sidebar.classList.add('left-[-300px]');
});
</script>

</body>
</html>