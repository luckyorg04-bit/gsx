<!-- <!DOCTYPE html> -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.partials.favicon')
    <title>{{ config('app.name', 'GSX') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
  <body id="app">
  <div class="fixed w-full h-full overflow-auto">
  <div class="fixed bg-white px-5 py-2 rounded-b-lg mx-2 lg:mx-16 z-40">
  	<img src="{{url('favicon/gsx.svg')}}" class="w-12 lg:w-24" alt="GSX">
  </div>
  @include('pages.video.__video-bar')
  @yield('content')
  <!-- @include('pages.video.__video-toolbar') -->
  </div>
     <script src="{{ mix('js/manifest.js') }}"></script>
     <script src="{{ mix('js/vendor.js') }}"></script>
     <script src="{{ mix('js/app.js') }}"></script>
        <script src="{{ asset('js/custom.js') }}" ></script>
   @stack('scripts')
</body>
</html>
