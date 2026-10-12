<x-layout>
  <div class="grid grid-cols-1 items-start gap-4 sm:grid-cols-2">
    <section class="flex flex-col gap-3">
      <h2 class="font-medium dark:text-[#EDEDEC]">Recent tops</h2>
      <x-tops :tops="$tops" />
    </section>

    <section class="flex flex-col gap-3">
      <h2 class="font-medium dark:text-[#EDEDEC]">Recent avatars</h2>
      <x-avatars :avatars="$avatars" />
    </section>

    <section class="flex flex-col gap-3">
      <h2 class="font-medium dark:text-[#EDEDEC]">Recent bottoms</h2>
      <x-bottoms :bottoms="$bottoms" />
    </section>

    <section
      class="flex flex-col gap-3 rounded-lg bg-white p-5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
      <div>
        <h2 class="font-medium dark:text-[#EDEDEC]">Upload a photo</h2>
        <p class="mt-1 text-[#706f6c] dark:text-[#A1A09A]">Add a clothing item to your wardrobe.</p>
      </div>

      <x-upload-form />
    </section>
  </div>
</x-layout>
