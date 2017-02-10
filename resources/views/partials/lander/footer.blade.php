<section class="footer">
	<div class="content-container">
		<div class="container-fluid">
			<div class="col-md-33 col-sm-50 col-xs-100">
				<p class="footer__coopyright">
					&copy; Copyright 2016 @if (date('Y') !== '2016') - {{date('Y')}}@endif BSMMA.com <span class="larger-screens">All Rights Reserved</span>
				</p>
			</div>
			<div class="col-md-33 hidden-sm hidden-xs">
				<div class="footer__logo">
					<img src="{{ env('APP_URL') }}/public/image/lander/footer-logo.png">
				</div>
			</div>
			<div class="col-md-33 col-sm-50 col-xs-100">
				<p class="footer__links">
					<a  href="{{ url('terms-of-service') }}">Terms</a> |
					<a href="{{ url('privacy-policy') }}">Privacy</a> |
					<a href="{{ url('contact-support') }}">Contact</a>
				</p>
			</div>
		</div>
	</div>
</section>