(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
'use strict';

var _heroSizer = require('./lander/hero-sizer');

var _heroSizer2 = _interopRequireDefault(_heroSizer);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

window.onload = function () {
	(0, _heroSizer2.default)();
};

},{"./lander/hero-sizer":2}],2:[function(require,module,exports){
'use strict';

Object.defineProperty(exports, "__esModule", {
	value: true
});
var heroSizer = function heroSizer() {
	var heroEl = document.querySelector('.hero');

	var sizeHero = function sizeHero() {
		heroEl.style.height = parseInt(heroEl.scrollWidth * (1071 / 1998), 10) + 'px';
	};

	if (document.querySelector('.hero') !== undefined) {
		sizeHero();
		window.addEventListener('resize', sizeHero);
	}
};

exports.default = heroSizer;

},{}]},{},[1]);

//# sourceMappingURL=lander.js.map
