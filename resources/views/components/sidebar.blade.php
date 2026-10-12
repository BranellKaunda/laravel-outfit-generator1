@php
  $links = [
    ['label' => 'Home', 'route' => 'home'],
    ['label' => 'Generate outfit', 'route' => 'outfits.create'],
    ['label' => 'Wardrobe', 'route' => 'outfits.index'],
  ];
@endphp

<aside class="flex h-full flex-col gap-6 p-4 sm:p-6">
  <div class="flex items-center justify-between lg:block">
    <a href="{{ route('home') }}" class="text-lg font-medium dark:text-[#EDEDEC]">Outfit Generator</a>
    <button type="button" id="sidebar-close"
      class="inline-flex items-center justify-center rounded-sm p-2 text-[#706f6c] hover:bg-[#f5f5f4] focus:outline-none focus:ring-2 focus:ring-[#e3e3e0] lg:hidden dark:text-[#A1A09A] dark:hover:bg-[#161615] dark:focus:ring-[#3E3E3A]"
      aria-label="Close navigation">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
        stroke="currentColor" class="h-6 w-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
  </div>

  <nav class="flex flex-col gap-1 text-sm">
    @foreach ($links as $link)
      @if (auth()->check() || $link['route'] === 'home')
        <a href="{{ route($link['route']) }}"
          @class([
            'rounded-sm px-3 py-2',
            'bg-[#1b1b18] text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]' => request()->routeIs($link['route']),
            'text-[#706f6c] hover:bg-[#f5f5f4] dark:text-[#A1A09A] dark:hover:bg-[#161615]' => !request()->routeIs($link['route']),
          ])>
          {{ $link['label'] }}
        </a>
      @endif
    @endforeach
  </nav>

  <div class="mt-auto flex flex-col gap-1 text-sm">
    @auth
      <div class="flex flex-col gap-0.5 px-3 py-2">
        <span class="truncate font-medium dark:text-[#EDEDEC]">{{ auth()->user()->name }}</span>
        <span class="truncate text-xs text-[#706f6c] dark:text-[#A1A09A]">{{ auth()->user()->email }}</span>
      </div>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit"
          class="w-full rounded-sm px-3 py-2 text-left text-[#706f6c] hover:bg-[#f5f5f4] dark:text-[#A1A09A] dark:hover:bg-[#161615]">
          Log out
        </button>
      </form>
    @else
      <a href="{{ route('login') }}"
        @class([
          'rounded-sm px-3 py-2',
          'bg-[#1b1b18] text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]' => request()->routeIs('login'),
          'text-[#706f6c] hover:bg-[#f5f5f4] dark:text-[#A1A09A] dark:hover:bg-[#161615]' => !request()->routeIs('login'),
        ])>
        Log in
      </a>
      <a href="{{ route('register') }}"
        @class([
          'rounded-sm px-3 py-2',
          'bg-[#1b1b18] text-white dark:bg-[#eeeeec] dark:text-[#1C1C1A]' => request()->routeIs('register'),
          'text-[#706f6c] hover:bg-[#f5f5f4] dark:text-[#A1A09A] dark:hover:bg-[#161615]' => !request()->routeIs('register'),
        ])>
        Sign up
      </a>
    @endauth
  </div>
</aside>
