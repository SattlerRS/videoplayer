@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    <div class="flex justify-center">
        <div class="w-8/12">
            <div class="bg-white rounded-lg shadow">
                <div class="py-4 px-6 bg-gray-100 border-b">
                    <h2 class="text-3xl font-semibold">{{ __('Register') }}</h2>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block mb-2 text-lg font-medium">{{ __('Name') }}</label>

                            <input id="name" type="text" class="form-input w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block mb-2 text-lg font-medium">{{ __('Email Address') }}</label>

                            <input id="email" type="email" class="form-input w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block mb-2 text-lg font-medium">{{ __('Password') }}</label>

                            <input id="password" type="password" class="form-input w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="block mb-2 text-lg font-medium">{{ __('Confirm Password') }}</label>

                            <input id="password-confirm" type="password" class="form-input w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-blue-500 focus:border-blue-500" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
