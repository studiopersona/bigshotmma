<template>
    <div>
    	<div v-if="loggedIn" class="appHeader">
            <!--<a v-link="" class="appHeader__backBtn"><img :src="'public/image/icons/back.png'"> Back</a>-->
            <button type="button" @click="toggleMenu" class="appHeader__menuBtn">Menu <img :src="'public/image/menu-icon.png'"></button>
        </div>
        <router-view></router-view>
        <nav class="appDashboard">
        	<header class="dashboardHeader">
                <div class="dashboardHeader__playerWrap clearfix">
                    <img src="public/image/avatar/male.jpg" class="dashboardHeader__avatar">
                    <div class="dashboardHeader__playerName">
                        {{ playersName }}
                        <div class="dashboardHeader__balance">
                            Balance: <span>${{ parseFloat(playersBalance).toFixed(2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="dashboardHeader__logoutWrap">
                    <a class="dashboardHeader__logout" @click.prevent="logout" v-link="{path: '/logout'}">Logout</a>
                </div>
            </header>
        	 <ul class="appDashboard__list clearfix">
        	 	<li class="appDashboard__linkWrap">
        	 		<a class="appDashboard__link appDashboard__link--contests" v-link="{ path: '/player/contests' }" @click="toggleMenu">
        	 			<img src="public/image/dashboard/contests.png">
        	 			Contests
        	 		</a>
        	 	</li>
                <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--contests" v-link="{ path: '/entries/' + playerId }" @click="toggleMenu">
                        <img src="public/image/dashboard/entries.png" style="max-height:42px;">
                        Entries
                    </a>
                </li>
                <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--rules" @click="showContestTypes">
                        <img src="public/image/dashboard/contest-types.png">
                        Contest Types
                    </a>
                </li>
               <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--rules" @click="showHowToPlay">
                        <img src="public/image/dashboard/rules.png">
                        How to Play
                    </a>
                </li>
                <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--eligibility" @click="showEligibility">
                        <img src="public/image/dashboard/eligibility.png">
                        Eligibilty
                    </a>
                </li>
                <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--rules" v-link="{ path: '/deposit' }" @click="toggleMenu">
                        <img src="public/image/dashboard/deposit.png">
                        Make Deposit
                    </a>
                </li>
                <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--rules" v-link="{ path: '/withdrawl-request' }" @click="toggleMenu">
                        <img src="public/image/dashboard/withdraw.png">
                        Withdraw
                    </a>
                </li>
        	 	<li class="appDashboard__linkWrap">
        	 		<a class="appDashboard__link appDashboard__link--profile" v-link="{ path: '/profile' }" @click="toggleMenu">
        	 			<img src="public/image/dashboard/profile.png">
        	 			Profile
        	 		</a>
        	 	</li>
                <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--profile" v-link="{ path: '/dashboard' }" @click="toggleMenu">
                        <img src="public/image/dashboard/profile.png">
                        Dashboard
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
	                <button @click.prevent="changeSlide($index, '.rulesSlider__slide', 'currentRulesSlide', '#rulesSlider', $event)" type="button" class="button button--green">Got It</button>
	            </div>
	            <button @click="sliderClose('#rulesSlider')" type="button" class="alertModal__close">x</button>
	        </div>
	    </section>
        <section id="howToPlaySlider" class="rulesSlider">
            <div :class="['playSlider__slide', currentHowToPlaySlide === 0 ? 'current' : '']">
                <h3 class="rulesSlider__title">How to Play</h3>
                <div class="rulesSlider__icon">
                    <img :src="URL.base + '/public/image/info/how-to-play.png'">
                </div>
                <div class="rulesSlider__description">
                    <p>Pick five (5) fighters by clicking their avatar, then decide how and when each fight will end.</p>
                    <p>Choose up to three (3) power ups to score bonus points.</p>
                </div>
                <div class="rulesSlider__positionIndicator">
                    <span class="rulesSlider__indicator current"></span>
                    <span class="rulesSlider__indicator"></span>
                    <span class="rulesSlider__indicator"></span>
                </div>
                <div class="button-wrap">
                    <button @click.prevent="changeSlide(0, '.playSlider__slide', 'currentHowToPlaySlide', '#howToPlaySlider', $event)" type="button" class="button button--green">Got It</button>
                </div>
                <button @click="sliderClose('#howToPlaySlider')" type="button" class="alertModal__close">x</button>
            </div>
            <div :class="['playSlider__slide', currentHowToPlaySlide === 1 ? 'current' : '']">
                <h3 class="rulesSlider__title">Point System</h3>
                <div class="rulesSlider__description">
                    <p>Points are earned by making correct choices:</p>
                    <div class="rulesSlider__pointSystem">
                        <div class="rulesSlider__pointSystemRow">
                            <div class="rulesSlider__pointSystemItem">
                                &nbsp;
                            </div>
                            <div class="rulesSlider__pointSystemItem">
                                <img :src="URL.base + '/public/image/info/scoring-underdog.png'">
                                <p>Underdog Wins</p>
                            </div>
                            <div class="rulesSlider__pointSystemItem">
                                &nbsp;
                            </div>
                            <div class="rulesSlider__pointSystemItem">
                                <img :src="URL.base + '/public/image/info/scoring-finish.png'">
                                <p>Correct Finish</p>
                            </div>
                            <div class="rulesSlider__pointSystemItem">
                                &nbsp;
                            </div>
                        </div>
                        <div class="rulesSlider__pointSystemRow">
                            <div class="rulesSlider__pointSystemItem">
                                <img :src="URL.base + '/public/image/info/scoring-favorite.png'">
                                <p>Favorite Wins</p>
                            </div>
                            <div class="rulesSlider__pointSystemItem">
                                <img :src="URL.base + '/public/image/info/scoring-round.png'">
                                <p>Correct Round</p>
                            </div>
                            <div class="rulesSlider__pointSystemItem">
                                <img :src="URL.base + '/public/image/info/scoring-minute.png'">
                                <p>Correct Minute</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rulesSlider__positionIndicator">
                    <span class="rulesSlider__indicator"></span>
                    <span class="rulesSlider__indicator current"></span>
                    <span class="rulesSlider__indicator"></span>
                </div>
                <div class="button-wrap">
                    <button @click.prevent="changeSlide(1, '.playSlider__slide', 'currentHowToPlaySlide', '#howToPlaySlider', $event)" type="button" class="button button--green">Got It</button>
                </div>
                <button @click="sliderClose('#howToPlaySlider')" type="button" class="alertModal__close">x</button>
            </div>
            <div :class="['playSlider__slide', currentHowToPlaySlide === 2 ? 'current' : '']">
                <h3 class="rulesSlider__title">Power Ups</h3>
                <div class="rulesSlider__description">
                    <p>Apply up to three (3) per contest, one (1) per fight to score bonus points.</p>
                    <div class="container-fluid powerups-list">
                        <div class="row">
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/bonecrusher.png'">
                                <div style="color: #90a5d4">Bonecrusher</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/cindarella.png'">
                                <div style="color: #dd03ff">Cindarella</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/minuteman.png'">
                                <div style="color: #fd880a">Minuteman</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/slater.png'">
                                <div style="color: #f8d86b">Slater</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/flawless.png'">
                                <div style="color: #03ff1b">Flawless</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/debo.png'">
                                <div style="color: #ce8534">Debo</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/hoyce.png'">
                                <div style="color: #929497">Hoyce</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/denied.png'">
                                <div style="color: #57747a">Denied</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rulesSlider__positionIndicator">
                    <span class="rulesSlider__indicator"></span>
                    <span class="rulesSlider__indicator"></span>
                    <span class="rulesSlider__indicator current"></span>
                </div>
                <div class="button-wrap">
                    <button @click.prevent="changeSlide(2, '.playSlider__slide', 'currentHowToPlaySlide', '#howToPlaySlider', $event)" type="button" class="button button--green">Got It</button>
                </div>
                <button @click="sliderClose('#howToPlaySlider')" type="button" class="alertModal__close">x</button>
            </div>
        </section>
        <section id="eligibility" class="rulesSlider">
            <div :class="['eligibilitySlider__slide', 'current']">
                <h3 class="rulesSlider__title">Eligibilty</h3>
                <div class="rulesSlider__icon">
                    <img :src="URL.base + '/public/image/info/eligibility.png'">
                </div>
                <div class="rulesSlider__description">
                    <p class="smaller-text">Daily fantasy sports (DFS) is legal in 41 US states, but residents of the following states are not eligible to participate in paid entry contests:</p>
                    <p>Alabama, Arizona, Hawaii, Idaho, Iowa, Louisiana, Montana, Nevada and Washington.</p>
                </div>
                <div class="button-wrap">
                    <button @click="sliderClose('#eligibility')" type="button" class="button button--green">Got It</button>
                </div>
                <button @click="sliderClose('#eligibility')" type="button" class="alertModal__close">x</button>
            </div>
        </section>
        <div id="overlay" @click="toggleMenu"></div>
    </div>
</template>

<script>
	//import auth from '../auth';
	import {router} from '../index'
	import auth from '../auth'
    import localforage from 'localforage'

	export default {
		data: function() {
			return {
				contestTypes: [],
				currentRulesSlide: 0,
                currentHowToPlaySlide: 0,
                currentEligibility: 0,
				loggedIn: false,
                playerId: 0,
				playersName: '',
                playersBalance: 0,
                appDashboardClassList: [],
                rulesSliderClassList: [],
                howToPlayClassList: [],
                eligibilityClassList: [],
                overlayClassList: [],
                overlay: {},
				URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
			}
		},

		created() {
            var vm = this

            localforage.getItem('id_token').then(vm.fetch)
        },

		ready() {
            var vm = this;
			//if ( auth.validate() ) this.loggedIn = true;
            auth.initLocalforage();
            localforage.getItem('id_token')
            .then(function(token) {
                var params

                if ( token ) {
                    params = auth.parseToken(token)
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) vm.loggedIn = true
                }
            })
            .catch(function(err) {
                console.log(err)
            });
            this.appDashboardClassList = document.querySelector('.appDashboard').classList
            this.rulesSliderClassList = document.querySelector('#rulesSlider').classList
            this.howToPlayClassList = document.querySelector('#howToPlaySlider').classList
            this.eligibilityClassList = document.querySelector('#eligibility').classList
            this.overlay = document.getElementById('overlay')
            this.overlayClassList = this.overlay.classList
            this.overlay.addEventListener('click', this.toogleMenu, false)
		},

		methods: {

            fetch(token) {
                let params = auth.parseToken(token)
                if (params) {
                    this.playerId = params.sub

                    this.$http.get( URL.base + '/api/v1/contest-types', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(
                        function(response) {
                            this.contestTypes = response.data;
                        },
                        function(err) {
                            console.log(err);
                    })

                    this.$http.get( URL.base + '/api/v1/player-name', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(
                        function(response) {
                            this.playersName = response.data.player_name;
                        },
                        function(err) {
                            console.log(err);
                    })

                    this.$http.get( URL.base + '/api/v1/player-balance', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(
                        function(response) {
                            this.playersBalance = response.data.playerBalance;
                        },
                        function(err) {
                            console.log(err);
                    })
                }
            },

			toggleMenu() {
                var templateWrapClassList

				this.appDashboardClassList.toggle('show')
                this.overlayClassList.toggle('show')
                templateWrapClassList = document.getElementById('templateWrap').classList
                templateWrapClassList.toggle('blur')
			},

			showContestTypes() {
				this.appDashboardClassList.toggle('show')
				this.rulesSliderClassList.toggle('show')
			},

            showHowToPlay() {
                this.appDashboardClassList.toggle('show')
                this.howToPlayClassList.toggle('show')
            },

            showEligibility() {
                this.appDashboardClassList.toggle('show')
                this.eligibilityClassList.toggle('show')
            },

			changeSlide(index, selector, position, sliderSelector, e) {
				if ( index < document.querySelectorAll(selector).length - 1 ) {
					this[position] = index + 1
				} else {
					this.sliderClose(sliderSelector)
					this[position] = 0
				}
			},

			sliderClose(selector) {
				document.querySelector(selector).classList.toggle('show')
                this.appDashboardClassList.toggle('show')
			},

			logout() {
				auth.logout()
				router.go('/login')
				this.toggleMenu()
				this.loggedIn = false
			},
		},

        events: {
            'logged-in': function() {
                this.loggedIn = true
            }
        },
	};
</script>