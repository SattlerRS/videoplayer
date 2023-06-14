@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10">
    <div class="flex justify-center">
        <div class="w-8/12">
            <div class="bg-black rounded-lg shadow">
                <div class="py-4 px-6 bg-orange-500 border-b">
                    <h2 class="text-3xl font-semibold text-white">{{ __('Login') }}</h2>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block mb-2 text-lg font-medium text-white">{{ __('Email Address') }}</label>

                            <input id="email" type="email" class="form-input w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block mb-2 text-lg font-medium text-white">{{ __('Password') }}</label>

                            <input id="password" type="password" class="form-input w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">

                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center mb-4">
                            <input class="form-checkbox text-orange-500" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="ml-2 text-lg font-medium text-white" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-6 py-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:bg-orange-600">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </form>

                    <!-- Link zum Zurücksetzen des Passworts -->
                    <div class="mt-4">
                        <a href="{{ route('password.request') }}" class="text-orange-500">{{ __('Forgot Your Password?') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
