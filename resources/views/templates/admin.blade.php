<!doctype html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Big Shot Fantasy MMA</title>

		<meta name="description" content="">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="cleartype" content="on">

		<meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
  		<meta http-equiv="PRAGMA" content="NO-CACHE">

  		<meta name="apple-mobile-web-app-capable" content="yes">
  		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		@yield('meta')

		<!-- stylesheets -->
		@yield('styles')
		@if ( App::environment() === 'local' )
			<link href="/bsmma/public{{ elixir('css/admin/app.css') }}" rel=stylesheet >
		@elseif ( App::environment() === 'staging' )
			<link href="/staging/public{{ elixir('css/admin/app.css') }}" rel=stylesheet >
		@else
			<link href="/public{{ elixir('css/admin/app.css') }}" rel=stylesheet >
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
		<div id="app"></div>

		<!-- scripts -->
		@yield('scripts')
		@if ( App::environment() === 'local' )
			<script src="/bsmma/public{{ elixir('js/admin/index.js') }}"></script>
		@elseif ( App::environment() === 'staging' )
			<script src="/staging/public{{ elixir('js/admin/index.js') }}"></script>
		@else
			<script src="/public{{ elixir('js/admin/index.js') }}"></script>
		@endif
	</body>
</html>