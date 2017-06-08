import heroSizer from './lander/hero-sizer'
import mobileHeroPadding from './lander/mobile-hero-padding'
import hamburger from './lander/hamburger'
import menuControl from './lander/menu-control'
import Carousel from './lander/carousel'
import powerupSlides from './lander/powerup-info'
import infoPanels from './lander/info-panels'
import formControl from './lander/form-control'
import $ from 'jquery'
import parsley from 'parsleyjs'

window.onload = function() {


	$(window).on('orientationchange resize', function() {
		$('.content-wrapper').css('paddingTop', $('.navigation').height() + 'px')
	})

	$('.content-wrapper').css('paddingTop', $('.navigation').height() + 'px')

	menuControl.init(hamburger)

	if ( $(window).width() > 615 ) {
		heroSizer(1071/1998);
	} else {
		hamburger.init();
		mobileHeroPadding(720/760);
	}

	if (document.getElementById('powerupsCarousel')) {
			var ipf = 6

			if (window.innerWidth < 900) ipf = 4
			if (window.innerWidth < 768 ) ipf = 3
			if (window.innerWidth < 600 ) ipf = 2

			var pc = new Carousel.getInstance({
				$carouselHolder: $('.carousel__holder'),
	            $itemsWrap: $('.carousel__wrap'),
	            $items: $('.carousel__item'),
	            $navBtnsWrap: $('.carousel-nav__wrap'),
	            marginSize: 15, // in pixels
	            itemsPerFrame: ipf,
			})

			$('.carousel-nav').on('click', pc.move)
		}

	if (document.querySelector('.powerupsInfo')) powerupSlides.init()

	if (document.getElementById('information')) infoPanels.init()

	if (document.getElementById('contact-form')) formControl.init({
        	formSelector: '#contact-form',
        	submitBtnSelector: '#contact-submit',
    	})
};