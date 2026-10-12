<x-layout title="Sign up">
  <section
    class="flex flex-col gap-4 rounded-lg bg-white p-5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
    <h2 class="font-medium dark:text-[#EDEDEC]">Sign up</h2>

    <form action="{{ route('register') }}" method="POST" class="flex flex-col gap-4">
      @csrf

      <div class="flex flex-col gap-1.5">
        <label for="name" class="font-medium dark:text-[#EDEDEC]">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name"
          class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]">
        @error('name')
          <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="email" class="font-medium dark:text-[#EDEDEC]">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="username"
          class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]">
        @error('email')
          <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="password" class="font-medium dark:text-[#EDEDEC]">Password</label>
        <input type="password" name="password" id="password" required autocomplete="new-password"
          class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]">
        @error('password')
          <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="password_confirmation" class="font-medium dark:text-[#EDEDEC]">Confirm password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
          class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]">
      </div>

      <div>
        <button type="submit"
          class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white">
          Sign up
        </button>
      </div>
    </form>

    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
      Already have an account?
      <a href="{{ route('login') }}" class="underline dark:text-[#EDEDEC]">Log in</a>
    </p>
  </section>
</x-layout>
