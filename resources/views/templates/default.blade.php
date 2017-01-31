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
			<link href="/bsmma/public{{ elixir('css/app.css') }}" rel=stylesheet >
		@elseif ( App::environment() === 'staging' )
			<link href="/staging/public{{ elixir('css/app.css') }}" rel=stylesheet >
		@else
			<link href="/public{{ elixir('css/app.css') }}" rel=stylesheet >
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
			<script src="/bsmma/public{{ elixir('js/index.js') }}"></script>
		@elseif ( App::environment() === 'staging' )
			<script src="/staging/public{{ elixir('js/index.js') }}"></script>
		@else
			<script src="/public{{ elixir('js/index.js') }}"></script>
		@endif

		@if (App::environment() !== 'local' && App::environment() !== 'staging')
			<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-75650217-1', 'auto');
			ga('send', 'pageview');

			</script>
		@endif
	</body>
</html>