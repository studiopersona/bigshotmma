import jQuery from 'jquery'

var menuControl = (function($, w, undefined) {

	var init = function(hamburger) {
		$('.navigation__btnWrap').on('click', 'button', scrollPage);
		$('#powerupsBtn').on('click', scrollPage);

		$('.mobile-main-nav').on('click', 'a[data-target]', function(e) {
			hamburger.close();
			scrollPage(e);
		});
	}

	var scrollPage = function(e) {
		var target = $(e.currentTarget).data('target'),
			windowWidth = $(w).width(),
			scrollAdjustment;


		if ( windowWidth <= 768 ) {
			scrollAdjustment = 18
		} else if ( windowWidth <= 1200 ) {
			scrollAdjustment = 80
		} else {
			scrollAdjustment = 100
		}

		e.preventDefault();
		$('html,body').animate({
	    	scrollTop: $(target).offset().top - scrollAdjustment,
	    }, 600);
	}

	return {
		init: init
	}

})(jQuery, window)

export default menuControl