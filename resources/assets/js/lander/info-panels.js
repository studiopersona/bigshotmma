import jQuery from 'jquery'

var panelControl = (function($, w, undefined) {
	var $triggers,
		$infoWindows,
		$phoneScreens

	var init = function() {
		$triggers = $('button.information__itemLink')
		$infoWindows = $('.information__item')
		$phoneScreens = $('.information__phoneScreen')

		$triggers.on('click', switchInfo);

		$('.information__itemsWrap').outerHeight($infoWindows.filter('[data-item="scoring"]').innerHeight() + 'px');
	}

	var switchInfo = function(e) {
		var target = $(e.currentTarget).data('target');

		$infoWindows.filter('.show').removeClass('show');
		$infoWindows.filter('[data-item="' + target + '"]').addClass('show');

		$phoneScreens.filter('.visible').removeClass('visible');
		$phoneScreens.filter('[data-item="' + target + '"]').addClass('visible');
		$('.information__itemsWrap').outerHeight($infoWindows.filter('[data-item="' + target + '"]').innerHeight() + 'px');
	}

	return {
		init: init,
	}

})(jQuery, window)

export default panelControl