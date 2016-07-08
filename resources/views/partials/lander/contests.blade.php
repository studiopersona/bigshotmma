<section id="contests" class="contests">
	<div class="content-container">
		<h2 class="contests__header">Contests</h2>
		<p>Compete for real cash in our fantasy MMA contests which offer a variety of formats and entry fees for players of all skill levels:</p>
		@if ( is_null($event) )
			<div class="contests__noEvents">
				<p>There are no events schedueled at this time.<br>Please check back soon for a list of current contests.</p>
			</div>
		@else
			<div class="container-fluid">
				<div class="col-xs-25">
					<img class="contests__eventImage" src="{{ asset('public/image/events'.$event['event_image_name']) }}">
					<button type="button" class="btn btn--green btn--round">All Contests</button>
				</div>
				<div class="col-xs-75">
					<h3 class="contests__tableHeader">{{ $event['event_name'] }}</h3>
					<table class="contests__table">
						@foreach ($event.contests as $contest)
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
							</tr>
						@endforeach
					</table>
				</div>
			</div>
		@endif
	</div>
</section>

