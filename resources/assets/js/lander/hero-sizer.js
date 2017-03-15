var heroSizer = function() {
	var heroEl = document.querySelector('.hero');

	var sizeHero = function() {
		heroEl.style.height = parseInt(heroEl.scrollWidth * (1071/1998), 10) + 'px';
	}

	if ( document.querySelector('.hero') ) {
		sizeHero();
		window.addEventListener('resize', sizeHero);
	}
}

export default heroSizer