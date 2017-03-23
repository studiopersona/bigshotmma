<!doctype html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<title>Big Shot Fantasy MMA</title>

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
		@if ( App::environment() === 'production')
			<script>
				!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
				n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
				n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
				t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
				document,'script','https://connect.facebook.net/en_US/fbevents.js');
				fbq('init', '1014779411913260'); // Insert your pixel ID here.
				fbq('track', 'PageView');
			</script>
			<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1014779411913260&ev=PageView&noscript=1" /></noscript>
			<!-- DO NOT MODIFY -->
			<!-- End Facebook Pixel Code -->
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
		<div class="app">
			@yield('content')
		</div>
		<!-- scripts -->
		@yield('scripts')
	</body>
</html>