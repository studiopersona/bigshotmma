<template>
    <div>
    	<div v-if="loggedIn" class="appHeader">
            <button type="button" @click="toggleMenu" class="appHeader__menuBtn">Dashboard <img :src="'public/image/menu-icon.png'"></button>
        </div>
        <router-view></router-view>
        <nav class="appDashboard">
        	<header class="pageHeader">
	            <h1 class="pageHeader__header">Dashboard</h1>
	            <h4 class="pageHeader__subheader">Logged in as {{ playersName }}. <a @click.prevent="logout" v-link="{path: '/logout'}">Logout</a> </h4>
	        </header>
        	 <ul class="appDashboard__list">
        	 	<li class="appDashboard__linkWrap">
        	 		<a class="appDashboard__link appDashboard__link--contests" v-link="{ path: '/player/contests' }" @click="toggleMenu">
        	 			<img src="public/image/dashboard/contests.png">
        	 			Contests
        	 		</a>
        	 	</li>
        	 	<li class="appDashboard__linkWrap">
        	 		<a class="appDashboard__link appDashboard__link--rules" @click="showRules">
        	 			<img src="public/image/dashboard/rules.png">
        	 			Rules
        	 		</a>
        	 	</li>
        	 	<li class="appDashboard__linkWrap">
        	 		<a class="appDashboard__link appDashboard__link--profile" v-link="{ path: '/profile' }">
        	 			<img src="public/image/dashboard/profile.png">
        	 			Profile
        	 		</a>
        	 	</li>
        	 </ul>
        </nav>
        <section id="rulesSlider" class="rulesSlider">
        	<div v-for="type in contestTypes" :class="['rulesSlider__slide', currentRulesSlide === $index ? 'current' : '']">
        		<h3 class="rulesSlider__title">{{ type.contest_type_name }}</h3>
        		<div class="rulesSlider__icon">
        			<img :src="URL.base + '/public/image/info/' + type.image_name">
        		</div>
        		<div class="rulesSlider__description">
        			{{{ type.contest_type_rules }}}
        		</div>
        		<div class="rulesSlider__positionIndicator">
        			<span data-slide-number="0" :class="['rulesSlider__indicator', $index === 0 ? 'current' : '']"></span>
        			<span data-slide-number="1" :class="['rulesSlider__indicator', $index === 1 ? 'current' : '']"></span>
        			<span data-slide-number="2" :class="['rulesSlider__indicator', $index === 2 ? 'current' : '']"></span>
        		</div>
        		<div class="button-wrap">
	                <button @click.prevent="changeSlide($index, $event)" type="button" class="button button--green">Got It</button>
	            </div>
	            <button @click="rulesSliderClose" type="button" class="alertModal__close">x</button>
	        </div>
	    </section>
    </div>
</template>

<script>
	//import auth from '../auth';
	import {router} from '../index';
	import auth from '../auth';

	export default {
		data: function() {
			return {
				contestTypes: [],
				currentRulesSlide: 0,
				loggedIn: false,
				playersName: '',
				URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
			};
		},

		created() {
			this.$http.get( URL.base + '/api/v1/contest-types', {}, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).then(
                function(response) {
                    this.contestTypes = response.data;
                },
                function(err) {
                    console.log(err);
            });

            this.$http.get( URL.base + '/api/v1/player-name', {}, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).then(
                function(response) {
                    this.playersName = response.data.player_name;
                },
                function(err) {
                    console.log(err);
            });
		},

		ready() {
			if ( auth.validate() ) this.loggedIn = true;
		},

		methods: {
			toggleMenu() {
				document.querySelector('.appDashboard').classList.toggle('show');
			},

			showRules() {
				document.querySelector('.appDashboard').classList.toggle('show');
				document.querySelector('#rulesSlider').classList.toggle('show');
			},

			changeSlide(index, e) {
				if ( index < document.querySelectorAll('.rulesSlider__slide').length - 1 ) {
					this.currentRulesSlide = index + 1;
				} else {
					this.rulesSliderClose();
					this.currentRulesSlide = 0;
				}
			},

			rulesSliderClose() {
				document.querySelector('#rulesSlider').classList.toggle('show');
			},

			logout() {
				auth.logout();
				router.go('login');
				this.toggleMenu();
				this.loggedIn = false;
			},
		},

		computed: {
		},
	};
</script>