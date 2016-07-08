import jQuery from 'jquery';

var infoSlides = (function($, w, undefined) {
	var $triggers;
	var $infoWindows;
	var $windowsHolder;
	var $infoCloseBtns;

	var init = function() {
		$triggers = $('.powerupsCarousel__itemButton');
		$infoWindows = $('.powerupsInfo__item');
		$windowsHolder = $('.powerupsInfo');
		$infoCloseBtns = $('.powerupsInfo__closeBtn');

		$triggers.on('click', showInfo);
		$infoCloseBtns.on('click', closeInfo);
	};

	var showInfo = function(e) {
		var powerupID;

		e.preventDefault();

		powerupID = $(e.currentTarget).data('id');

		$windowsHolder.addClass('show');
		$infoWindows.filter('[data-id="' + powerupID + '"]').addClass('show');
	};

	var closeInfo = function(e) {
		var powerupID;

		e.preventDefault();

		powerupID = $(e.currentTarget).data('id');

		$windowsHolder.removeClass('show');
		$infoWindows.filter('[data-id="' + powerupID + '"]').removeClass('show');
	};

	return {
		init: init,
	};
})(jQuery, window);

export default infoSlides;