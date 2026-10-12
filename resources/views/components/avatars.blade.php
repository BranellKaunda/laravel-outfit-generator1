@props(['avatars' => null])

<div data-carousel
  class="relative w-full overflow-hidden rounded-lg border border-[#e3e3e0] bg-white shadow-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
  @if ($avatars->isEmpty())
    <x-placeholder label="No avatars uploaded yet" />
  @else
    <div class="relative aspect-[4/3] w-full p-2">
      @foreach ($avatars as $index => $avatar)
        <img data-carousel-item="{{ $index }}" src="{{ $avatar['public_url'] }}" alt="Avatar {{ $loop->iteration }}"
          class="{{ $index === 0 ? '' : 'hidden' }} absolute inset-0 h-full w-full object-contain">
      @endforeach
    </div>

    @if ($avatars->count() > 1)
      <x-arrow-left data-carousel-prev aria-label="Show previous avatar"
        class="absolute left-2 top-1/2 -translate-y-1/2" />

      <x-arrow-right data-carousel-next aria-label="Show next avatar"
        class="absolute right-2 top-1/2 -translate-y-1/2" />
    @endif
  @endif
</div>

<x-carousel-script />
