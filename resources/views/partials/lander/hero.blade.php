<section class="hero">
	<div class="content-container larger-screens-block">
		<h1 class="hero__header">Skills, Not Scripts.</h1>
		<h2 class="hero__subheader">(we've got <a id="powerupsBtn" href="#" data-target="#powerups">power ups</a> too)</h2>
		<a href="{{ url('/play') }}" class="btn btn--outline hero__ctaBtn larger-screens-inline-block">Play Now!</a>
	</div>
	<div class="content-container smaller-screens-block">
		<div class="mobileHeroBtn__wrap">
			<a class="mobileHeroBtn" href="{{ url('play#!/register') }}">Create Account</a>
			<!--<a class="mobileHeroBtn mobileHeroBtn--light" href="http://youtube.com/bsmma">Watch Tutorial</a>-->
		</div>
		<div class="quote__wrap">
			<span class="quote">"Absolutely love the play structure!"</span>
		</div>
		<img class="fiveStarRating" src="{{ asset('public/image/lander/mobile-five-stars.png') }}" alt="Five Star Rating">
		<div class="mobileHero__paymentLogos">
			<span class="visuallyhidden">We accept PayPal, Visa, MasterCard, Discover, American Express</span>
		</div>
	</div>
</section>
