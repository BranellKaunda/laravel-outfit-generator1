<x-layout>
  <section id="wardrobe" class="flex flex-col gap-3" data-status-url="{{ route('outfits.status') }}"
    data-count="{{ $outfits->count() }}" data-generating="{{ $generating ? '1' : '0' }}">
    <h2 class="font-medium dark:text-[#EDEDEC]">Wardrobe</h2>

    @if (request()->boolean('generated'))
      <div role="status" aria-live="polite"
        class="rounded-sm border border-[#a5d6a7] bg-[#e8f5e9] p-3 text-sm text-[#256029] dark:border-[#3E6B41] dark:bg-[#12241a] dark:text-[#7fce8a]">
        Your new outfit is ready!
      </div>
    @elseif ($generating)
      <div id="generation-status" role="status" aria-live="polite"
        class="rounded-sm border border-[#e3e3e0] bg-[#f7f7f6] p-3 text-sm text-[#706f6c] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#A1A09A]">
        Your outfit is being generated. This can take a moment. It will appear here shortly.
      </div>
    @endif

    <x-outfits :outfits="$outfits" />
  </section>

  @if ($generating && !request()->boolean('generated'))
    @push('scripts')
      <script>
        (function() {
          const wardrobe = document.getElementById('wardrobe');
          if (!wardrobe) return;

          const endpoint = wardrobe.dataset.statusUrl;
          const initialCount = Number(wardrobe.dataset.count);
          const wardrobeUrl = @json(route('outfits.index'));

          const check = async () => {
            try {
              const response = await fetch(endpoint, {
                headers: {
                  'Accept': 'application/json'
                }
              });
              if (!response.ok) return;

              const data = await response.json();
              if (data.count > initialCount) {
                clearInterval(timer);
                window.location.replace(wardrobeUrl + '?generated=1');
              }
            } catch (error) {
              // Stay silent; the next tick retries.
            }
          };

          const timer = setInterval(check, 3000);
        })();
      </script>
    @endpush
  @endif
</x-layout>
