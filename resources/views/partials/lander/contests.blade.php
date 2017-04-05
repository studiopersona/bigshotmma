<section id="contests" class="contests">
	<div class="content-container">
		<h2 class="contests__header">Contests</h2>
		<h4 class="contests__subheader">Compete for real cash in our fantasy MMA contests which offer a variety of formats and entry fees for players of all skill levels:</h4>
		@if ( is_null($event) )
			<div class="contests__noEvents">
				<p>There are no contests available at this time.<br>Please check back soon for a list of current contests.</p>
			</div>
		@else
			<div class="container-fluid contests__list">
				<div class="col-sm-100 col-md-30 hidden-xs contests__eventWrap larger-screens-block">
					<img class="contests__eventImage" src="{{ asset('public/image/events/'.$event['event_image_name']) }}">
					<h4 class="countdownHeader">Live In:</h4>
					<div id="countdown"></div>
					<script>
						// Set the date we're counting down to
						var countDownDate = new Date("{{ date('M j, Y H:i:s', strtotime($event['date_time'].' -30 min')) }}").getTime();

						// Get todays date and time
						var now = new Date().getTime();

						// Find the distance between now an the count down date
						var distance = countDownDate - now;

						// Time calculations for days, hours, minutes and seconds
						var days = Math.floor(distance / (1000 * 60 * 60 * 24));
						var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
						var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
						var seconds = Math.floor((distance % (1000 * 60)) / 1000);

						document.getElementById("countdown").innerHTML = '<span class="countdownValue">' + days + '</span><span class="countdownLabel">days</span> <span class="countdownValue">'+ hours + '</span><span class="countdownLabel">hours</span> <span class="countdownValue">' + minutes + '</span><span class="countdownLabel">minutes</span>';

						// Update the count down every 1 second
						var x = setInterval(function() {

							// Get todays date and time
							var now = new Date().getTime();

							// Find the distance between now an the count down date
							var distance = countDownDate - now;

							// Time calculations for days, hours, minutes and seconds
							var days = Math.floor(distance / (1000 * 60 * 60 * 24));
							var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
							var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
							var seconds = Math.floor((distance % (1000 * 60)) / 1000);

							// Display the result in the element with id="demo"
							if ( days > 0 ) {
								document.getElementById("countdown").innerHTML = '<span class="countdownValue">' + days + '</span><span class="countdownLabel">days</span> <span class="countdownValue">'+ hours + '</span><span class="countdownLabel">hours</span> <span class="countdownValue">' + minutes + '</span><span class="countdownLabel">minutes</span>';
							} else {
								document.getElementById("countdown").innerHTML = '<span class="countdownValue">'+ hours + '</span><span class="countdownLabel">hours</span> <span class="countdownValue">' + minutes + '</span><span class="countdownLabel">minutes</span>';
							}

							// If the count down is finished, write some text
							if (distance < 0) {
								clearInterval(x);
								document.getElementById("countdown").innerHTML = "Event Has Started";
							}
						}, 60000);
					</script>
				</div>
				<div class="col-xs-100 col-sm-100 col-md-68 col-sm-offset-2">
					<h3 class="contests__tableHeader">{{ $event['event_name'] }}</h3>
					<table class="contests__table">
						@if ($event['contests']->isEmpty())
							<tr><td class="contests__noContests">No contests have been created for this event. Check back soon for updates.</td></tr>
						@else
							@if ($event['contests']->count() >= 5)
								@foreach ($event['contests']->random(5) as $contest)
									<tr>
										<td class="contests__tableContestType">{{ $contest['contestType']['contest_type_name'] }}</td>
										<td>
											<span class="contests__tableTitle">Players: </span>
											<span class="contests__playerCount">{{ count($contest['contestParticipants']) }}/{{ $contest['max_participants']}}</span>
										</td>
										<td>
											<span class="contests__tableTitle">Entry: </span>
											<span class="contests__tableFee">${{ $contest['entry_fee'] }}</span>
										</td>
										<td>
											<a href="{{ url('/play') }}" class="btn btn--green btn--round">Lobby</a>
										</td>
									</tr>
								@endforeach
							@else
								@foreach ($event['contests'] as $contest)
									<tr>
										<td class="contests__tableContestType">{{ $contest['contestType']['contest_type_name'] }}</td>
										<td>
											<span class="contests__tableTitle">Players: </span>
											<span class="contests__playerCount">{{ count($contest['contestParticipants']) }}/{{ $contest['max_participants']}}</span>
										</td>
										<td>
											<span class="contests__tableTitle">Entry: </span>
											<span class="contests__tableFee">${{ $contest['entry_fee'] }}</span>
										</td>
										<td>
											<a href="{{ url('/play') }}" class="btn btn--green btn--round">Lobby</a>
										</td>
									</tr>
								@endforeach
							@endif
						@endif
					</table>
					<div class="all-contests-btn-wrap">
						<a href="{{ url('/play') }}" class="btn btn--green btn--round">View All Contests</a>
					</div>
				</div>
			</div>
		@endif
	</div>
</section>

