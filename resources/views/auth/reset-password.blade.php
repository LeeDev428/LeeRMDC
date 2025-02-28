<x-guest-layout>
    @section('title', 'Recover')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="bg-white rounded-lg shadow-lg flex flex-col md:flex-row w-full max-w-4xl mx-auto mt-6 overflow-hidden">

            <!-- Left Side: Password Reset Form -->
            <div class="w-full md:w-3/5 p-6 md:p-10">
                <h2 class="text-2xl font-bold text-gray-900 text-center">Reset Your Password</h2>
                <p class="text-center text-gray-600 mt-2">Please enter your new password below</p>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" class="text-md" />
                    <x-text-input id="email" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" readonly />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500" />
                </div>

                <!-- Password -->
                <div class="mt-4 mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-md" />
                    <x-text-input id="password" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4 mb-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-md" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full p-2.5 text-md border border-gray-300 rounded-md" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500" />
                </div>

                <!-- Reset Password Button -->
                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-md">
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>
            </div>

            <!-- Right Side: Welcome Section -->
            <div class="w-full md:w-2/5 bg-gradient-to-r from-blue-600 to-green-400 text-white p-6 md:p-10 flex flex-col justify-center items-center rounded-b-lg md:rounded-b-none md:rounded-r-lg">
                <h3 class="text-xl font-bold text-center">Forgot Your Password?</h3>
                <p class="text-center mt-3 text-md">No worries! Just reset your password here.</p>
            </div>

        </div>
    </form>
</x-guest-layout>
