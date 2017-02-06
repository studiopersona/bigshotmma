<section id="contests" class="contests">
	<div class="content-container">
		<h2 class="contests__header">Contests</h2>
		<h4 class="contests__subheader">Compete for real cash in our fantasy MMA contests which offer a variety of formats and entry fees for players of all skill levels:</h4>
		@if ( is_null($event) )
			<div class="contests__noEvents">
				<p>There are no events schedueled at this time.<br>Please check back soon for a list of current contests.</p>
			</div>
		@else
			<div class="container-fluid contests__list">
				<div class="col-xs-30 contests__eventWrap larger-screens-block">
					<img class="contests__eventImage" src="{{ asset('public/image/events/'.$event['event_image_name']) }}">
					<a href="{{ env('APP_URL') }}/play" class="btn btn--green btn--round">All Contests</a>
				</div>
				<div class="col-xs-100 col-sm-68 col-sm-offset-2">
					<h3 class="contests__tableHeader">{{ $event['event_name'] }}</h3>
					<table class="contests__table">
						@if (! $event['contests']->isEmpty())
							@if ($event['contests']->count() >= 5)
								@foreach ($event['contests']->random(5) as $contest)
									<tr>
										<td class="contests__tableContestType">{{ $contest['contestType']['contest_type_name'] }}</td>
										<td>
											<span class="contests__tableTitle">Players: </span>
											<span class="contests__playerCount">{{ count($contest['contestParticipant']) }}/{{ $contest['max_participants']}}</span>
										</td>
										<td>
											<span class="contests__tableTitle">Fee: </span>
											<span class="contests__tableFee">${{ $contest['entry_fee'] }}</span>
										</td>
										<td>
											<a href="{{ url('/play') }}" class="btn btn--green btn--round">Enter</a>
										</td>
									</tr>
								@endforeach
							@else
								@foreach ($event['contests'] as $contest)
									<tr>
										<td class="contests__tableContestType">{{ $contest['contestType']['contest_type_name'] }}</td>
										<td>
											<span class="contests__tableTitle">Players: </span>
											<span class="contests__playerCount">{{ count($contest['contestParticipant']) }}/{{ $contest['max_participants']}}</span>
										</td>
										<td>
											<span class="contests__tableTitle">Fee: </span>
											<span class="contests__tableFee">${{ $contest['entry_fee'] }}</span>
										</td>
										<td>
											<a href="{{ url('/play') }}" class="btn btn--green btn--round">Enter</a>
										</td>
									</tr>
								@endforeach
							@endif
						@else
							<tr><td class="contests__noContests">No contests have been created for this event. Check back soon for updates.</td></tr>
						@endif
					</table>
					<div class="smaller-screens-block">
						<a href="{{ env('APP_URL') }}/play" class="btn btn--green btn--round">All Contests</a>
					</div>
				</div>
			</div>
		@endif
	</div>
</section>

