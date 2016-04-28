<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Fighters {{ playerPicks.length }}/5 Chosen</h1>
            <h4 class="pageHeader__subheader">{{ totalPowerUps }}/3 Power-ups Used</h4>
        </header>
        <div class="fightsList">
            <ul>
                <li class="fightsList__item" v-for="fight in fightsList[0].fights">
                    <div class="container-fluid fightsList__fightersWrap" data-fight-id="{{ fight.id }}">
                        <div class="col-xs-50 fightsList__fighterStatsWarp">
                            <div
                                class="fightsList__clickableArea"
                                @click.stop.prevent="selectFight"
                                data-fighter-id="{{ fight.fighters[0].id }}"
                                data-fight-id="{{ fight.id }}"
                            ></div>
                            <div class="col-xs-40 fightsList__fighterWrap">
                                <div class="fightsList__fighterImgWrap" data-fight-id="{{ fight.id }}">
                                    <img
                                        :class="['fightsList__fighter', (fight.fighters[0].pivot.odds > fight.fighters[1].pivot.odds) ? 'favorite' : '']"
                                        :src="'public/image/fighters/' + fight.fighters[0].fighter_image_name"
                                        alt="{{ fight.fighters[0].firstname }} {{ fight.fighters[0].lastname }} Image"
                                        data-fighter-id="{{ fight.fighters[0].id }}"
                                    >
                                    <img
                                        class="fightsList__powerup left"
                                        :src="'public/image/powerups/' + fightData[fight.id].powerupImage"
                                        data-fighter-id="{{ fight.fighters[0].id}}"
                                        data-fight-id="{{ fight.id }}"
                                        @click="removePowerUp(fight.id, $event)"
                                    >
                                    <div class="fightsList__selectedIndicatorWrap">
                                        <div :class="['fightsList__selectedIndicator', (parseInt(fight.fighters[0].pivot.odds, 10) > parseInt(fight.fighters[1].pivot.odds, 10)) ? 'favorite' : '']" data-fighter-id="{{ fight.fighters[0].id }}">
                                            <span>
                                                {{ (parseInt(fight.fighters[0].pivot.odds, 10) > parseInt(fight.fighters[1].pivot.odds, 10)) ? 'Favorite (+3)' : 'Underdog (+5)' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-60">
                                <div class="fightsList__fighterName">
                                    {{ fight.fighters[0].firstname }} {{ fight.fighters[0].lastname }}
                                </div>
                                <div class="fightsList__fighterHeight">
                                    {{ fight.fighters[0].height_ft }}' {{ fight.fighters[0].height_in }}"
                                    {{ fight.fighters[0].weight_lbs }}lbs.
                                </div>
                                <div class="fightsList__fighterRecord">
                                    {{ fight.fighters[0].wins }} - {{ fight.fighters[0].loses }} - {{ fight.fighters[0].draws }}
                                </div>
                                <div :class="['fightsList__spread', fight.fighters[0].pivot.odds > 0 ? 'favorite' : '']">
                                    {{ fight.fighters[0].pivot.odds }}
                                </div>
                            </div>
                        </div><!-- .fightsList__fighterStatsWarp -->
                        <div class="col-xs-50 fightsList__fighterStatsWarp">
                            <div
                                class="fightsList__clickableArea"
                                @click.stop.prevent="selectFight"
                                data-fighter-id="{{ fight.fighters[1].id }}"
                                data-fight-id="{{ fight.id }}"
                            ></div>
                            <div class="col-xs-60 right">
                                <div class="fightsList__fighterName">
                                    {{ fight.fighters[1].firstname }} {{ fight.fighters[1].lastname }}
                                </div>
                                <div class="fightsList__fighterHeight">
                                    {{ fight.fighters[1].height_ft }}' {{ fight.fighters[1].height_in }}"
                                    {{ fight.fighters[1].weight_lbs }}lbs.
                                </div>
                                <div class="fightsList__fighterRecord">
                                    {{ fight.fighters[1].wins }} - {{ fight.fighters[1].loses }} - {{ fight.fighters[1].draws }}
                                </div>
                                <div :class="['fightsList__spread', fight.fighters[1].pivot.odds > 0 ? 'favorite' : '']">
                                    {{ fight.fighters[1].pivot.odds }}
                                </div>
                            </div>
                            <div class="col-xs-40  fightsList__fighterWrap">
                                <div class="fightsList__fighterImgWrap" data-fight-id="{{ fight.id }}">
                                    <img
                                        :class="['fightsList__fighter', (parseInt(fight.fighters[1].pivot.odds, 10) > parseInt(fight.fighters[0].pivot.odds, 10)) ? 'favorite' : '']"
                                        :src="'public/image/fighters/' + fight.fighters[1].fighter_image_name"
                                        alt="{{ fight.fighters[1].firstname }} {{ fight.fighters[1].lastname }} Image"
                                        data-fighter-id="{{ fight.fighters[1].id }}"
                                    >
                                    <img
                                        class="fightsList__powerup right"
                                        :src="'public/image/powerups/' + fightData[fight.id].powerupImage"
                                        data-fighter-id="{{ fight.fighters[1].id}}"
                                        data-fight-id="{{ fight.id }}"
                                        @click="removePowerUp(fight.id, $event)"
                                    >
                                     <div class="fightsList__selectedIndicatorWrap">
                                        <div
                                            :class="['fightsList__selectedIndicator', (fight.fighters[1].pivot.odds > fight.fighters[0].pivot.odds) ? 'favorite' : '']"
                                            data-fighter-id="{{ fight.fighters[1].id }}">
                                            <span>
                                                {{ (parseInt(fight.fighters[1].pivot.odds, 10) > parseInt(fight.fighters[0].pivot.odds, 10)) ? 'Favorite (+3)' : 'Underdog (+5)' }}
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- .fightsList__fighterImgWrap -->
                            </div><!-- .fightsList__fighterWrap -->
                        </div><!-- .fightsList__fighterStatsWarp -->
                        <button @click="clearFight(fight.id, $event)"
                                type="button"
                                class="fightsList__clearButton"
                                >X<span class="visuallyhidden">Clear</span>
                        </button>
                    </div><!-- .fightsList__fightersWrap -->
                    <div class="fightsList__pick" id="{{ fight.id }}" data-fight-id="{{ fight.id }}">
                        <div class="container-fluid">
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">How will {{ currentFighterName }} win?</div>
                            </div>
                            <div class="col-xs-100">
                                <select v-model="fightData[fight.id].finishId">
                                    <option value="0">Choose Finish (+5)</option>
                                    <option v-for="finish in finishes" value="{{ finish.id }}">{{ finish.name }} (+{{ finish.points }})</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <select v-model="fightData[fight.id].round">
                                    <option value="0">Choose Round (+2)</option>
                                    <option value="1">Round 1 (+2)</option>
                                    <option value="2">Round 2 (+2)</option>
                                    <option value="3">Round 3 (+2)</option>
                                    <option value="4">Round 4 (+2)</option>
                                    <option value="5">Round 5 (+2)</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <select v-model="fightData[fight.id].minute">
                                    <option value="0">Choose Minute (+1)</option>
                                    <option value="1">Minute 1 (+1)</option>
                                    <option value="2">Minute 2 (+1)</option>
                                    <option value="3">Minute 3 (+1)</option>
                                    <option value="4">Minute 4 (+1)</option>
                                    <option value="5">Minute 5 (+1)</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">Power-up (optional)</div>
                            </div>
                            <div class="col-xs-100 powerUpsList">
                                <div class="col-xs-23 col-xs-offset-2" v-for="powerUp in powerUps">
                                    <button class="powerUpList__btn" type="button">
                                        <span class="visuallyhidden">{{ powerUp.name }}</span>
                                        <img
                                            :src="'public/image/powerups/' + powerUp.image_name"
                                            @click="confirmPowerUp"
                                            data-power-up="{{ powerUp.power_up_id }}"
                                            data-fight-id="{{ fight.id }}"
                                            alt="{{ powerUp.name }} Image"
                                        >
                                        <span :style="{color: powerUp.color, fontSize: '0.9rem'}">{{ powerUp.name }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-100 button-wrap">
                                <button @click="closeFight(fight.id, $event)"
                                        type="button"
                                        class="button button--green"
                                >Done</button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        @click="commitPicks"
                    >Save Picks</button>
                </div>
            </div>
            <div :class="loaderClasses">
                <div class="js-global-loader loader">
                    <svg viewBox="0 0 32 32" width="32" height="32">
                        <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
                    </svg>
                </div>
            </div>
        </div>
        <section :class="powerUpModalClasses">
            <h3 class="powerUpModal__title" :style="{color: selectedPowerUp.color}">{{ selectedPowerUp.title }}</h3>
            <img class="powerUpModal__image" :src="'public/image/powerups/' + selectedPowerUp.image_name" alt="{{ selectedPowerUp.title }}">
            <div class="powerUpModal__description">
                {{{ selectedPowerUp.description }}}
            </div>
            <div class="powerUpModal__points" :style="{color: selectedPowerUp.color}">
                +{{ selectedPowerUp.bonus_points }} points
            </div>
            <div class="powerUpModal__apply">
                <p class="powerUpModal__apply--big">Apply this power up?</p>
                <p :style="{color: selectedPowerUp.color}">Failure results in a -{{ selectedPowerUp.penalty_points }} penalty</p>
            </div>
            <div class="powerUpModal__confirm">
                <button @click="powerUpModalClose" class="powerUpModal__confirm--no">No</button>
                <button @click="selectPowerUp(selectedPowerUp.fightId, selectedPowerUp.id, selectedPowerUp.image_name, $event)" class="powerUpModal__confirm--yes">Yes</button>
            </div>
            <button @click="powerUpModalClose" type="button" class="powerUpModal__close">x</button>
        </section>
        <section :class="alertNoticeClasses">
            <div>
                <h2 class="alertNotice__header">{{ alertNotice.header }}</h2>
                <div class="alertNotice__subject">{{ alertNotice.subject }}</div>
                <div class="alertNotice__body">
                    {{{ alertNotice.body }}}
                </div>
                <button @click="alertNoticeClose" type="button" class="alertModal__close">x</button>
            </div>
            <div v-if="alertNotice.action" class="button-wrap">
                <button @click="alertNoticeClose" type="button" class="button button--green">Got It</button>
            </div>
        </section>
    </div>
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';
    export default {

        props: ['working'],

        data() {
            return {
                fightsList: [{
                    fights: [],
                    event: {
                        event_short_name: '',
                    },
                }],
                powerUps: {},
                finishes: {},
                working: false,
                powerUpModalClasses: ['powerUpModal'],
                fightData: [],
                contestId: this.$route.params.contest_id,
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            if ( ! auth.validate() ) {
                this.tokenRefresh();
            } else {
                this.fetch();
            }
        },

        methods: {
            tokenRefresh() {
                var vm = this;

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    localStorage.setItem('id_token', response.data.token);
                    vm.fetch();
                }, function(err) {
                    router.go('login');
                });
            },

            fetch() {
                this.$http.get(URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/fights', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.fightsList = response.data.fights;
                    this.working = false;
                }, function(err) {
                    console.log(err);
                });

                this.$http.get(URL.base + '/api/v1/power-ups', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.powerUps = response.data;
                });

                this.$http.get(URL.base + '/api/v1/finishes', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.finishes = response.data;
                });
            },

            findPowerUp(powerUp) {
                return powerUp.power_up_id === parseInt(this.powerUpId, 10);
            },

            selectFight(e) {

            },

            switchFight(e) {
                var fightToClose,
                    fightToOpen;

                fightToClose = document.querySelector('div.fightsList__pick[data-fight-id="' + this.currentFightId + '"]');
                if ( fightToClose ) fightToClose.classList.toggle('show');

                fightToOpen = document.querySelector('div.fightsList__pick[data-fight-id="' + e.target.dataset.fightId + '"]');
                fightToOpen.classList.toggle('show');
                // update currentFightId
                this.currentFightId = e.target.dataset.fightId;
            },

            closeFight(fightId) {
                var fightPickEl;

                fightPickEl = document.querySelector('div.fightsList__pick[data-fight-id="' + fightId + '"]');
                fightPickEl.classList.toggle('show');

                this.currentFightId = '';
                this.currentFighterId = '';
            },

        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },
    };
</script>