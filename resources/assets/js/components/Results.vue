<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Event Results</h1>
            <h4 class="pageHeader__subheader">{{ event.event_name }}</h4>
        </header>
        <div v-if="! resultsList.length">
            <div class="fightsList">
                <p>No event results have been recorded at this time.</p>
                <div :class="loaderClasses">
                    <div class="js-global-loader loader">
                        <svg viewBox="0 0 32 32" width="32" height="32">
                            <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div class="resultsList">
                <ul>
                    <li class="fightsList__item resultsList__item" @click.prevent="toggleFight(result.fightResults.fight_id)" v-for="result in resultsList">
                        <div class="container-fluid fightsList__fightersWrap" data-fight-id="{{ result.fightResults.fight_id }}">
                            <div class="col-xs-50 fightsList__fighterStatsWarp">
                                <div v-if="parseInt(result.fightResults.finish_id, 10) === 4" class="col-xs-40 fightsList__fighterWrap">
                                    <div class="fightsList__fighterImgWrap" data-fight-id="{{ result.fightResults.fight_id }}">
                                        <img
                                            :class="['fightsList__fighter']"
                                            :src="'public/image/fighters/' + result.fightResults.fight.fighters[0].fighter_image_name"
                                            alt="{{ result.fightResults.fight.fighters[0].firstname }} {{ result.fightResults.fight.fighters[0].lastname }} Image"
                                            data-fighter-id="{{ result.fightResults.fight.fighters[0].id }}"
                                        >
                                        <div class="fightsList__selectedIndicatorWrap">
                                            <div :class="['fightsList__selectedIndicator']" data-fighter-id="{{ result.fightResults.fight.fighters[0].id }}">
                                                <span>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else class="col-xs-40 fightsList__fighterWrap">
                                    <div class="fightsList__fighterImgWrap" data-fight-id="{{ result.fightResults.fight_id }}">
                                        <img
                                            :class="['fightsList__fighter', (result.fightResults.fight.fighters[0].pivot.odds < result.fightResults.fight.fighters[1].pivot.odds) ? 'favorite' : '', (parseInt(result.fightResults.fight.fighters[0].id, 10) === parseInt(result.fightResults.winning_fighter_id, 10)) ? 'selected' : '']"
                                            :src="'public/image/fighters/' + result.fightResults.fight.fighters[0].fighter_image_name"
                                            alt="{{ result.fightResults.fight.fighters[0].firstname }} {{ result.fightResults.fight.fighters[0].lastname }} Image"
                                            data-fighter-id="{{ result.fightResults.fight.fighters[0].id }}"
                                        >
                                        <div class="fightsList__selectedIndicatorWrap">
                                            <div :class="['fightsList__selectedIndicator', (parseInt(result.fightResults.fight.fighters[0].pivot.odds, 10) < parseInt(result.fightResults.fight.fighters[1].pivot.odds, 10)) ? 'favorite' : '', (parseInt(result.fightResults.fight.fighters[0].id, 10) === parseInt(result.fightResults.winning_fighter_id, 10)) ? 'show' : '']" data-fighter-id="{{ result.fightResults.fight.fighters[0].id }}">
                                                <span>
                                                    Winner
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-60">
                                    <div class="fightsList__fighterName">
                                        {{ result.fightResults.fight.fighters[0].firstname }} {{ result.fightResults.fight.fighters[0].lastname }}
                                    </div>
                                    <div class="fightsList__fighterHeight">
                                        {{ result.fightResults.fight.fighters[0].height_ft }}' {{ result.fightResults.fight.fighters[0].height_in }}"
                                        {{ result.fightResults.fight.fighters[0].weight_lbs }}lbs.
                                    </div>
                                    <div class="fightsList__fighterRecord">
                                        {{ result.fightResults.fight.fighters[0].wins }} - {{ result.fightResults.fight.fighters[0].loses }} - {{ result.fightResults.fight.fighters[0].draws }}
                                    </div>
                                    <div :class="['fightsList__spread', result.fightResults.fight.fighters[0].pivot.odds < 0 ? 'favorite' : '']">
                                        {{ result.fightResults.fight.fighters[0].pivot.odds > 0 ? '+' : '' }}{{ result.fightResults.fight.fighters[0].pivot.odds }}
                                    </div>
                                </div>
                            </div><!-- .fightsList__fighterStatsWarp -->
                            <div class="col-xs-50 fightsList__fighterStatsWarp">
                                <div class="col-xs-60 right">
                                    <div class="fightsList__fighterName">
                                        {{ result.fightResults.fight.fighters[1].firstname }} {{ result.fightResults.fight.fighters[1].lastname }}
                                    </div>
                                    <div class="fightsList__fighterHeight">
                                        {{ result.fightResults.fight.fighters[1].height_ft }}' {{ result.fightResults.fight.fighters[1].height_in }}"
                                        {{ result.fightResults.fight.fighters[1].weight_lbs }}lbs.
                                    </div>
                                    <div class="fightsList__fighterRecord">
                                        {{ result.fightResults.fight.fighters[1].wins }} - {{ result.fightResults.fight.fighters[1].loses }} - {{ result.fightResults.fight.fighters[1].draws }}
                                    </div>
                                    <div :class="['fightsList__spread', result.fightResults.fight.fighters[1].pivot.odds < 0 ? 'favorite' : '']">
                                        {{ result.fightResults.fight.fighters[1].pivot.odds > 0 ? '+' : '' }}{{ result.fightResults.fight.fighters[1].pivot.odds }}
                                    </div>
                                </div>
                                <div v-if="parseInt(result.fightResults.finish_id, 10) === 4" class="col-xs-40  fightsList__fighterWrap">
                                    <div class="fightsList__fighterImgWrap" data-fight-id="{{ result.fightResults.id }}">
                                        <img
                                            :class="['fightsList__fighter']"
                                            :src="'public/image/fighters/' + result.fightResults.fight.fighters[1].fighter_image_name"
                                            alt="{{ result.fightResults.fight.fighters[1].firstname }} {{ result.fightResults.fight.fighters[1].lastname }} Image"
                                            data-fighter-id="{{ result.fightResults.fight.fighters[1].id }}"
                                        >
                                         <div class="fightsList__selectedIndicatorWrap">
                                            <div
                                                :class="['fightsList__selectedIndicator']"
                                                data-fighter-id="{{ result.fightResults.fight.fighters[1].id }}">
                                                <span>
                                                    Winner
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- .fightsList__fighterImgWrap -->
                                </div><!-- .fightsList__fighterWrap -->
                                <div v-else class="col-xs-40  fightsList__fighterWrap">
                                    <div class="fightsList__fighterImgWrap" data-fight-id="{{ result.fightResults.id }}">
                                        <img
                                            :class="['fightsList__fighter', (parseInt(result.fightResults.fight.fighters[1].pivot.odds, 10) < parseInt(result.fightResults.fight.fighters[0].pivot.odds, 10)) ? 'favorite' : '', (parseInt(result.fightResults.fight.fighters[1].id, 10) === parseInt(result.fightResults.winning_fighter_id, 10)) ? 'selected' : '']"
                                            :src="'public/image/fighters/' + result.fightResults.fight.fighters[1].fighter_image_name"
                                            alt="{{ result.fightResults.fight.fighters[1].firstname }} {{ result.fightResults.fight.fighters[1].lastname }} Image"
                                            data-fighter-id="{{ result.fightResults.fight.fighters[1].id }}"
                                        >
                                         <div class="fightsList__selectedIndicatorWrap">
                                            <div
                                                :class="['fightsList__selectedIndicator', (result.fightResults.fight.fighters[1].pivot.odds < result.fightResults.fight.fighters[0].pivot.odds) ? 'favorite' : '', (parseInt(result.fightResults.fight.fighters[1].id, 10) === parseInt(result.fightResults.winning_fighter_id, 10)) ? 'show' : '']"
                                                data-fighter-id="{{ result.fightResults.fight.fighters[1].id }}">
                                                <span>
                                                    Winner
                                                </span>
                                            </div>
                                        </div>
                                    </div><!-- .fightsList__fighterImgWrap -->
                                </div><!-- .fightsList__fighterWrap -->
                            </div><!-- .fightsList__fighterStatsWarp -->
                        </div><!-- .fightsList__fightersWrap -->
                        <div v-if="parseInt(result.fightResults.finish_id, 10) === 4" class="resultsList__outcomeString left">
                            {{ result.fightResults.fight.fighters[0].lastname }} vs. {{ result.fightResults.fight.fighters[1].lastname }} declared a draw
                        </div>
                        <div v-else :class="['resultsList__outcomeString', (parseInt(result.fightResults.fight.fighters[0].id, 10) === parseInt(result.fightResults.winning_fighter_id, 10)) ? 'right' : 'left']">
                            {{ parseInt(result.fightResults.fight.fighters[0].id, 10) === parseInt(result.fightResults.winning_fighter_id, 10) ? result.fightResults.fight.fighters[0].lastname : result.fightResults.fight.fighters[1].lastname }}
                            wins via
                            {{ result.fightResults.finish.abbr }}
                            in Round {{ result.fightResults.round }}
                            at {{ result.roundTime }}
                        </div>
                        <div class="fightsList__pick closed" id="{{ result.fightResults.id }}" data-fight-id="{{ result.fightResults.fight_id }}">
                            <div class="container-fluid">
                                <!--<div class="col-xs-100 fightPicksList__row">
                                    <div class="col-xs-10 fightPicksList__icon">
                                        <img src="public/image/icons/star.png">
                                    </div>
                                    <div class="col-xs-90">
                                        <h4 v-if="(10 > 0)" class="fightPicksList__selectionTitle">Favorite Wins</h4>
                                        <h4 v-else  class="fightPicksList__selectionTitle">Underdog Wins</h4>
                                    </div>
                                </div>
                                <!-- finish row -->
                                <!--<div class="col-xs-100 fightPicksList__row">
                                    <div class="col-xs-10 fightPicksList__icon">
                                        <img src="public/image/icons/fist.png">
                                    </div>
                                    <div class="col-xs-90">
                                        <h4 class="fightPicksList__selectionTitle">Won by a {{ result.fightResults.finish.finish_name }}</h4>
                                    </div>
                                </div>
                                <!-- round row -->
                                <!--<div class="col-xs-100 fightPicksList__row">
                                    <div class="col-xs-10 fightPicksList__icon">
                                        <img src="public/image/icons/bell.png">
                                    </div>
                                    <div class="col-xs-90">
                                        <h4 class="fightPicksList__selectionTitle">Won in Round {{ numberNames[parseInt(result.fightResults.round, 10) - 1] }}</h4>
                                    </div>
                                </div>
                                <!-- minute row -->
                                <!--<div class="col-xs-100 fightPicksList__row">
                                    <div class="col-xs-10 fightPicksList__icon">
                                        <img src="public/image/icons/stopwatch.png">
                                    </div>
                                    <div class="col-xs-90">
                                        <h4 class="fightPicksList__selectionTitle">Won in Minute {{ numberNames[parseInt(result.fightResults.minute, 10) - 1] }}</h4>
                                    </div>
                                </div>
                                <!-- power up row -->
                                <div v-for="fighter in result.fightResults.fight.fighters" class="col-xs-100 fightPicksList__row">
                                    <div class="col-xs-100 resultsList__powerUpsTitle">
                                        Power Ups Achieved by {{ fighter.lastname }}
                                    </div>
                                    <div v-if="! fighter.powerupsAchieved.length" class="col-xs-100 resultsList__noPowerUps">
                                        {{ fighter.lastname }} did not achieve any power ups during this match.
                                    </div>
                                    <div v-else v-for="powerup in fighter.powerupsAchieved" :class="['col-xs-20', 'resultsList__powerUps']">
                                        <img :src="URL.base + '/public/image/powerups/' + powerup.power_up_image_name">
                                        <div :style="{color: powerup.power_up_color}">{{ powerup.power_up_name }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div v-if="hasPicks" class="container-fluid">
                    <div class="col-xs-100 button-wrap">
                        <a v-link="{ path: '/contest/' + contestId + '/picks' }" class="button button--primary">My Picks</a>
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
        </div>
    </div>
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

    export default {

        props: ['working'],

        data() {
            return {
                resultsList: [],
                event: {},
                powerUps: {},
                finishes: {},
                contestId: 0,
                numberNames: ['One', 'Two', 'Three', 'Four', 'Five'],
                contestId: this.$route.params.contest_id,
                hasPicks: true,
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
            var vm = this,
                params;

            localforage.getItem('id_token').then(function(token) {
                if ( token ) {
                    params = auth.parseToken(token);
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                        vm.fetch(token);
                    } else {
                        vm.tokenRefresh(token);
                    }
                } else {
                    router.go('login');
                }
            })
            .catch(function(err) {
                console.log(err);
            });
        },

        methods: {
            tokenRefresh(token) {
                var vm = this;

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    localforage.setItem('id_token', response.data.token).then(function() {
                        vm.fetch(token);
                    });
                }, function(err) {
                    router.go('login');
                });
            },

            fetch(token) {
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/results', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    this.resultsList = response.data.results;
                    // console.log(response.data.results);
                    this.event = response.data.event;
                    this.contestId = response.data.contest.contest_id;
                    this.working = false;
                    // console.log(response.data.results[0].fightResults.fight.fighters);
                }, function(err) {
                    this.working = false;
                    console.log(err);
                });

                this.$http.get(URL.base + '/api/v1/power-ups', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    this.powerUps = response.data;
                });

                this.$http.get(URL.base + '/api/v1/finishes', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    this.finishes = response.data;
                });

                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/check-for-picks', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    if ( ! response.data.count ) this.hasPicks = false
                })
                .catch(function(err) {
                    if ( err.status === 404 ) this.hasPicks = false
                })
            },

            findPowerUp(powerUp) {
                return powerUp.power_up_id === parseInt(this.powerUpId, 10);
            },

            toggleFight(fightId) {
                document.querySelector('div.fightsList__pick[data-fight-id="' + fightId + '"]').classList.toggle('closed');
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },
    };
</script>