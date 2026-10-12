<x-layout title="Log in">
  <section
    class="flex flex-col gap-4 rounded-lg bg-white p-5 shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:bg-[#161615] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
    <h2 class="font-medium dark:text-[#EDEDEC]">Log in</h2>

    <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-4">
      @csrf

      <div class="flex flex-col gap-1.5">
        <label for="email" class="font-medium dark:text-[#EDEDEC]">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
          class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]">
        @error('email')
          <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
        @enderror
      </div>

      <div class="flex flex-col gap-1.5">
        <label for="password" class="font-medium dark:text-[#EDEDEC]">Password</label>
        <input type="password" name="password" id="password" required autocomplete="current-password"
          class="w-full rounded-sm border border-[#e3e3e0] bg-white p-2 text-sm text-[#1b1b18] dark:border-[#3E3E3A] dark:bg-[#161615] dark:text-[#EDEDEC]">
        @error('password')
          <p class="text-sm text-[#f53003] dark:text-[#FF4433]">{{ $message }}</p>
        @enderror
      </div>

      <label class="flex items-center gap-2 text-sm text-[#706f6c] dark:text-[#A1A09A]">
        <input type="checkbox" name="remember" value="1" @checked(old('remember'))
          class="rounded-sm border-[#e3e3e0] dark:border-[#3E3E3A]">
        Remember me
      </label>

      <div>
        <button type="submit"
          class="inline-block rounded-sm border border-black bg-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-white hover:border-black hover:bg-black dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A] dark:hover:border-white dark:hover:bg-white">
          Log in
        </button>
      </div>
    </form>

    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
      Don't have an account?
      <a href="{{ route('register') }}" class="underline dark:text-[#EDEDEC]">Sign up</a>
    </p>
  </section>
</x-layout>
