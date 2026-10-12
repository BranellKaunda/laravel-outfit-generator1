@props([
  'title' => null,
])

@php $title ??= config('app.name', 'Laravel'); @endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    @fonts
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
  </head>

  <body class="min-h-screen bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a]">
    <!-- Mobile header -->
    <header
      class="flex items-center justify-between border-b border-[#e3e3e0] px-4 py-3 lg:hidden dark:border-[#3E3E3A]">
      <a href="{{ route('home') }}" class="text-base font-medium dark:text-[#EDEDEC]">Outfit Generator</a>
      <button type="button" id="sidebar-open"
        class="inline-flex items-center justify-center rounded-sm p-2 text-[#706f6c] hover:bg-[#f5f5f4] focus:outline-none focus:ring-2 focus:ring-[#e3e3e0] dark:text-[#A1A09A] dark:hover:bg-[#161615] dark:focus:ring-[#3E3E3A]"
        aria-label="Open navigation">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
          stroke="currentColor" class="h-6 w-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>
    </header>

    <!-- Mobile overlay -->
    <div id="sidebar-overlay"
      class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-[1px] transition-opacity lg:hidden"></div>

    <div class="flex min-h-screen items-stretch">
      <!-- Sidebar (mobile drawer + desktop static) -->
      <div id="sidebar-container"
        class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full transform flex-col border-r border-[#e3e3e0] bg-[#FDFDFC] transition-transform duration-200 ease-out lg:static lg:z-auto lg:flex lg:w-48 lg:translate-x-0 lg:shrink-0 lg:border-r lg:bg-transparent sm:w-56 dark:border-[#3E3E3A] dark:bg-[#0a0a0a] lg:dark:bg-transparent">
        <x-sidebar />
      </div>

      <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <div class="mx-auto w-full max-w-3xl">
          {{ $slot }}
        </div>
      </main>
    </div>

    @stack('scripts')

    <script>
      const openBtn = document.getElementById('sidebar-open');
      const closeBtn = document.getElementById('sidebar-close');
      const overlay = document.getElementById('sidebar-overlay');
      const container = document.getElementById('sidebar-container');

      function openSidebar() {
        if (!container || !overlay) return;
        container.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
      }

      function closeSidebar() {
        if (!container || !overlay) return;
        container.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }

      if (openBtn) openBtn.addEventListener('click', openSidebar);
      if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
      if (overlay) overlay.addEventListener('click', closeSidebar);

      // Close on escape key
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
      });
    </script>
  </body>

</html>
