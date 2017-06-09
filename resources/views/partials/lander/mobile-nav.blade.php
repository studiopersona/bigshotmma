<div class="mobile-main-nav-wrap">
	<nav class="mobile-main-nav">
		<ul>
			<li><a data-target="#contests">Contests</a></li>
			<li><a data-target="#information">How to Play</a></li>
			<li><a data-target="#powerups">Power Ups</a></li>
			<!--<li><a>Updates</a></li>-->
			<li><a href="{{ url('contact-support') }}">Reach Out</a></li>
		</ul>
	</nav>
	<div class="mobile-nav-btn-wrap">
		<a href="{{ url('play#!/register') }}" class="mobile-nav-btn">Create Account</a>
		<a href="{{ url('play#!/login') }}" class="mobile-nav-btn mobile-nav-btn--dark">Player Login</a>
	</div>
	<div class="menu-nav-footer">
		<h2 class="menu-nav-footer-header">Skills, Not Scripts.</h2>
		<h3 class="menu-nav-footer-subheader">We've got <a href="#">power ups</a> too.</h3>
		<img src="{{ asset('public/image/lander/mobile-menu-powerups.png') }}" alt="Cindrella +5, Hoyce +3">
	</div>
</div>