@extends('templates.lander-inner')

@section('title', 'Conact Us')

@section('page-title')
	<h1>Contact Us</h1>
@endsection

@section('content')
	<p>Complete the form below and one of our representatives will get back to you within 24 hours.</p>
	<p>Or call us Monday thru Friday, 9:00AM - 7:00PM EST<br><strong>(954) 213-6762</strong></p>
	<div class="form-wrap">
    	<form id="contact-form" action="{{ url('contact-support') }}" method="POST">
    		<div class="container-fluid">
    			<div class="row">
    				<div class="col-sm-49 small-screen-row">
    					<label class="text-input" for="firstname">
							<input type="text" name="firstname" placeholder="First Name" data-parsley-required data-parsley-error-message="Your first name is required">
							<span class="visuallyhidden">First Name</span>
						</label>
    				</div>
    				<div class="col-sm-49 col-sm-offset-2">
    					<label class="text-input" for="lastname">
							<input type="text" name="lastname" placeholder="Last Name">
							<span class="visuallyhidden">Last Name</span>
						</label>
    				</div>
    			</div>
    			<div class="row">
    				<div class="col-sm-49 small-screen-row">
    					<label class="text-input" for="phone">
							<input type="text" name="phone" placeholder="Phone Number" data-parsley-required data-parsley-error-message="Your phone number is required">
							<span class="visuallyhidden">Phone Number</span>
						</label>
    				</div>
    				<div class="col-sm-49 col-sm-offset-2">
    					<label class="text-input" for="email">
						<input type="email" name="email" placeholder="Email Address" data-parsley-required data-parsley-error-message="Your email address is required">
						<span class="visuallyhidden">Email Address</span>
					</label>
    				</div>
    			</div>
    			<div class="row">
					<div class="col-sm-100">
						<select id="select1" name="need_help_with">
							<option class="placeholder" value="">How can we help you?</option>
							<option value="Game Play">Game Play</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-100">
						<textarea name="extra_info" placeholder="Please provide some additional information to help us assist you..." data-parsley-required data-parsley-error-message="Please elaborate on the reason you are contacting us."></textarea>
					</div>
				</div>
				<div class="row">
					<div>
						<button type="submit" id="contact-submit" class="btn btn--primary icon icon-bubbles3 workingBtn">
							<span class="workingBtn__indicator"></span>
							<span class="workingBtn__label">Submit</span>
						</button>
					</div>
				</div>
			</div><!-- .container-fluid -->
    	</form>
    </div><!-- .form-wrap -->
@endsection