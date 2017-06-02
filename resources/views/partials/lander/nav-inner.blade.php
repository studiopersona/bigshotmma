<section class="navigation" role="navigation">
	<div class="nav-container larger-screens-block">
		<div class="container-fluid">
			<div class="col-xs-50">
				<a href="{{ url('/') }}">
					<img class="mainLogo" src="{{ asset('public/image/lander/logo.png') }}" alt="BSMMA - Fantasy MMA - Showcase Your Skills. Win Real Money.">
				</a>
			</div>
			<div class="col-xs-50 navigation__btnWrap">
				<a href="{{ url('/') }}#information" class="btn btn--outline" data-target="#information">Learn</button>
				<a href="{{ url('/') }}#contests" type="button" class="btn btn--outline" data-target="#contests">Contests</button>
				<a href="{{ url('/play') }}" class="btn btn--primary">Fight!</a>
			</div>
		</div>
	</div>
	<div class="mobile-nav-container smaller-screens-flex">
		<div class="mobile-menu-btn-wrap">
			<button id="lander-menu-trigger" class="hamburger hamburger--spin" type="button">
				<span class="hamburger-box">
			    	<span class="hamburger-inner"></span>
			  	</span>
			</button>
			Menu
		</div>
		<div class="mobile-logo-wrap">
			<img class="mobileMainLogo" src="{{ asset('public/image/lander/mobile-menu-logo.png') }}" alt="BSMMA - Fantasy MMA - Showcase Your Skills. Win Real Money.">
		</div>
		<div class="mobile-account-btn-wrap">
			<a href="{{ url('/play#!/login') }}" class="mobile-account-btn">
				Account <img src="{{ asset('public/image/lander/mobile-account-icon.png') }}" alt="Account Login">
			</a>
		</div>
	</div>
</section>