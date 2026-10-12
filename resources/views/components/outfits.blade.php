@props(['outfits' => null])

<div
  class="relative w-full overflow-hidden rounded-lg border border-[#e3e3e0] bg-white shadow-sm dark:border-[#3E3E3A] dark:bg-[#161615]">
  @if ($outfits->isEmpty())
    <x-placeholder label="No outfits generated yet" />
  @else
    <div class="grid grid-cols-2 gap-2 p-2 sm:grid-cols-3">
      @foreach ($outfits as $outfit)
        <img src="{{ $outfit['public_url'] }}" alt="Generated outfit {{ $loop->iteration }}"
          class="aspect-[3/4] w-full rounded-sm object-cover">
      @endforeach
    </div>
  @endif
</div>
