<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Videoplayer</title>

    <meta charset="UTF-8">
    @if (Auth::check()) 
      <meta name="user" content="{{ Auth::user() }}">
    @endif 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss','resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
<nav class="navbar navbar-expand-md navbar-light bg-black shadow-sm">
        <div class="container">
            <a class="navbar-brand text-white" href="{{ url('/') }}">
                Videoplayer
            </a>
            <button class="navbar-toggler" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div id="navbarSupportedContent">
                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ms-auto">
                    <!-- Authentication Links -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item flex items-center text-white text-nav">
                                <a class="px-4 py-2 " href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item flex items-center text-white text-nav">
                                <a class="px-4 py-2 " href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item flex items-center text-white text-nav">
                            <a class="px-4 py-2" href="{{ route('profile') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre >
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="nav-item flex items-center text-white">
                            <a href="{{ route('profile') }}">
                                <img  src="{{ asset('image/' . Auth::user()->image) }}" href="#" class="w-12 h-12 rounded-full"/>
                            </a>
                        </li>
                        <li class="nav-item flex items-center text-white text-nav">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="px-4 py-2">
                                {{ __('Logout') }}
                            </a>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
        <main class="py-0">
            @yield('content')
        </main>
    </div>
</body>
</html>
