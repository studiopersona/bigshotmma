import jQuery from 'jquery'

var menuControl = (function($, w, undefined) {

	var init = function() {
		$('.navigation__btnWrap').on('click', 'button', scrollPage);
	}

	var scrollPage = function(e) {
		var target = $(e.currentTarget).data('target');

		e.preventDefault();
		$('html,body').animate({
	    	scrollTop: $(target).offset().top,
	    }, 600);
	}

	return {
		init: init
	}

})(jQuery, window)

export default menuControl