import jQuery from 'jquery'

var panelControl = (function($, w, undefined) {
	var $triggers,
		$infoWindows,
		$phoneScreens,
		$arrowTrigger,
		$learnMoreList

	var init = function() {
		$triggers = $('button.information__itemLink')
		$infoWindows = $('.information__item')
		$phoneScreens = $('.information__phoneScreen')
		$arrowTrigger = $('.mobileInformation__itemLinksList > div.arrow')
		$learnMoreList = $('.mobileInformation__itemLinksList')

		$triggers.on('click', switchInfo);
		$arrowTrigger.on('click', toggleLearnMore)

		$('.information__itemsWrap').outerHeight($infoWindows.filter('[data-item="scoring"]').innerHeight() + 'px')
	}

	var switchInfo = function(e) {
		var target = $(e.currentTarget).data('target')

		$infoWindows.filter('.show').removeClass('show')
		$infoWindows.filter('[data-item="' + target + '"]').addClass('show')

		$phoneScreens.filter('.visible').removeClass('visible');
		$phoneScreens.filter('[data-item="' + target + '"]').addClass('visible');
		$('.information__itemsWrap').outerHeight($infoWindows.filter('[data-item="' + target + '"]').innerHeight() + 'px')

		$('.mobileInformation__itemLinksList > div.current').removeClass('current')
		$(e.currentTarget).parent().addClass('current')
		$learnMoreList.toggleClass('open')
	}

	var toggleLearnMore = function(e) {
		var target = $(e.currentTarget)

		$learnMoreList.toggleClass('open')
	}

	return {
		init: init,
	}

})(jQuery, window)

export default panelControl