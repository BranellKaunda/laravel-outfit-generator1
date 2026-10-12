<x-layout>
  <section
    class="flex flex-col gap-4 rounded-lg bg-white p-5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
    <div>
      <h2 class="font-medium dark:text-[#EDEDEC]">Generate an outfit</h2>
      <p class="mt-1 text-[#706f6c] dark:text-[#A1A09A]">Pick an avatar, a top, and a bottom, then let AI dress them.</p>
    </div>

    @error('outfit')
      <div role="alert"
        class="rounded-sm border border-[#f0b0a5] bg-[#fdecea] p-3 text-sm text-[#a3372a] dark:border-[#6b3a33] dark:bg-[#241413] dark:text-[#f0a99e]">
        {{ $message }}
      </div>
    @enderror

    @if ($remaining === 0)
      <div role="status"
        class="rounded-sm border border-[#e3e3e0] bg-[#f7f7f6] p-3 text-sm text-[#706f6c] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#A1A09A]">
        You have reached your daily limit of {{ $dailyLimit }} outfits. Please come back tomorrow.
      </div>
    @else
      <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
        You can generate {{ $remaining }} more {{ Str::plural('outfit', $remaining) }} today.
      </p>
    @endif

    <x-generate-form :tops="$tops" :bottoms="$bottoms" :avatars="$avatars" :disabled="$remaining === 0" />
  </section>
</x-layout>
