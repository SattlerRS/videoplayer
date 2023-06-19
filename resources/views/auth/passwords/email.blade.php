@extends('layouts.app')

@section('content')
<div class="pt-10 bg-black h-screen">
    <div class="flex justify-center">
        <div class="w-8/12 flex justify-center ">
            <div class="bg-black rounded-lg shadow w-50">
                <div class="card-header bg-orange-500 py-4 px-6 rounded">
                    <h2 class="text-3xl font-semibold text-white">{{ __('Passwort zurücksetzen') }}</h2>
                </div>

                <div class="card-body p-6 bg-black rounded-lg">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="block mb-2 text-lg font-medium text-white">{{ __('Email Adresse') }}</label>

                            <input id="email" type="email" class="form-input w-full px-4 py-2 rounded-lg border focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex items-center mb-4">
                            <button type="submit" class="px-6 py-3 mr-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:bg-orange-600">
                                {{ __('Reset Password') }}
                            </button>
                            <button onclick="window.location='/'" class="px-6 py-3 mx-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:bg-orange-600">
                                {{ __('Zurück') }}
                            </button>
                        </div>
                     </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
