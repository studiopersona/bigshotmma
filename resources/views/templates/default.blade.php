<!doctype html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>@yield('title', 'Login') | Blood Sport Fantasy MMA</title>

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width">
		@yield('meta')

		<!-- stylesheets -->
		@yield('styles')
		@if ( App::environment() === 'local' )
			<link href="/bsmma/public/css/app.css" rel=stylesheet >
		@elseif ( App::environment() === 'staging' )
			<link href="/staging/public/css/app.css" rel=stylesheet >
		@else
			<link href="/public/css/app.css" rel=stylesheet >
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

		<div id="app">
			@yield('content')
		</div><!-- #app -->

		<!-- scripts -->
		@yield('scripts')
		@if ( App::environment() === 'local' )
			<script src="/bsmma/public{{ elixir('js/index.js') }}"></script>
		@elseif ( App::environment() === 'staging' )
			<script src="/staging/public{{ elixir('js/index.js') }}"></script>
		@else
			<script src="/public{{ elixir('js/index.js') }}"></script>
		@endif
	</body>
</html>