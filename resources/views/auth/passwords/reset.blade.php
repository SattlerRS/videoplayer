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
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="block mb-2 text-lg font-medium text-white">{{ __('Email Adresse') }}</label>

                            <div >
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="block mb-2 text-lg font-medium text-white">{{ __('Passwort') }}</label>

                            <div>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="block mb-2 text-lg font-medium text-white">{{ __('Passwort wiederholen') }}</label>

                            <div >
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="flex items-center mb-4">
                                <button type="submit" class="px-6 py-3 mr-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:bg-orange-600">
                                    {{ __('Speichern') }}
                                </button>
                                <button onclick="window.location='/'" class="px-6 py-3 mx-3 bg-orange-500 text-white rounded-md hover:bg-orange-600 focus:outline-none focus:bg-orange-600">
                                    {{ __('Zurück') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
