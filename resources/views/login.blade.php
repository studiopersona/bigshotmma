@extends('templates.default')

@section('title', 'Login/Register')

@section('content')
	<section :class="flipClasses">
		<div class="flipper">
			<div class="front">
				<login>
					<header class="pageHeader">
						<h1 class="pageHeader__header">Player Login</h1>
						<h4 class="pageHeader__subheader">
							Don't have an account yet?
							<a @click="flipped = !flipped" href="#">Sign Up</a>
						</h4>
					</header>
					@include('partials.login')
					<div class="login__forgotPassword">
						<a href="{{ url('') }}">Forgot Password?</a>
					</div>
					<div class="logo">
						<img src="{{ url('public/images/logo.jpg') }}" alt="Big Shot Fantasy MMA Logo">
					</div>
				</login>
			</div>
			<div class="back">
				<register>
					<header class="pageHeader">
						<h1 class="pageHeader__header">Player Sign-Up</h1>
						<h4 class="pageHeader__subheader">
							Already have an account?
							<a @click="flipped = !flipped" href="#">Login</a>
						</h4>
					</header>
					@include('partials.register')
					<div class="logo">
						<img src="{{ url('public/images/logo.jpg') }}" alt="Big Shot Fantasy MMA Logo">
					</div>
				</register>
			</div>
		</div>
	</section>
	<!-- Loading Dialog For use by Activities -->
	<div :class="loaderClasses">
		<svg viewBox="0 0 32 32" width="32" height="32">
	    	<circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
	  	</svg>
	</div>
@endsection