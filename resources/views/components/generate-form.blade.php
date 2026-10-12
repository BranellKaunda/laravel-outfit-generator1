@props(['tops' => null, 'bottoms' => null, 'avatars' => null, 'disabled' => false])

<form action="{{ route('outfits.store') }}" method="POST" {{ $attributes->merge(['class' => 'flex flex-col gap-4']) }}>
  @csrf

  @foreach ([
      'avatar_id' => ['label' => 'Avatar', 'items' => $avatars],
      'top_id' => ['label' => 'Top', 'items' => $tops],
      'bottom_id' => ['label' => 'Bottom', 'items' => $bottoms],
    ] as $field => $group)
    <fieldset class="flex flex-col gap-1.5" @disabled($disabled)>
      <legend class="font-medium dark:text-[#EDEDEC]">{{ $group['label'] }}</legend>

      @if ($group['items']->isEmpty())
        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">No {{ strtolower($group['label']) }}s uploaded yet.</p>
      @else
        <div class="flex flex-wrap gap-2">
          @foreach ($group['items'] as $item)
            <label class="cursor-pointer">
              <input type="radio" name="{{ $field }}" value="{{ $item['id'] }}" class="peer sr-only" required
                @checked(old($field) == $item['id'])>
              <img src="{{ $item['public_url'] }}" alt=""
                class="h-20 w-20 rounded-sm border-2 border-transparent object-cover peer-checked:border-[#1b1b18] peer-focus-visible:ring-2 peer-focus-visible:ring-[#1b1b18] dark:peer-checked:border-[#eeeeec] dark:peer-focus-visible:ring-[#eeeeec]">
            </label>
          @endforeach
        </div>
      @endif

      @error($field)
        <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
      @enderror
    </fieldset>
  @endforeach

  <div>
    <button type="submit" @disabled($disabled)
      class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black disabled:cursor-not-allowed disabled:opacity-50 dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white">
      Generate outfit
    </button>
  </div>
</form>
