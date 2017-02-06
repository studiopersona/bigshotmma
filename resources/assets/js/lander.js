import heroSizer from './lander/hero-sizer'
import Carousel from './lander/carousel'
import powerupSlides from './lander/powerup-info'
import infoPanels from './lander/info-panels'
import menuControl from './lander/menu-control'
import formControl from './lander/form-control'
import $ from 'jquery'
import parsley from 'parsleyjs'

window.onload = function() {
	heroSizer()
	menuControl.init()

	console.log($(window).width())
	if ( $(window).width() > 615 ) {
		if (document.getElementById('powerupsCarousel')) {
			var ipf = 6

			if (window.innerWidth < 900) ipf = 4
			if (window.innerWidth < 768 ) ipf = 3
			if (window.innerWidth < 600 ) ipf = 2

			var pc = new Carousel.getInstance({
				$carouselHolder: $('.carousel__holder'),
	            $itemsWrap: $('.carousel__wrap'),
	            $items: $('.carousel__item'),
	            $navBtnsWrap: $('.carousel-nav'),
	            marginSize: 15, // in pixels
	            itemsPerFrame: ipf,
			})

			$('.carousel-nav').on('click', pc.move)
		}
	}

	if (document.querySelector('.powerupsInfo')) powerupSlides.init()

	if (document.getElementById('information')) infoPanels.init()

	if (document.getElementById('contact-form')) formControl.init({
        	formSelector: '#contact-form',
        	submitBtnSelector: '#contact-submit',
    	})
};