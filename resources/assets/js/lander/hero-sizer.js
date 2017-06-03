var heroSizer = function(ratio) {
	var heroEl = document.querySelector('.hero');

	var sizeHero = function() {
		heroEl.style.height = parseInt(heroEl.scrollWidth * ratio, 10) + 'px';
	}

	if ( document.querySelector('.hero') ) {
		sizeHero();
		window.addEventListener('resize', sizeHero);
	}
}

export default heroSizer