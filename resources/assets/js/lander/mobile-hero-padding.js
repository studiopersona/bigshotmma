var mobileHeroPadding = function(ratio) {
	var heroEl = document.querySelector('.hero');

	var setHeroPadding = function() {
		heroEl.style.paddingTop = parseInt(heroEl.scrollWidth * ratio, 10) - 70 + 'px';
	}

	if ( document.querySelector('.hero') ) {
		setHeroPadding();
		window.addEventListener('resize', setHeroPadding);
	}
}

export default mobileHeroPadding