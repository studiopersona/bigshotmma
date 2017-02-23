<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">{{ $root.playersName }}'s Picks</h1>
            <h4 class="pageHeader__subheader">{{ picksList[0].event.event_short_name }} / <a v-link="{ path: '/contest/' + picksList[0].contest.id + '/players' }">Standings</a>  </h4>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Entry Fee:</span> ${{ picksList[0].contest.buy_in.toFixed(2) }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Standing:</span> <span v-if="results.length">{{ playerRanking }}/{{ standings.length }}</span>
                        <span v-else>N/A</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Prize Pool:</span> ${{ (picksList[0].contest.buy_in * picksList[0].contest.max_participants * 0.85).toFixed(2)  }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Your Winnings:</span> $0.00
                    </div>
                </div>
            </div>
        </div>

        <div v-if="picksList.length === 0" class="fightPicksList">
            <p>You did not make any picks for this contest.</p>
        </div>
        <div v-else class="fightPicksList">
            <ul class="stripped-list">
                <li
                    class="fightPicksList__item closed"
                    @click.prevent="toggleDetails(pick.pick_id)"
                    v-for="pick in picksList"
                    data-pick-id="{{ pick.pick_id }}"
                >
                    <div class="container-fluid">
                        <div class="col-xs-15">
                            <img class="fightPicksList__image" :src="'public/image/fighters/' +  pick.fighter.image">
                        </div>
                        <div class="col-xs-40">
                            <div class="fightPicksList__fighterName">
                                {{ pick.fighter.name }}
                            </div>
                            <div :class="['fightPicksList__odds', (parseInt(pick.fighter.odds, 10) < 0) ? 'favorite' : '' ]">
                                {{ (parseInt(pick.fighter.odds, 10) < 0) ? '' : '+' }}{{ pick.fighter.odds  }}
                            </div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Result</div>
                            <div v-if="!results.length" class="fightPicksList__stat">--</div>
                            <div v-else :class="['fightPicksList__stat', outcome[pick.fight_id].fighter ? 'correct' : '']">{{ outcome[pick.fight_id].fighter ? 'W' : 'L' }}</div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Finish</div>
                            <div v-if="!results.length" class="fightPicksList__stat">--</div>
                            <div v-else :class="['fightPicksList__stat', outcome[pick.fight_id].finish_id ? 'correct' : '']">{{ outcome[pick.fight_id].finish_abbr }}</div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Points</div>
                            <div v-if="!results.length" class="fightPicksList__stat">--</div>
                            <div v-else :class="['fightPicksList__stat', outcome[pick.fight_id].points > 0 ? 'correct' : '']">{{ outcome[pick.fight_id].total_points }}</div>
                        </div>
                    </div>
                    <div class="fightPicksList__details">
                        <div class="container-fluid">
                            <div v-if="!results.length" class="col-xs-100 fightPicksList__resultString">
                                Fight Results Not Available
                            </div>
                            <div v-else class="col-xs-70 col-xs-push-15 fightPicksList__resultString">
                                {{ pick.fighter.name }} <span v-if="outcome[pick.fight_id].fighter">wins</span><span v-else>loses</span> via {{ outcome[pick.fight_id].finish_abbr }}
                                in Round {{ outcome[pick.fight_id].roundResult }} at {{ outcome[pick.fight_id].roundTime }}
                            </div>
                            <div class="col-xs-100 fightPicksList__choicesTitle">
                                Your Choices
                            </div>
                            <!-- figher row -->
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/star.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 v-if="(parseInt(pick.fighter.odds, 10) < 0)" class="fightPicksList__selectionTitle">Favorite to Win</h4>
                                    <h4 v-else  class="fightPicksList__selectionTitle">Underdog to Win</h4>
                                    <p class="fightPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].fighter">You chose the winning fighter</span>
                                        <span v-else>You didn't chose the winning figher</span>
                                    </p>
                                    <p class="fightPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    --
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].fighter ? 'correct' : '']">
                                    {{ outcome[pick.fight_id].fighterPoints > 0 ? '+' : ''}}{{ outcome[pick.fight_id].fighterPoints }}
                                </div>
                            </div>
                            <!-- finish row -->
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/fist.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">{{ pick.finish.finish_type }}</h4>
                                    <p class="fightPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].finish_id">You chose the winning finish</span>
                                        <span v-else>You didn't choose the winning finish</span>
                                    </p>
                                    <p class="fightPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    --
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].finish_id ? 'correct' : '']">
                                    <span>{{ outcome[pick.fight_id].finish_points > 0 ? '+' : ''}}{{ outcome[pick.fight_id].finish_points }}</span>
                                </div>
                            </div>
                            <!-- round row -->
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/bell.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">Round {{ numberNames[parseInt(pick.round.selected, 10) - 1] }}</h4>
                                    <p class="fightPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].round">You chose the winning round</span>
                                        <span v-else>You didn't choose the winning round</span>
                                    </p>
                                    <p class="fightPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    --
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].round ? 'correct' : '']">
                                    <span>{{ outcome[pick.fight_id].round_points > 0 ? '+' : ''}}{{ outcome[pick.fight_id].round_points  }}</span>
                                </div>
                            </div>
                            <!-- minute row -->
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/stopwatch.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">Minute {{ numberNames[parseInt(pick.minute.selected, 10) - 1] }}</h4>
                                    <p class="fightPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].minute">You chose the winning minute</span>
                                        <span v-else>You didn't chose the winning minute</span>
                                    </p>
                                    <p class="fightPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    --
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].minute ? 'correct' : '']">
                                    <span>{{ outcome[pick.fight_id].minute_points > 0 ? '+' : ''}}{{ outcome[pick.fight_id].minute_points }}</span>
                                </div>
                            </div>
                            <!-- power up row -->
                            <div v-if="pick.power_up.image" class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img :src="'public/image/powerups/' + pick.power_up.image">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__powerUpName" :style="{color: pick.power_up.color}">{{ pick.power_up.power_up_name }}</h4>
                                    <p class="fightPicksList__selectionResults" v-show="results.length">
                                        <span v-if="parseInt(outcome[pick.fight_id].power_up_points, 10) > 0">You chose a winning power up</span>
                                        <span v-else>Your fighter did not achieve the power up</span>
                                    </p>
                                    <p class="fightPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points" :style="{color: pick.power_up.color}">
                                    --
                                </div>
                                <div    v-else :class="['col-xs-15', 'fightPicksList__points', parseInt(outcome[pick.fight_id].power_up_points, 10) > 0 ? 'correct' : 'penalty']" style="{color: pick.power_up.color}">
                                    {{ outcome[pick.fight_id].power_up_points > 0 ? '+' : ''}}{{ outcome[pick.fight_id].power_up_points }}
                                </div>
                            </div>
                            <!-- ponits total -->
                            <div class="col-xs-100 fightPicksList__totalWrap">
                                <div class="col-xs-85 fightPicksList__totalTitle">
                                    Total
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__totalValue">
                                    --
                                </div>
                                <div v-else class="col-xs-15 fightPicksList__totalValue">
                                    {{ outcome[pick.fight_id].total_points }}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-if="results.length" class="playerSummary">
                <div class="playerSummary__itemsWrap">
                    <div class="playerSummary__recordTitle">Record:</div>
                    <div class="playerSummary__recordWrap">
                        <span class="playerSummary__record--win">Won {{ playerRecord.wins }}</span> / <span class="playerSummary__record--lose">Lost {{ playerRecord.loses }}</span>
                    </div>
                </div>
                <div class="playerSummary__totalWrap">
                    <div class="playerSummary__totalPointsTitle">Total Points</div>
                    <div class="playerSummary__total">{{ playerRecord.totalPoints }}</div>
                </div>
            </div>
            <div v-if="results.length" class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        v-link="{ path: '/contest/' + picksList[0].contest.id + '/players' }"
                    >Standings</button>
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
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

    export default {

        props: ['working'],

        data() {
            return {
                picksList: [{
                    event: {
                        event_short_name: '',
                    },
                    contest: {
                        buy_in: '',
                        max_participants: '',
                        prize_pool: '',
                    },
                }],
                results: [],
                outcome: [],
                playerRecord: {
                    wins: 0,
                    loses: 0,
                    totalPoints: 0,
                },
                standings: [],
                finishes: [],
                totalPoints: 0,
                working: false,
                playerId: 0,
                playerRanking: 0,
                numberNames: ['One', 'Two', 'Three', 'Four', 'Five'],
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
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/picks', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    this.picksList = response.data.picks;

                    console.log(this.picksList)
                    this.working = false;

                    this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/results', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        this.results = response.data.results;
                        this.parseResults(response.data.results);
                    }, function(err) {
                        console.log(err);
                    });

                    this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/standings', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        this.standings = response.data.data[0].standings;
                        this.playerId = response.data.data[0].player;
                        this.determineRank(response.data.data[0].standings);
                    }, function(err) {
                        console.log(err);
                    });

                    this.$http.get(URL.base + '/api/v1/finishes', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        this.finishes = response.data;
                        // console.log(this.finishes);
                    });

                }, function(err) {
                    console.log(err);
                    console.log(this.$route.params.contest_id);
                    this.$http.get(URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/has-player-entered', {}, {
                         // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    })
                    .then(function(response) {
                        // if no picks have been entered yet and the player has alread entered the contest redirect to the fights view
                        if ( err.data.error.status_code === 404 && response.data.hasPlayerEntered ) {
                            router.go('/contest/' + this.$route.params.contest_id + '/fights')
                        } else {
                            this.picksList = [];
                            this.working = false;
                        }
                    })
                    .catch(function(err) {
                        console.log(err)
                    })
                });
            },

            toggleDetails(pickId) {
               document.querySelector('li.fightPicksList__item[data-pick-id="' + pickId + '"]').classList.toggle('closed');
            },

            parseResults(results) {
                var fightPicks,
                    powerUpResult,
                    finishResult,
                    totalFightPicks = 0,
                    totalWinningFights = 0,
                    totalPoints = 0,
                    vm = this;

                results.forEach(function(obj, i) {
                    fightPicks = vm.picksList.find(function(pick) {
                        return parseInt(pick.fight_id, 10) === parseInt(obj.fightResults.fight_id, 10);
                    });
                    // console.log('fight picks: ', fightPicks);
                    // console.log('obj: ', obj);
                    // console.log('outcome: ', vm.outcome);
                    vm.outcome[obj.fightResults.fight_id] = {};
                    // console.log('fight id from fight results: ', parseInt(obj.fightResults.fight_id, 10));
                    // console.log('pick list: ', vm.picksList);
                    if ( fightPicks ) {
                        ++totalFightPicks;
                        if ( parseInt(obj.fightResults.winning_fighter_id, 10) === parseInt(fightPicks.fighter.winning_fighter_id, 10) ) {
                            ++totalWinningFights;
                            // console.log('got the winning fighter');
                            // figher results
                            vm.outcome[obj.fightResults.fight_id].fighter = 1;
                            vm.outcome[obj.fightResults.fight_id].fighterFavorite = ( parseInt(fightPicks.fighter.odds, 10) < 0 ) ? 1 : 0;
                            vm.outcome[obj.fightResults.fight_id].fighterPoints = fightPicks.fighter.points;
                            // finish results
                            vm.outcome[obj.fightResults.fight_id].finish_id = ( parseInt(obj.fightResults.finish_id, 10) === parseInt(fightPicks.finish.finish_id, 10) ) ? 1 : 0;
                            vm.outcome[obj.fightResults.fight_id].finish = parseInt(obj.fightResults.finish_id, 10);
                            vm.outcome[obj.fightResults.fight_id].finish_abbr = obj.fightResults.finish.abbr;
                            vm.outcome[obj.fightResults.fight_id].finish_points = fightPicks.finish.points;
                            // round resluts
                            vm.outcome[obj.fightResults.fight_id].round = ( parseInt(obj.fightResults.round, 10) === parseInt(fightPicks.round.selected, 10) ) ? 1 : 0;
                            vm.outcome[obj.fightResults.fight_id].round_points = fightPicks.round.points;
                            vm.outcome[obj.fightResults.fight_id].roundResult = obj.fightResults.round;
                            // minute results
                            vm.outcome[obj.fightResults.fight_id].minute = ( parseInt(obj.fightResults.minute, 10) === parseInt(fightPicks.minute.selected, 10) ) ? 1 : 0;
                            vm.outcome[obj.fightResults.fight_id].minute_points = fightPicks.minute.points;
                            vm.outcome[obj.fightResults.fight_id].minuteResult = obj.fightResults.minute;

                            vm.outcome[obj.fightResults.fight_id].totalTime = obj.totalTime;
                            vm.outcome[obj.fightResults.fight_id].roundTime = obj.roundTime;

                        } else {
                            vm.outcome[obj.fightResults.fight_id] = {
                                fighter: 0,
                                fighterPoints: 0,
                                finish_points: 0,
                                finish_id: 0,
                                finish_abbr: obj.fightResults.finish.abbr,
                                round_points: 0,
                                minute_points: 0,
                                roundResult: obj.fightResults.round,
                                minuteResult: obj.fightResults.minute,
                                totalTime: obj.totalTime,
                                roundTime: obj.roundTime,
                            };
                        }
                        // power up points
                        if ( fightPicks.power_up.power_up_id ) {
                            powerUpResult = obj.fightResults.power_ups.findIndex(function(pu) {
                                return ( parseInt(fightPicks.power_up.power_up_id, 10)  === parseInt(pu.power_up_id, 10) );
                            });

                            if ( powerUpResult !== -1 ) {
                                vm.outcome[obj.fightResults.fight_id].power_up = 1;
                                vm.outcome[obj.fightResults.fight_id].power_up_points = parseInt(fightPicks.power_up.bonus_points, 10);
                            } else {
                                vm.outcome[obj.fightResults.fight_id].power_up = 1;
                                vm.outcome[obj.fightResults.fight_id].power_up_points = -parseInt(fightPicks.power_up.penalty_points, 10);
                            }
                        } else {
                            vm.outcome[obj.fightResults.fight_id].power_up = 0;
                        }

                        vm.outcome[obj.fightResults.fight_id].total_points = fightPicks.totalPoints;
                        totalPoints = totalPoints + parseInt(fightPicks.totalPoints, 10);
                    }
                });
                this.playerRecord.wins = totalWinningFights;
                this.playerRecord.loses = totalFightPicks - totalWinningFights;
                this.playerRecord.totalPoints = totalPoints;
                // console.log('outcome: ', this.outcome);
                // this.tallyPoints(this.outcome);
            },

            determineRank(standings) {
                var vm = this,
                    findPlayer = function(standing) {
                        // console.log('standing player id: ', parseInt(standing.player_id, 10));
                        // console.log('playerId: ', vm.playerId)
                        return parseInt(standing.playerId, 10) === parseInt(vm.playerId, 10);
                    };

                this.playerRanking = ( standings.findIndex(findPlayer) + 1 );
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },
    };
</script>