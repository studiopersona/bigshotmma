import jQuery from 'jquery'

var hamburger = (function($, w, undefined) {
	var $menu;
	var $contentWrap;

	var init = function() {
		$('#lander-menu-trigger').on('click', toggleMenu);
		$menu = $('.mobile-main-nav-wrap');
		$contentWrap = $('.content-wrapper');
	}

	var toggleMenu = function(e) {
		var target = $(e.currentTarget);

		e.preventDefault();

		if ( target.hasClass('is-active') ) {
			target.removeClass('is-active');
			$menu.removeClass('show');
			$contentWrap.removeClass('blur');
		} else {
			target.addClass('is-active');
			$menu.addClass('show');
			$contentWrap.addClass('blur');
		}
	}

	return {
		init: init
	}

})(jQuery, window);

export default hamburger;