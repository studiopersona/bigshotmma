<section class="hero">
	<div class="larger-screens-block">
		<h1 class="visuallyhidden">Fantasy MMA</h1>
		<h2 class="visuallyhidden">Showcase your skills. Win real money.</h2>
		<div class="visuallyhidden">We accept PayPal, Visa, MasterCard, Discover and American Express</div>
		<a href="{{ url('/play#!/register') }}" class="btn mobile-nav-btn hero__ctaBtn">Create Account</a>
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
