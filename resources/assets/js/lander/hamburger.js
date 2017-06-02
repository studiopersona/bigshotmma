import jQuery from 'jquery'

var hamburger = (function($, w, undefined) {
	var $menu;
	var $contentWrap;
	var $menuTrigger;

	var init = function() {
		$menuTrigger = $('#lander-menu-trigger');
		$menu = $('.mobile-main-nav-wrap');
		$contentWrap = $('.content-wrapper');

		$menuTrigger.on('click', toggleMenu);
	};

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
	};

	var closeMenu = function() {
		$menuTrigger.removeClass('is-active');
		$menu.removeClass('show');
		$contentWrap.removeClass('blur');
	};

	return {
		init: init,
		close: closeMenu,
	};

})(jQuery, window);

export default hamburger;