<section id="information" class="information">
	<div class="content-container">
		<div class="row">
			<div class="col-xs-45">
				<div class="information__itemsWrap">
					<div class="information__item show" data-item="how-to-play">
						<h1 class="information__header">How to Play</h1>
						<p>Each player picks a total of five (5) fighters from the event lineup and decides how &amp; when each fight will end.</p>
						<p>Points are awarded based on performance, and winning players receive cash prizes!</p>
					</div>
					<div class="information__item" data-item="power-ups">
						<h1 class="information__header">Power Ups</h1>
						<p>Apply up to three (3) power ups per contest which can help you score bonus points if the chosen fighter performs accordingly.</p>
						<p>On the flip side, if the chosen fighter fails to achieve the desired result, a penalty will result.</p>
					</div>
					<div class="information__item" data-item="contest-formats">
						<h1 class="information__header">Contest Fomats</h1>
						<ul>
							<li>OLD SCHOOL - top 20% of players win a prize</li>
							<li>50/50 -  top 50% of players win a prize</li>
							<li>GREED - winner take all, one player wins a prize</li>
						</ul>
					</div>
					<div class="information__item" data-item="legal">
						<h1 class="information__header">Is this Legal?</h1>
						<p>Yes! Daily fantasy sports (DFS) are completely legal for residents of 41 US states, and anyone is allowed to participate in our free entry contests.</p>
						<p>Residents of the following states are not eligible to participate in paid entry contests:<br>Alabama, Arizona, Hawaii, Idaho, Iowa, Louisiana, Montana, Nevada, and Washington.</p>
					</div>
				</div>
				<div class="information__itemLinksWrap">
					<h3 class="information__header">Learn</h3>
					<ul class="informaion__itemLinksList clearfix">
						<li>
							<button type="button" class="information__itemLink" data-target="how-to-play">Scoring Explained</button>
						</li>
						<li>
							<button type="button" class="information__itemLink" data-target="legal">Is this legal?</button>
						</li>
						<li>
							<button type="button" class="information__itemLink" data-target="power-ups">Power Ups</button>
						</li>
						<li>
							<a href="{{ url('/') }}" class="information__itemLink">Start Playing</a>
						</li>
						<li>
							<button type="button" class="information__itemLink" data-target="contest-formats">Contest Formats</button>
						</li>
					</ul>
				</div>
				<div class="information__thirdPartyLogosWrap">
					<img src="{{ asset('public/image/lander/paypal-logo.png') }}" class="information__thirdPartyLogo information__thirdPartyLogo--paypal">
					<img src="{{ asset('public/image/lander/credit-card-logos.png') }}" class="information__thirdPartyLogo information__thirdPartyLogo--creditCards">
					<img src="{{ asset('public/image/lander/ssl-logo.png') }}" class="information__thirdPartyLogo information__thirdPartyLogo--ssl">
				</div>
			</div>
			<div class="col-xs-50 col-xs-offset-5">
				<div class="information__phoneTemplateWrap">
					<div class="information__phoneScreenWrap">
						<div class="information__phoneScreen information__phoneScreen--howToPlay visible"></div>
						<div class="information__phoneScreen information__phoneScreen--powerUps"></div>
						<div class="information__phoneScreen information__phoneScreen--contestFormats"></div>
						<div class="information__phoneScreen information__phoneScreen--legal"></div>
					</div>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container-fluid -->
</section>