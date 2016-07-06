<!doctype html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>@yield('title', 'Login') | Blood Sport Fantasy MMA</title>

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
		@include('partials.lander.hero')
		@include('partials.lander.nav')
		@include('partials.lander.information-panel')
		@include('partials.lander.contests')
		@include('partials.lander.power-ups')
		@include('partials.lander.footer')
		@include('partials.lander.nav')
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