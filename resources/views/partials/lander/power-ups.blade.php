<section id="powerups" class="powerUps">
	<div class="content-container">
		<h1 class="powerUps__header">Power Ups</h1>
		<h2 class="powerUps__subheader">Earn bonus points for success, but risk penalty for failure!</h2>
		<div class="carousel">
			<div class="carousel-nav__wrap">
				<button class="carousel-nav" data-direction="forward"><span class="visuallyhidden">Forward</span>More</button>
			    <button class="carousel-nav disabled" data-direction="backward"><span class="visuallyhidden">Backward</span>More</button>
			</div>
		    <div id="powerupsCarousel" class="carousel__holder">
		        <div class="carousel__wrap">
		            @foreach ($powerUps as $powerUp)
		                <div class="carousel__item">
		                	<button type="button" data-id="{{ $powerUp['id'] }}" class="powerupsCarousel__itemButton">
		            			<img class="powerupsCarousel__itemImage" src="{{ asset('public/image/powerups/'.$powerUp['power_up_image_name']) }}">
		            			<h5 class="powerupsCarousel__itemTitle" style="color:{{ $powerUp['power_up_color'] }}">{{ $powerUp['power_up_name'] }}</h5>
		            		</button>
		                </div>
		            @endforeach
		        </div>
		    </div>
		</div>
		<a href="{{ url('/play') }}" class="btn btn--black btn--semiround">Start Playing</a>
	    <div class="powerupsInfo">
	    	@foreach ($powerUps as $powerUp)
	    		<div class="powerupsInfo__item" data-id="{{ $powerUp['id'] }}">
	            	<h5 class="powerupsInfo__itemTitle" style="color:{{ $powerUp['power_up_color'] }}">{{ $powerUp['power_up_name'] }}</h5>
	    			<img class="powerupsInfo__itemImage" src="{{ asset('public/image/powerups/'.$powerUp['power_up_image_name']) }}">
	    			<p class="powerupsInfo__description">{{ $powerUp['power_up_description'] }}</p>
	    			<div class="powerUpInfo__points" style="color:{{ $powerUp['power_up_color'] }}">
	    				@if ($powerUp['penalty_points'] > 1)
        					+{{ $powerUp['bonus_points'] }} points / -{{ $powerUp['penalty_points'] }} points
        				@else
        					+{{ $powerUp['bonus_points'] }} points / -{{ $powerUp['penalty_points'] }} point
        				@endif
        			</div>
	    			<button type="button" class="btn btn--green btn--semiround powerupsInfo__closeBtn" data-id="{{ $powerUp['id'] }}">Got It</button>
	    		</div>
	    	@endforeach
	    </div>
	</div>
</section>