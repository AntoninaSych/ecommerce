<x-app-layout>
    <div class="w-[400px] mx-auto p-6 my-16">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                {{--            <x-input-label for="name" :value="__('Name')"/>--}}
                <input id="name" placeholder="Your name"
                       class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full"
                       type="text" name="name" :value="old('name')" required
                       autofocus autocomplete="name"/>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                {{--            <x-input-label for="email" :value="__('Email')"/>--}}
                <input id="email" placeholder="Your email"
                       class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full"
                       type="email" name="email" :value="old('email')" required
                       autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                {{--            <x-input-label for="password" :value="__('Password')"/>--}}

                <input id="password" placeholder="Your password"
                       class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full"
                       type="password"
                       name="password"
                       required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password')" class="mt-2"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                {{--            <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>--}}

                <input id="password_confirmation" placeholder="Repeat password"
                       class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full"

                       type="password"
                       name="password_confirmation" required autocomplete="new-password"/>

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
            </div>

            <div class="flex items-center justify-end mt-4">


                <button class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full">
                    {{ __('Register') }}
                </button>
            </div>
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </form>
    </div>
    </x-guest-layout>
