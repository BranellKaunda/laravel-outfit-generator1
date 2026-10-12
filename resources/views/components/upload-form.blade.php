<form action="/clothes" method="POST" enctype="multipart/form-data"
  {{ $attributes->merge(['class' => 'flex flex-col gap-4']) }}>
  @csrf
  @method('POST')

  <div class="flex flex-col gap-1.5">
    <label for="photo" class="font-medium dark:text-[#EDEDEC]">Photo</label>
    <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" required
      class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#706f6c] file:me-3 file:rounded-sm file:border-0 file:bg-[#FDFDFC] file:px-3 file:py-1 file:text-sm file:text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#A1A09A] file:dark:bg-[#3E3E3A] file:dark:text-[#EDEDEC]">
    @error('photo')
      <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
    @enderror
  </div>

  <div class="flex flex-col gap-1.5">
    <label for="type" class="font-medium dark:text-[#EDEDEC]">Type</label>
    <select name="type" id="type" required
      class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]">
      <option value="" disabled selected>Select a type</option>
      @foreach (\App\Enums\ImageType::cases() as $imageType)
        <option value="{{ $imageType->value }}" @selected(old('type') === $imageType->value)>{{ ucfirst($imageType->name) }}
        </option>
      @endforeach
    </select>
    @error('type')
      <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
    @enderror
  </div>

  <div>
    <button type="submit"
      class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white">
      Upload
    </button>
  </div>
</form>
