<!doctype html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>@yield('title', 'Login') | Big Shot Fantasy MMA</title>

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta http-equiv="cleartype" content="on">

		<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
  		<meta http-equiv="PRAGMA" content="NO-CACHE">

  		<meta name="viewport" content="width=device-width">
  		<meta name="apple-mobile-web-app-capable" content="yes">
  		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		@yield('meta')

		<!-- stylesheets -->
		@yield('styles')
		@if ( App::environment() === 'local' )
			<link href="/bsmma/public{{ elixir('css/lander.css') }}" rel=stylesheet >
		@elseif ( App::environment() === 'staging' )
			<link href="/staging/public{{ elixir('css/lander.css') }}" rel=stylesheet >
		@else
			<link href="/public{{ elixir('css/lander.css') }}" rel=stylesheet >
		@endif

		<script>
			var URL = {
				'base' : '{{ URL::to('/') }}',
				'current' : '{{ URL::current() }}',
				'full' : '{{ URL::full() }}'
			};
		</script>
	</head>
	<body>
		@include('partials.lander.nav-inner')
		<div class="content-wrapper">
			@include('partials.lander.hero')
			<section class="informational">
				@yield('page-title')
				@yield('updated')
				@yield('content')
			</section>
			@include('partials.lander.power-ups')
			@include('partials.lander.footer')
		</div>
		<!-- scripts -->
		@yield('scripts')
		@if ( App::environment() === 'local' )
			<script src="/bsmma/public{{ elixir('js/lander.js') }}"></script>
		@elseif ( App::environment() === 'staging' )
			<script src="/staging/public{{ elixir('js/lander.js') }}"></script>
		@else
			<script src="/public{{ elixir('js/lander.js') }}"></script>
		@endif
	</body>
</html>