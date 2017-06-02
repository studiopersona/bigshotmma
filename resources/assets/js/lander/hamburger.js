import jQuery from 'jquery'

var hamburger = (function($, w, undefined) {
	var $menu;

	var init = function() {
		$('#lander-menu-trigger').on('click', toggleMenu);
		$menu = $('.mobile-main-nav-wrap');
	}

	var toggleMenu = function(e) {
		var target = $(e.currentTarget);

		e.preventDefault();

		if ( target.hasClass('is-active') ) {
			target.removeClass('is-active');
			$menu.removeClass('show');
		} else {
			target.addClass('is-active');
			$menu.addClass('show');
		}
	}

	return {
		init: init
	}

})(jQuery, window);

export default hamburger;