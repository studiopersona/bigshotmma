<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Picks</h1>
            <h4 class="pageHeader__subheader">{{ picksList[0].event.event_short_name }}</h4>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Buy in:</span> ${{ picksList[0].contest.buy_in }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Standing:</span> <span v-if="results.length">{{ playerRanking }}/{{ standings.length }}</span>
                        <span v-else>No Results Reported</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Prize Pool:</span> ${{ picksList[0].contest.prize_pool  }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Your Winnigs:</span> $0
                    </div>
                </div>
            </div>
        </div>
        <div class="fightPicksList">
            <ul class="stripped-list">
                <li
                    class="fightPicksList__item"
                    @click="toggleDetails(pick.pick_id, $event)"
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
                            <div :class="['fightPicksList__odds', (parseInt(pick.fighter.odds, 10) > 0) ? 'favorite' : '' ]">
                                {{ pick.fighter.odds  }}
                            </div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Result</div>
                            <div v-if="!results.length" class="fightPicksList__stat">---</div>
                            <div v-else :class="['fightPicksList__stat', outcome[pick.fight_id].fighter ? 'correct' : '']">{{ outcome[pick.fight_id].fighter ? 'W' : 'L' }}</div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Finish</div>
                            <div v-if="!results.length" class="fightPicksList__stat">---</div>
                            <div v-else :class="['fightPicksList__stat', outcome[pick.fight_id].finish_id ? 'correct' : '']">{{ outcome[pick.fight_id].finish_abbr }}</div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Points</div>
                            <div v-if="!results.length" class="fightPicksList__stat">---</div>
                            <div v-else :class="['fightPicksList__stat', outcome[pick.fight_id].points > 0 ? 'correct' : '']">{{ outcome[pick.fight_id].points }}</div>
                        </div>
                    </div>
                    <div class="fightPicksList__details">
                        <div class="container-fluid">
                            <div v-if="!results.length" class="col-xs-100 fightPicksList__resultString">
                                Fight Results Not Available
                            </div>
                            <div v-else class="col-xs-70 col-xs-push-15 fightPicksList__resultString">
                                {{ pick.fighter.name }} <span v-if="outcome[pick.fight_id].fighter">wins</span><span v-else>loses</span> via {{ outcome[pick.fight_id].finish_abbr }}
                                in Round {{ outcome[pick.fight_id].roundResult }} at {{ outcome[pick.fight_id].totalTime }}
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
                                    <h4 v-if="(parseInt(pick.fighter.odds, 10) > 0)" class="fightPicksList__selectionTitle">Favorite to Win</h4>
                                    <h4 v-else  class="fightPicksList__selectionTitle">Underdog to Win</h4>
                                    <p class="fighPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].fighter">You chose the winning fighter</span>
                                        <span v-else>You didn't chose the winning figher</span>
                                    </p>
                                    <p class="fighPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].fighter ? 'correct' : '']">
                                    +{{ outcome[pick.fight_id].fighterPoints }}
                                </div>
                            </div>
                            <!-- finish row -->
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/fist.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">{{ pick.finish.finish_type }}</h4>
                                    <p class="fighPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].finish_id">You chose the winning finish</span>
                                        <span v-else>You didn't choose the winning finish</span>
                                    </p>
                                    <p class="fightPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].finish_id ? 'correct' : '']">
                                    <span v-if="outcome[pick.fight_id].finish_id">+5</span>
                                    <span v-else>0</span>
                                </div>
                            </div>
                            <!-- round row -->
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/bell.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">Round {{ numberNames[parseInt(pick.round, 10) - 1] }}</h4>
                                    <p class="fighPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].round">You chose the winning round</span>
                                        <span v-else>You didn't choose the winning round</span>
                                    </p>
                                    <p class="fighPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].round ? 'correct' : '']">
                                    <span v-if="outcome[pick.fight_id].round">+2</span>
                                    <span v-else>0</span>
                                </div>
                            </div>
                            <!-- minute row -->
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/stopwatch.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">Minute {{ numberNames[parseInt(pick.minute, 10) - 1] }}</h4>
                                    <p class="fighPicksList__selectionResults" v-if="results.length">
                                        <span v-if="outcome[pick.fight_id].minute">You chose the winning minute</span>
                                        <span v-else>You didn't chose the winning minute</span>
                                    </p>
                                    <p class="fighPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else :class="['col-xs-15', 'fightPicksList__points', outcome[pick.fight_id].minute ? 'correct' : '']">
                                    <span v-if="outcome[pick.fight_id].minute">+1</span>
                                    <span v-else>0</span>
                                </div>
                            </div>
                            <!-- power up row -->
                            <div v-if="pick.power_up.image" class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img :src="'public/image/powerups/' + pick.power_up.image">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__powerUpName" :style="{color: pick.power_up.color}">{{ pick.power_up.power_up_name }}</h4>
                                    <p class="fighPicksList__selectionResults" v-show="results.length">
                                        <span v-if="parseInt(outcome[pick.fight_id].power_up_points, 10) > 0">You chose a winning power up</span>
                                        <span v-else>You're fighter did not achieve the power up</span>
                                    </p>
                                    <p class="fighPicksList__selectionResults" v-else>
                                        Results not entered yet
                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points" :style="{color: pick.power_up.color}">
                                    ---
                                </div>
                                <div    v-else :class="['col-xs-15', 'fightPicksList__points', parseInt(outcome[pick.fight_id].power_up_points, 10) > 0 ? 'correct' : 'penalty']" style="{color: pick.power_up.color}">
                                    {{ outcome[pick.fight_id].power_up_points }}
                                </div>
                            </div>
                            <!-- ponits total -->
                            <div class="col-xs-100 fightPicksList__totalWrap">
                                <div class="col-xs-85 fightPicksList__totalTitle">
                                    Total
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__totalValue">
                                    ---
                                </div>
                                <div v-else class="col-xs-15 fightPicksList__totalValue">
                                    {{ outcome[pick.fight_id].points }}
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-if="results.length" class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        v-link="{ path: '/contest/' + picksList[0].contest.id + '/standings' }"
                    >Lobby</button>
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
                standings: [],
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
                playerIsValid: true,
            }
        },

        created() {
            this.working = true;
            if ( ! auth.validate() ) {
                if ( ! auth.refresh(this) ) {
                    router.go('login');
                    this.playerIsValid = false;
                }
            }
        },

        ready() {
            if ( this.playerIsValid ) {
                //console.log(URL.base);
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/picks', (data) => {
                    this.picksList = data.picks;
                    this.working = false;

                    this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/results', (data) => {
                        this.results = data.results;
                        this.parseResults(data.results);
                    }, {
                        // Attach the JWT header
                        headers: auth.getAuthHeader()
                    }).error((err) => {
                        console.log(err);
                    });

                    this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/standings', (data) => {
                        this.standings = data.data[0].standings;
                        this.playerId = data.data[0].player;
                        this.determineRank(data.data[0].standings);
                    }, {
                        // Attach the JWT header
                        headers: auth.getAuthHeader()
                    });
                }, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).error((err) => console.log(err))
            }
        },

        methods: {
            toggleDetails(pickId, e) {
                e.preventDefault();
                var pickToToggle = document.querySelector('li.fightPicksList__item[data-pick-id="' + pickId + '"]');

                pickToToggle.classList.toggle('show');
            },

            parseResults(results) {
                var fightPicks,
                    powerUpResult,
                    vm = this;

                results.forEach(function(obj, i) {
                    fightPicks = vm.picksList.find(function(pick) {
                        return parseInt(pick.fight_id, 10) === parseInt(obj.fightResults.fight_id, 10);
                    });
                    vm.outcome[obj.fightResults.fight_id] = {};
                    vm.outcome[obj.fightResults.fight_id].fighter = ( parseInt(obj.fightResults.winning_fighter_id, 10) === parseInt(fightPicks.fighter.winning_fighter_id, 10) ) ? 1 : 0;
                    vm.outcome[obj.fightResults.fight_id].fighterFavorite = ( parseInt(fightPicks.fighter.odds, 10) > 0 ) ? 1 : 0;

                    if ( parseInt(obj.fightResults.winning_fighter_id, 10) && parseInt(fightPicks.fighter.odds, 10) > 0 ) {
                        vm.outcome[obj.fightResults.fight_id].fighterPoints = 3;
                    } else if ( parseInt(obj.fightResults.winning_fighter_id, 10) && parseInt(fightPicks.fighter.odds, 10) < 0 ) {
                        vm.outcome[obj.fightResults.fight_id].fighterPoints = 5;
                    } else {
                        vm.outcome[obj.fightResults.fight_id].fighterPoints = 0;
                    }

                    vm.outcome[obj.fightResults.fight_id].finish_id = ( parseInt(obj.fightResults.finish_id, 10) === parseInt(fightPicks.finish.finish_id, 10) ) ? 1 : 0;
                    vm.outcome[obj.fightResults.fight_id].finish_abbr = obj.fightResults.finish.abbr;
                    vm.outcome[obj.fightResults.fight_id].round = ( parseInt(obj.fightResults.round, 10) === parseInt(fightPicks.round, 10) ) ? 1 : 0;
                    vm.outcome[obj.fightResults.fight_id].minute = ( parseInt(obj.fightResults.minute, 10) === parseInt(fightPicks.minute, 10) ) ? 1 : 0;

                    vm.outcome[obj.fightResults.fight_id].roundResult = obj.fightResults.round;
                    vm.outcome[obj.fightResults.fight_id].minuteResult = obj.fightResults.minute;
                    vm.outcome[obj.fightResults.fight_id].totalTime = obj.totalTime;

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
                });
                this.tallyPoints(this.outcome);
            },

            tallyPoints(outcome) {
                var vm = this;

                outcome.forEach(function (item, i) {
                    outcome[i].points = 0;
                    // +5 for underdog, +3 for favorite
                    if ( item.fighter ) {
                        if (item.fighterFavorite ) {
                            outcome[i].points += 3;
                        } else {
                            outcome[i].points += 5;
                        }
                    }
                    if ( item.finish_id ) outcome[i].points += 5;
                    if ( item.minute ) outcome[i].points += 1;
                    if ( item.round ) outcome[i].points += 2;

                    if ( item.power_up ) outcome[i].points += item.power_up_points;

                    vm.totalPoints += outcome[i].points;
                });
            },

            determineRank(standings) {
                var vm = this,
                    findPlayer = function(standing) {
                        console.log('standing player id: ', parseInt(standing.player_id, 10));
                        console.log('playerId: ', vm.playerId)
                        return parseInt(standing.player_id, 10) === parseInt(vm.playerId, 10);
                    };

                this.playerRanking = ( standings.findIndex(findPlayer) + 1 );
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },

        route: {
            // Check the users auth status before
            // allowing navigation to the route
            canActivate() {
                return auth.user.authenticated
            }
        }
    };
</script>