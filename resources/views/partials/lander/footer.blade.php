<section class="footer">
	<div class="content-container">
		<div class="container-fluid">
			<div class="col-xs-50">
				<p class="footer__coopyright">
					&copy; Copyright 2016 @if (date('Y') !== '2016') - {{date('Y')}}@endif BSMMA.com All Rights Reserved</p>
			</div>
			<div class="col-xs-50">
				<p class="footer__links">
					<a  href="{{ url('terms-of-service') }}">Term of Service</a> |
					<a href="{{ url('privacy-policy') }}">Privacy Policy</a> |
					<a href="{{ url('contact-support') }}">Contact Support</a>
				</p>
			</div>
		</div>
	</div>
</section>