<div class="container mx-auto homepage pt-8 pb-16 lg:px-32 flex flex-col items-center">
    <div class="image-wrapper mt-2 mb-8">
        @include('layouts.partials.logo')
    </div>

    <demo-tab url="{{ url('/') }}"></demo-tab>

    <a href="{{ route('login') }}" class="btn bg-slate-900 hover:bg-slate-700 text-white font-bold tracking-wide uppercase text-xs px-5 py-3 mt-8 cursor-pointer rounded flex items-center justify-center">
        Access the GSX Parent Portal
    </a>

    @guest
        <div class="my-4 text-center mx-2 lg:mx-0 md:mx-0">
            <a href="{{ url('/register') }}" class="inline-block px-3 py-2 bg-red-600 text-base lg:text-xl md:text-xl font-exo text-white tracking-wide rounded my-1">
                Trial Signup (FREE for 30 Days)
            </a>
        </div>
    @endguest
</div>
