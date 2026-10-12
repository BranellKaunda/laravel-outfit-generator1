@props(['tops' => null])

<div data-carousel
  class="relative w-full overflow-hidden rounded-lg border border-[#e3e3e0] bg-white shadow-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
  @if ($tops->isEmpty())
    <x-placeholder label="No tops uploaded yet" />
  @else
    <div class="relative aspect-[4/3] w-full p-2">
      @foreach ($tops as $index => $top)
        <img data-carousel-item="{{ $index }}" src="{{ $top['public_url'] }}" alt="Top {{ $loop->iteration }}"
          class="{{ $index === 0 ? '' : 'hidden' }} absolute inset-0 h-full w-full object-contain">
      @endforeach
    </div>

    @if ($tops->count() > 1)
      <x-arrow-left data-carousel-prev aria-label="Show previous top" class="absolute left-2 top-1/2 -translate-y-1/2" />

      <x-arrow-right data-carousel-next aria-label="Show next top" class="absolute right-2 top-1/2 -translate-y-1/2" />
    @endif
  @endif
</div>

<x-carousel-script />
