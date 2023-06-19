<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SP-Videoplayer</title>

    <meta charset="UTF-8">
    @if (Auth::check()) 
      <meta name="user" content="{{ Auth::user() }}">
    @endif 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 

    <!-- Scripts -->
    @vite(['resources/sass/app.scss','resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-black shadow">
            <div class="container">
                @guest
                <a href="{{ url('/') }}">
                    <img src="{{ asset('image/logo.png') }}" href="{{ route('home') }}" alt="Logo" class="h-20 w-30 rounded">
                </a>
                @else
                <a href="{{ route('home') }}">
                    <img src="{{ asset('image/logo.png') }}" href="{{ route('home') }}" alt="Logo" class="h-20 w-30 rounded">
                </a>
                @endguest

            
                <!-- DropDown -->
                <div class="relative md:hidden" data-te-dropdown-ref >
                <button
                    class="flex items-center bg-black rounded px-6 pb-2 pt-2.5 border border-white text-white text-dropdown border-dropdown"
                    type="button"
                    id="dropdownMenuButton1"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 " fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                    <ul class="absolute z-[1000] float-left my-2 pb-1 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-black shadow"
                        aria-labelledby="dropdownMenuButton1"
                        data-te-dropdown-menu-ref>
                    @guest
                        @if (Route::has('login'))
                        <li class="nav-item flex items-center text-white text-lg text-dropdown">
                            <a class="px-4 py-2" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item flex items-center text-white text-lg text-dropdown">
                            <a class="px-4 py-2" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        <li class="nav-item flex items-center text-white text-lg text-dropdown">
                            <a href="{{ url('/') }}" class="px-4 py-2">
                                {{ __('Home') }}
                           </a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item flex items-center text-white text-lg text-dropdown">
                            <a class="px-4 py-2" href="{{ route('profile') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                Profil
                            </a>
                        </li>
                        <li class="nav-item flex items-center text-white text-lg text-dropdown">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="px-4 py-2">
                                {{ __('Logout') }}
                            </a>
                        </li>
                        <li class="nav-item flex items-center text-white text-lg text-dropdown">
                            <a href="{{ route('home') }}" class="px-4 py-2">
                                {{ __('Home') }}
                            </a>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        @endguest
                    </ul>
                </div>
                <!-- DropDown End -->


                          
                <div id="navbarSupportedContent" class="hidden md:block">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                        @if (Route::has('login'))
                        <li class="nav-item flex items-center text-white text-nav">
                            <a class="px-4 py-2" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                        <li class="nav-item flex items-center text-white text-nav">
                            <a class="px-4 py-2" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @endif
                        @else
                        <li class="nav-item flex items-center text-white text-nav">
                            <a class="px-4 py-2" href="{{ route('profile') }}" role="button" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="nav-item flex items-center text-white">
                            <a href="{{ route('profile') }}">
                                <img src="{{ asset('image/' . Auth::user()->image) }}" href="#" class="w-12 h-12 rounded-full" />
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
    <footer class="bg-gray-900 text-white text-center py-2 fixed bottom-0 left-0 right-0">
        <h3>© by SP-Softwaresolutions</h3>
    </footer>
</body>
</html>
