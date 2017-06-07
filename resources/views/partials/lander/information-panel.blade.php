<section id="information" class="information">
	<div class="content-container">
		<div class="row larger-screens-block">
			<div class="col-sm-45 col-xs-100">
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
							<li><span class="highlight--pos">CLASSIC</span> - top 20% of players win a prize</li>
							<li>&nbsp;</li>
							<li><span class="highlight--pos">50/50</span> -  top 50% of players win a prize</li>
							<li>&nbsp;</li>
							<li><span class="highlight--pos">GREED</span> - winner take all, one player wins a prize</li>
						</ul>
					</div>
					<div class="information__item" data-item="legal">
						<h1 class="information__header">Is this Legal?</h1>
						<p><span class="highlight--pos">Yes!</span> Daily fantasy sports (DFS) are completely legal for residents of 41 US states, and anyone is allowed to participate in our free entry contests.</p>
						<p>Residents of the following states are not eligible to participate in paid entry contests:<br><span class="highlight--neg">Alabama, Arizona, Hawaii, Idaho, Iowa, Louisiana, Montana, Nevada, and Washington</span></p>
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
							<a href="{{ url('/play') }}" class="information__itemLink">Start Playing</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="col-sm-50 col-sm-offset-5">
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
		<div class="row smaller-screens-block">
			<div class="col-sm-45 col-xs-100">
				<div class="information__itemsWrap">
					<div class="information__item show" data-item="how-to-play">
						<h1 class="information__header">How to Play</h1>
						<p>Choose five (5) fighters and decide how &amp; when they will win to score points.</p>
						<div class="information__itemSection">
							<h3 class="information__subheader">Pick 5 Fighters</h3>
							<img src="{{ asset('public/image/lander/mobile-pick-fighters.png') }}" alt="Five fighters icons indicating five fighters chosen">
						</div>
						<div class="information__itemSection">
							<h3 class="information__subheader">Points System</h3>
							<img src="{{ asset('public/image/lander/mobile-points-system.png') }}" alt="5 points for correct finish, 5 points for correct underdog figher win, 3 points for correct favorite firghter win, 2 points for correct round, 1 point for correct minute">
						</div>
						<div class="information__itemSection">
							<h3 class="information__subheader">Power Ups*</h3>
							<img class="mobileInformation__powerUpStars" src="{{ asset('public/image/lander/mobile-power-up-stars.png') }}" alt="3 stars indicating 3 power ups selected">
							<p class="information__footnote">
								*Up to three (3) powers ups can be applied per contest to score bonus points.
							</p>
						</div>
					</div>
					<div class="information__item" data-item="contest-formats">
						<h1 class="information__header">Formats</h1>
						<p>There are three different contest formats available with varying levels of risk.</p>
						<ul>
							<li>
								<h3 class="information__subheader">Classic</h3>
								<div class="formats__descriptionWrap">
									<div class="formats__descriptionImgWarp">
										<img src="{{ asset('public/image/info/classic-controller.png') }}" alt="Classic Format Icon">
									</div>
									<p class="formats__description">
										<span class="formats__highlight">Top 20%</span> of highest scoring players win a prize.
									</p>
								</div>
							</li>
							<li>
								<h3 class="information__subheader">Greed</h3>
								<div class="formats__descriptionWrap">
									<div class="formats__descriptionImgWarp">
										<img src="{{ asset('public/image/info/greed-icon.png') }}" alt="Greed Format Icon">
									</div>
									<p class="formats__description">
										The top scorer takes home <span class="formats__highlight">100%</span> of the prize pool.
									</p>
								</div>
							</li>
							<li>
								<h3 class="information__subheader">50/50</h3>
								<div class="formats__descriptionWrap">
									<div class="formats__descriptionImgWarp">
										<img src="{{ asset('public/image/info/5050-bar.png') }}" alt="Fifty Fifty Format Icon">
									</div>
									<p class="formats__description">
										<span class="formats__highlight">Top 50%</span> of highest scoring players win a prize.
									</p>
								</div>
							</li>
						</ul>
					</div>
					<div class="information__item" data-item="legal">
						<h1 class="information__header">Legal Info</h1>
						<p>BSMMA offers daily fantasy (DFS) contests to players in Canada and the United States* with the exception of these states:</p>
						<div class="illegalStates">
							AL, AR, HI, ID, LA, MT, NV, WA
						</div>
						<img class="illegalStatesMap" src="{{ asset('public/image/lander/mobile-legal-map.png') }}" alt="Map graphic indicating AL, AR, HI, ID, LA, MT, NV, WA are not eligible for participation in paid contests">
						<p class="legal__footnote">
							*Resrictions only apply to paid contests.
						</p>
						<p><strong>All are welcome to compete in FREE contests!</strong></p>
					</div>
				</div>
				<div class="information__itemLinksWrap">
					<h3 class="information__header">Learn More</h3>
					<div class="mobileInformation__itemLinksList">
						<div class="current">
							<button type="button" class="information__itemLink" data-target="how-to-play">How to Play</button>
						</div>
						<div>
							<button type="button" class="information__itemLink" data-target="legal">Legal Info</button>
						</div>
						<div>
							<button type="button" class="information__itemLink" data-target="contest-formats">Formats</button>
						</div>
						<div>
							<a href="{{ url('/play') }}" class="information__itemLink">Start Playing</a>
						</div>
						<div class="arrow"></div>
					</ul>
				</div>
			</div>
		</div><!-- .row -->
	</div><!-- .container-fluid -->
</section>