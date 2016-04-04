@extends('templates.default')

@section('title', 'Login/Register')

@section('content')
	<app-header>
		<h1 class="pageHeader__header">@{{ pageHeader }}</h1>
		<h4 class="pageHeader__subheader">@{{ pageSubheader }}</h4>
	</app-header>
	<login>
		@include('partials.login')
		<div class="login__forgotPassword">
			<a href="{{ url('') }}">Forgot Password?</a>
		</div>
	</login>
	<div class="logo">
		<img src="{{ url('public/images/logo.jpg') }}" alt="Blood Sport Fantasy MMA Logo">
	</div>
	<!-- Loading Dialog For use by Activities -->
	<div class="loader js-global-loader visuallyhidden">
		<svg viewBox="0 0 32 32" width="32" height="32">
	    	<circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
	  	</svg>
	</div>
@endsection