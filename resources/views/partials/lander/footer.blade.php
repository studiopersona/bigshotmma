<section class="footer">
	<div class="smaller-screens-block">
		<h3 class="followHeader">Follow BSMMA</h3>
		<div class="socialLinks__wrap">
			<a href="http://facebook.com" class="icon-facebook-with-circle social-icon"><span class="visuallyhidden">Facebook</span></a>
			<a href="http://instagram.com" class="icon-instagram-with-circle social-icon"><span class="visuallyhidden">Instagram</span></a>
			<a href="http://twitter.com" class="icon-twitter-with-circle social-icon"><span class="visuallyhidden">Twitter</span></a>
			<a href="http://youtube.com" class="icon-youtube-with-circle social-icon"><span class="visuallyhidden">YouTube</span></a>
		</div>
	</div>
	<div class="content-container">
		<div class="container-fluid">
			<div class="col-md-33 col-sm-50 col-xs-100">
				<p class="footer__coopyright">
					&copy; Copyright 2016 @if (date('Y') !== '2016') - {{date('Y')}}@endif BSMMA.com <span class="larger-screens">All Rights Reserved</span>
				</p>
			</div>
			<div class="col-md-33 hidden-sm hidden-xs">
				<div class="footer__logo">
					<img src="{{ url('/') }}/public/image/lander/footer-logo.png">
				</div>
			</div>
			<div class="col-md-33 col-sm-50 col-xs-100">
				<p class="footer__links">
					<a href="{{ url('terms-of-service') }}">Terms</a> |
					<a href="{{ url('privacy-policy') }}">Privacy</a> |
					<a href="{{ url('contact-support') }}">Contact</a>
				</p>
			</div>
		</div>
	</div>
</section>