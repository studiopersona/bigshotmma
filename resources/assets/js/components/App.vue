<template>
    <div>
    	<div class="appHeader">
            <button type="button" @click="toggleMenu" class="appHeader__menuBtn">Dashboard <img :src="'public/image/menu-icon.png'"></button>
        </div>
        <router-view></router-view>
        <nav class="appDashboard">
        	 <ul>
        	 	<li><a v-link="{ path: '/player/contests' }" @click="toggleMenu">Contests</a></li>
        	 	<li><a v-link="{ path: '/rules' }" @click="toggleMenu">Rules</a></li>
        	 	<!--<li><a v-link="{ path: '/profile' }">Profile</a></li>-->
        	 </ul>
        </nav>
    </div>
</template>

<script>
	//import auth from '../auth';
	import {router} from '../index';

	export default {
		data: function() {
			return {
			};
		},

		created() {
		},

		methods: {
			toggleMenu() {
				document.querySelector('.appDashboard').classList.toggle('show');
			},
		},

		computed: {
		},
	};
</script>