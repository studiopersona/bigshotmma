<template>
    <div>
    	<div v-if="loggedIn" class="appHeader">
            <!--<a v-link="" class="appHeader__backBtn"><img :src="'public/image/icons/back.png'"> Back</a>-->
            <button type="button" @click="toggleMenu" class="appHeader__menuBtn">Dashboard <img :src="'public/image/menu-icon.png'"></button>
        </div>
        <router-view></router-view>
        <nav class="appDashboard">
        	<header class="dashboardHeader">
                <div class="dashboardHeader__playerWrap clearfix">
                    <img src="public/image/avatar/male.jpg" class="dashboardHeader__avatar">
                    <div class="dashboardHeader__playerName">
                        {{ playersName }}
                        <div class="dashboardHeader__balance">
                            Balance: <span>${{ playersBalance }}</span>
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
                    <a class="appDashboard__link appDashboard__link--rules" @click="showHowToPlay">
                        <img src="public/image/dashboard/rules.png">
                        How to Play
                    </a>
                </li>
        	 	<!--<li class="appDashboard__linkWrap">
        	 		<a class="appDashboard__link appDashboard__link--rules" @click="showContestTypes">
        	 			<img src="public/image/dashboard/contest-types.png">
        	 			Contest Types
        	 		</a>
        	 	</li>
                <li class="appDashboard__linkWrap">
                    <a class="appDashboard__link appDashboard__link--rules" v-link="{ path: '/deposit' }" @click="toggleMenu">
                        <img src="public/image/dashboard/deposit.png">
                        Make Deposit
                    </a>
                </li>-->
        	 	<li class="appDashboard__linkWrap">
        	 		<a class="appDashboard__link appDashboard__link--profile" v-link="{ path: '/profile' }" @click="toggleMenu">
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
	                <button @click.prevent="changeSlide($index, '.rulesSlider__slide', 'currentRulesSlide', '#rulesSlider', $event)" type="button" class="button button--green">Got It</button>
	            </div>
	            <button @click="sliderClose('#rulesSlider')" type="button" class="alertModal__close">x</button>
	        </div>
	    </section>
        <section id="howToPlaySlider" class="rulesSlider">
            <div :class="['playSlider__slide', currentHowToPlaySlide === 0 ? 'current' : '']">
                <h3 class="rulesSlider__title">How to Play</h3>
                <div class="rulesSlider__icon">
                    <img :src="URL.base + '/public/image/info/fighter.png'">
                </div>
                <div class="rulesSlider__description">
                    <p>Each player picks a total of five (5) fighters from the event lineup and decides how &amp; when each fight will end.</p>
                    <!--<p>In the event of an injury, one "Reserve" fighter is set as a backup.</p>-->
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
                <h3 class="rulesSlider__title">Scoring</h3>
                <div class="rulesSlider__description">
                    <p>Points are earned by making correct choices:</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Result</th>
                                <th>Choices</th>
                                <th class="center">Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Winning Fighter</td>
                                <td>Underdog<br>Favorite</td>
                                <td class="points"><strong>5<br>3</strong></td>
                            </tr>
                            <tr>
                                <td>Correct Finish</td>
                                <td>TKO/KO, Submission<br>Decision</td>
                                <td class="points"><strong>10<br>7</strong></td>
                            </tr>
                            <tr>
                                <td>Correct Round</td>
                                <td>1,2,3 (4,5 if applicable)</td>
                                <td class="points"><strong>2</strong></td>
                            </tr>
                            <tr>
                                <td>Correct Minute</td>
                                <td>1,2,3,4,5</td>
                                <td class="points"><strong>1</strong></td>
                            </tr>
                            <tr>
                                <td>Draw</td>
                                <td>Should never happen</td>
                                <td class="points"><strong>0</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    <!--<p>In the event of an injury, one "Reserve" fighter is set as a backup.</p>-->
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
                                <img :src="URL.base + '/public/image/powerups/danielsan.png'">
                                <div style="color: #ff2203">Danielsan</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/minuteman.png'">
                                <div style="color: #fd880a">Minuteman</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/slater.png'">
                                <div style="color: #f8d86b">Slater</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/bunyan.png'">
                                <div style="color: #0377ff">Bunyan</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/flawless.png'">
                                <div style="color: #03ff1b">Flawless</div>
                            </div>
                            <div class="col-xs-25">
                                <img :src="URL.base + '/public/image/powerups/debo.png'">
                                <div style="color: #ce8534">Debo</div>
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
    </div>
</template>

<script>
	//import auth from '../auth';
	import {router} from '../index';
	import auth from '../auth';
    import localforage from 'localforage';

	export default {
		data: function() {
			return {
				contestTypes: [],
				currentRulesSlide: 0,
                currentHowToPlaySlide: 0,
				loggedIn: false,
				playersName: '',
                playersBalance: 0,
                appDashboardClassList: [],
                rulesSliderClassList: [],
                howToPlayClassList: [],
				URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
			};
		},

		created() {
            var vm = this;

            localforage.getItem('id_token').then(vm.fetch);
        },

		ready() {
            var vm = this;
			//if ( auth.validate() ) this.loggedIn = true;
            auth.initLocalforage();
            localforage.getItem('id_token')
            .then(function(token) {
                var params;

                if ( token ) {
                    params = auth.parseToken(token)
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) vm.loggedIn = true;
                }
            })
            .catch(function(err) {
                console.log(err);
            });
            this.appDashboardClassList = document.querySelector('.appDashboard').classList;
            this.rulesSliderClassList = document.querySelector('#rulesSlider').classList;
            this.howToPlayClassList = document.querySelector('#howToPlaySlider').classList;
		},

		methods: {

            fetch(token) {
                this.$http.get( URL.base + '/api/v1/contest-types', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(
                    function(response) {
                        this.contestTypes = response.data;
                    },
                    function(err) {
                        console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/player-name', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(
                    function(response) {
                        this.playersName = response.data.player_name;
                    },
                    function(err) {
                        console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/player-balance', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(
                    function(response) {
                        this.playersBalance = response.data.playerBalance;
                    },
                    function(err) {
                        console.log(err);
                });
            },

			toggleMenu() {
				this.appDashboardClassList.toggle('show');
			},

			showContestTypes() {
				this.appDashboardClassList.toggle('show');
				this.rulesSliderClassList.toggle('show');
			},

            showHowToPlay() {
                this.appDashboardClassList.toggle('show');
                this.howToPlayClassList.toggle('show');
            },

			changeSlide(index, selector, position, sliderSelector, e) {
				if ( index < document.querySelectorAll(selector).length - 1 ) {
					this[position] = index + 1;
				} else {
					this.sliderClose(sliderSelector);
					this[position] = 0;
				}
			},

			sliderClose(selector) {
				document.querySelector(selector).classList.toggle('show');
                this.appDashboardClassList.toggle('show');
			},

			logout() {
				auth.logout();
				router.go('login');
				this.toggleMenu();
				this.loggedIn = false;
			},
		},
	};
</script>