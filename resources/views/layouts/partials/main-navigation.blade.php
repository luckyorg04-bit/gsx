@unless(request()->is('/'))
    <nav class="navbar sticky bg-white w-full flex lg:flex-row px-4 lg:px-8 pt-4 mb-0 justify-between items-center shadow">
        <div>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 mb-4 no-underline" aria-label="GSX home">
                <span class="inline-flex items-center justify-center rounded-lg bg-slate-900 text-white font-bold tracking-wider" style="width:42px;height:42px;">GSX</span>
                <span class="text-xl font-bold tracking-wide text-slate-900">GSX</span>
            </a>
        </div>
        <div>
            <ul class="navbar-nav ml-auto flex items-center">
                @guest
                    <li class="nav-item px-2"><a class="text-sm lg:text-base px-3 py-1 rounded bg-white border hover:bg-gray-300 hover:text-gray-900" href="{{ route('register') }}" id="register">{{ __('Free Sign Up') }}</a></li>
                    <li class="nav-item px-2"><a class="text-sm lg:text-base px-3 py-1 rounded bg-white border hover:bg-gray-300 hover:text-gray-900" href="{{ route('login') }}" id="login">{{ __('Login') }}</a></li>
                @endguest
                @auth
                    <li class="nav-item px-2"><a class="text-sm lg:text-base px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 hover:text-white" href="{{ route('dashboard') }}" id="dashboard">{{ __('Dashboard') }}</a></li>
                    <li><a class="text-sm lg:text-base px-3 py-1 rounded bg-white border hover:bg-gray-300 hover:text-gray-900" dusk="logout-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>
@endunless

@if(request()->is('/'))
    <nav class="navbar bg-white w-full flex lg:flex-row px-3 mt-4 mb-0 justify-between items-center home-navbar">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 no-underline" aria-label="GSX home">
            <span class="inline-flex items-center justify-center rounded-lg bg-slate-900 text-white font-bold tracking-wider" style="width:42px;height:42px;">GSX</span>
            <span class="text-xl font-bold tracking-wide text-slate-900">GSX</span>
        </a>
        <div>
            <ul class="navbar-nav ml-auto flex items-center">
                @guest
                    <li class="nav-item px-2"><a class="text-sm lg:text-base px-3 py-1 rounded bg-white border hover:bg-gray-300 hover:text-gray-900" href="{{ route('register') }}" id="register">{{ __('Free Sign Up') }}</a></li>
                    <li class="nav-item px-2"><a class="text-sm lg:text-base px-3 py-1 rounded bg-white border hover:bg-gray-300 hover:text-gray-900" href="{{ route('login') }}" id="login">{{ __('Login') }}</a></li>
                @endguest
                @auth
                    <li class="nav-item px-2"><a class="text-sm lg:text-base px-3 py-1 rounded bg-red-600 text-white hover:bg-red-700 hover:text-white" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                @endauth
            </ul>
        </div>
    </nav>
@endif
