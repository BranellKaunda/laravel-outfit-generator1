<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @fonts
    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
  </head>

  <body
    class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:p-8 dark:bg-[#0a0a0a]">
    <main
      class="w-full max-w-[335px] rounded-lg bg-white p-6 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] lg:max-w-md lg:p-10 dark:bg-[#161615] dark:text-[#EDEDEC] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
      <h1 class="mb-1 font-medium">Upload a photo</h1>
      <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">Add a clothing item to your wardrobe.</p>

      <form action="/clothes" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
        @csrf
        @method('POST')

        <div class="flex flex-col gap-1.5">
          <label for="photo" class="font-medium">Photo</label>
          <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" required
            class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#706f6c] file:me-3 file:rounded-sm file:border-0 file:bg-[#FDFDFC] file:px-3 file:py-1 file:text-sm file:text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#A1A09A] file:dark:bg-[#3E3E3A] file:dark:text-[#EDEDEC]">
          @error('photo')
            <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex flex-col gap-1.5">
          <label for="type" class="font-medium">Type</label>
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
    </main>
  </body>

</html>
