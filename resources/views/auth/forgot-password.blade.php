<x-app-layout>
    <div class="w-[400px] mx-auto p-6 my-16">
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')"/>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <input id="email" placeholder="Your email address"
                       class="border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full"
                       type="email" name="email" :value="old('email')" required
                       autofocus/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="btn-primary bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-700 w-full">
                    {{ __('Email Password Reset Link') }}
                    <button>
            </div>
        </form>
    </div>
</x-app-layout>
