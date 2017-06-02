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
		var target = $(e.currentTarget).data('target');

		e.preventDefault();
		$('html,body').animate({
	    	scrollTop: $(target).offset().top - 100,
	    }, 600);
	}

	return {
		init: init
	}

})(jQuery, window)

export default menuControl