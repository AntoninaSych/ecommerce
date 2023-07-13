<x-app-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')"/>

    <form action="{{route('login')}}" method="post" class="w-[400px] mx-auto p-6 my-16">@csrf
        <h2 class="text-2xl font-semibold text-center mb-5">
            Login to your account
        </h2>
        <p class="text-center text-gray-500 mb-6">
            or
            <a
                    href="{{route('register')}}"
                    class="text-sm text-purple-700 hover:text-purple-600"
            >create new account</a
            >
        </p> @if ($errors->any())
            <div class="p-3 rounded-md bg-red-600 text-white">
                <div class="font-medium">
                    {{ __('Whoops! Something went wrong.') }}
                </div>

                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div><br>
        @endif
        <div class="mb-4">
            <input
                    id="loginEmail"
                    type="email"
                    name="email"
                    placeholder="Your email address"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full"
            />
            {{--            <x-input-error :messages="$errors->get('email')" class="mt-2"/>--}}
        </div>
        <div class="mb-4">
            <input
                    id="loginPassword"
                    type="password"
                    name="password"
                    placeholder="Your password"
                    class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full"
            />
            {{--            <x-input-error :messages="$errors->get('password')" class="mt-2"/>--}}
        </div>

        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center">
                <input
                        id="loginRememberMe"
                        type="checkbox"
                        class="mr-3 rounded border-gray-300 text-purple-500 focus:ring-purple-500"
                />
                <label for="loginRememberMe">Remember Me</label>
            </div>
            <a href="{{route('password.request')}}" class="text-sm text-purple-700 hover:text-purple-600">Forgot
                Password?</a>
        </div>
        <x-primary-button>
            Login
        </x-primary-button>
    </form>


</x-app-layout>
