@props(['bottoms' => null])

<div data-carousel
  class="relative w-full overflow-hidden rounded-lg border border-[#e3e3e0] bg-white shadow-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
  @if ($bottoms->isEmpty())
    <x-placeholder label="No bottoms uploaded yet" />
  @else
    <div class="relative aspect-[4/3] w-full p-2">
      @foreach ($bottoms as $index => $bottom)
        <img data-carousel-item="{{ $index }}" src="{{ $bottom['public_url'] }}"
          alt="Bottom {{ $loop->iteration }}"
          class="{{ $index === 0 ? '' : 'hidden' }} absolute inset-0 h-full w-full object-contain">
      @endforeach
    </div>

    @if ($bottoms->count() > 1)
      <x-arrow-left data-carousel-prev aria-label="Show previous bottom" class="absolute left-2 top-1/2 -translate-y-1/2" />

      <x-arrow-right data-carousel-next aria-label="Show next bottom" class="absolute right-2 top-1/2 -translate-y-1/2" />
    @endif
  @endif
</div>

<x-carousel-script />