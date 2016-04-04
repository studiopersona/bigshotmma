// browserify entrypoint

var Vue = require('vue');

import AppHeader from './components/AppHeader.vue';
import Login from './components/Login.vue';

new Vue({
	el: '#app',

	data: {
		pageHeader: 'Player Login',
		pageSubheader: 'Don\'t have an account yet? <a href="#">Sign Up</a>',
	},

	components: { AppHeader, Login },

	ready() {

	},
});