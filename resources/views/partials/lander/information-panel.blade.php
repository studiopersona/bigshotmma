<section id="information" class="information">
	<div class="content-container">
		<div class="row">
			<div class="col-xs-45">
				<div class="information__itemsWrap">
					<div class="information__item show" data-item="scoring">
						<h1 class="information__header">Scoring</h1>
						<p>Points are scored by making correct choices, and the player(s) with the most points win.</p>
						<p>Players can apply power ups to try and boost their total score, but there is a risk of penalty in the event the fighter fails to achieve the power up.</p>
					</div>
					<div class="information__item" data-item="how-to-play">
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
							<li><span class="highlight--pos">OLD SCHOOL</span> - top 20% of players win a prize</li>
							<li>&nbsp;</li>
							<li><span class="highlight--pos">50/50</span> -  top 50% of players win a prize</li>
							<li>&nbsp;</li>
							<li><span class="highlight--pos">GREED</span> - winner take all, one player wins a prize</li>
						</ul>
					</div>
					<div class="information__item" data-item="legal">
						<h1 class="information__header">Is this Legal?</h1>
						<p><span class="highlight--pos">Yes!</span> Daily fantasy sports (DFS) are completely legal for residents of 41 US states, and anyone is allowed to participate in our free entry contests.</p>
						<p>Residents of the following states are not eligible to participate in paid entry contests:<br><span class="highlight--neg">Alabama, Arizona, Hawaii, Idaho, Iowa, Louisiana, Montana, Nevada, and Washington</span>.</p>
					</div>
				</div>
				<div class="information__itemLinksWrap">
					<h3 class="information__header">Learn</h3>
					<ul class="informaion__itemLinksList clearfix">
						<li>
							<button type="button" class="information__itemLink" data-target="scoring">Scoring Explained</button>
						</li>
						<li>
							<button type="button" class="information__itemLink" data-target="legal">Is this legal?</button>
						</li>
						<li>
							<button type="button" class="information__itemLink" data-target="power-ups">Power Ups</button>
						</li>
						<li>
							<button type="button" class="information__itemLink" data-target="how-to-play">How to Play</button>
						</li>
						<li>
							<button type="button" class="information__itemLink" data-target="contest-formats">Contest Formats</button>
						</li>
						<li>
							<a href="{{ url('/') }}" class="information__itemLink">Start Playing</a>
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
						<div class="information__phoneScreen information__phoneScreen--scoring visible" data-item="scoring"></div>
						<div class="information__phoneScreen information__phoneScreen--powerUps" data-item="power-ups"></div>
						<div class="information__phoneScreen information__phoneScreen--howToPlay" data-item="how-to-play"></div>
						<div class="information__phoneScreen information__phoneScreen--contestFormats" data-item="contest-formats"></div>
						<div class="information__phoneScreen information__phoneScreen--legal" data-item="legal"></div>
					</div>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container-fluid -->
</section>