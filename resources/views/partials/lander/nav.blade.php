<section class="navigation" role="navigation">
	<div class="nav-container">
		<div class="container-fluid">
			<div class="col-xs-50">
				<img class="mainLogo larger-screens" src="{{ asset('public/image/lander/logo.png') }}" alt="BSMMA - Fantasy MMA - Showcase Your Skills. Win Real Money.">
				<img class="mainLogo smaller-screens" src="{{ asset('public/image/lander/logo-small.jpg') }}" alt="BSMMA - Fantasy MMA - Showcase Your Skills. Win Real Money.">
			</div>
			<div class="col-xs-50 navigation__btnWrap">
				<button type="button" class="btn btn--outline larger-screens" data-target="#information">Learn</button>
				<button type="button" class="btn btn--outline larger-screens" data-target="#contests">Contests</button>
				<a href="{{ url('/play') }}" class="btn btn--primary">Fight!</a>
			</div>
		</div>
	</div>
</section>