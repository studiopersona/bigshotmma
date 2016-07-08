import heroSizer from './lander/hero-sizer'
import Carousel from './lander/carousel'
import powerupSlides from './lander/powerup-info'
import infoPanels from './lander/info-panels'
import menuControl from './lander/menu-control'
import $ from 'jquery'

window.onload = function() {
	heroSizer()
	menuControl.init();

	if (document.getElementById('powerupsCarousel')) {
		var pc = new Carousel.getInstance({
			$carouselHolder: $('.carousel__holder'),
            $itemsWrap: $('.carousel__wrap'),
            $items: $('.carousel__item'),
            $navBtnsWrap: $('.carousel-nav'),
            marginSize: 15, // in pixels
            itemsPerFrame: 6,
		});

		$('.carousel-nav').on('click', pc.move);
	}

	if (document.querySelector('.powerupsInfo')) {
		powerupSlides.init()
	}

	if (document.querySelector('#information')) {
		infoPanels.init()
	}
};