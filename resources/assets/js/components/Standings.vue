<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Contest Standings</h1>
            <h4 class="pageHeader__subheader">
                {{ contest.event_short_name }} / <a v-if="standingsList.length" v-link="{ path: '/contest/' + contest.contest_id + '/results' }">Results</a><a v-else v-link="{ path: '/contest/' + contest.contest_id + '/picks' }">My Picks</a>
            </h4>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Entry Fee:</span> ${{ contest.buy_in.toFixed(2) }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Rank:</span> {{ playerRanking }}/{{ contest.max_participants }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title"><a @click="showPrizeModal">Prize Pool</a>:</span> $<span v-if="! isNaN(parseFloat(prizePool.total))">{{ parseFloat(prizePool.total).toFixed(2) }}
                    </div>
                    <div class="col-xs-50 contestDetails__type">
                        <a href="#" @click="showContestRules" data-contest-type="{{ contest.contest_type_id }}">
                            {{ contest.contest_type_name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="participantsList">
            <template v-if="standingsList.length">
                <ul class="stripped-list">
                    <li class="participantsList__item" v-for="participant in standingsList">
                        <div class="container-fluid">
                            <div v-if="participant.avatar === ''" class="col-xs-15 participantsList__img">
                                <img :src="URL.base + '/public/image/avatar/male.jpg'">
                            </div>
                            <div v-else class="col-xs-15 participantsList__img">
                                <img :src="URL.base + '/public/image/avatar/' + participant.avatar">
                            </div>
                            <div class="col-xs-30">
                                <div class="participantsList__itemTitle">&nbsp;</div>
                                <div class="participantsList__name">
                                    {{ participant.player_name }}
                                </div>
                            </div>
                            <div class="col-xs-15">
                                <div class="participantsList__itemTitle">Rank</div>
                                <div class="standingsList__rank">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div class="col-xs-15">
                                <div class="participantsList__itemTitle">Points</div>
                                <div class="standingsList__points">
                                    {{ participant.totalPoints }}
                                </div>
                            </div>
                            <div class="col-xs-25">
                                <div class="participantsList__itemTitle">Winnings</div>
                                <div v-if="winnings.length" class="standingsList__winnings">
                                    <span v-if="winnings[$index]" class="highlight">${{ winnings[$index].payout.toFixed(2) }}</span>
                                    <span v-else>---</span>
                                </div>
                                <div v-else class="standingsList__winnings">
                                    --
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </template>
            <template v-else>
                <p style="margin-top: 1rem; text-align: center;">No results have been reported.</p>
            </template>
            <div v-if="hasPicks" class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <a v-link="{ path: '/contest/' + contest.contest_id + '/picks' }" class="button button--primary">My Picks</a>
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
        <section :class="prizeModalClasses">
            <h3 class="prizeModal__title">Prize Pool</h3>
            <div class="prizeModal__body">
            <p>In a <a @click="showContestRules" data-contest-type="{{ standingsList[0].contest.contest_type_id }}">{{ standingsList[0].contest.contest_type_name }}</a> contest with {{ standingsList[0].contest.max_participants }} players:</p>
                <div class="prizeModal__entryFeeWrap">
                    <span class="prizeModal__entryFeeTitle">Entry Fee:</span> <span class="prizeModal__entryFee">${{ parseFloat(standingsList[0].contest.buy_in).toFixed(2) }}</span>
                </div>
                <table class="prizeModal__payoutTable">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Prize</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payout of prizePool.payouts" track-by="$index">
                            <td>{{ $index + 1 }}</td>
                            <td class="prizeModal__payout">${{ parseFloat(payout).toFixed(2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="button-wrap">
                <button @click="prizeModalClose" type="button" class="button button--green">Got It</button>
            </div>
            <button @click="prizeModalClose" type="button" class="infoModal__close">x</button>
        </section>
        <section :class="infoModalClasses">
            <h3 class="infoModal__title">{{ infoModalContent.title }}</h3>
            <img class="infoModal__image" :src="URL.base + '/public/image/info/' + infoModalContent.image" alt="{{ infoModalContent.title }} Image">
            <div class="infoModal__rules">
                {{{ infoModalContent.rules }}}
            </div>
            <button @click="infoModalClose" type="button" class="infoModal__close">x</button>
        </section>
    </div>
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

    export default {

        props: ['working', 'standingsList', 'contest'],

        data() {
            return {
                prizePool: {},
                winnings: [],
                contestTypes: {},
                contestTypeId: '',
                infoModalContent: {
                    title: '',
                    rules: '',
                    image: ''
                },
                playerId: 0,
                playerRanking: 0,
                infoModalClasses: ['infoModal'],
                hasPicks: true,
                prizePoolPayouts: {
                    Classic: {
                        10: [0.7, 0.3],
                        20: [0.5, 0.25, 0.15, 0.10],
                        50: [0.365, 0.21, 0.15, 0.10, 0.05, 0.025, 0.025, 0.025, 0.025, 0.025],
                        100: [0.3275, 0.150, 0.08, 0.07, 0.06, 0.05, 0.04, 0.03, 0.0275, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150],
                    },
                    Greed: [1],
                    '50/50': [1],
                },
                prizeModalClasses: ['prizeModal'],
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
                let vm = this

                this.$nextTick(function() {
                    console.log(vm.standingsList)
                    vm.determineRank(vm.standingsList);
                })

                this.$http.get( URL.base + '/api/v1/contest-types', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    this.contestTypes = response.data

                    this.working = false
                })
                .catch(function(err) {
                    console.log(err)
                });

                this.$http.get( URL.base + '/api/v1/contest/' + this.contest.contest_id + '/winnings', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    console.log(response)
                    this.winnings = response.data.winnings
                })
                .catch(function(err) {
                    console.log('there was a problem fetching the winnings')
                    console.log(err)
                })

                this.$http.get( URL.base + '/api/v1/contest/' + this.contest.contest_id + '/check-for-picks', {}, {
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

            showContestRules(e) {
                var newType;
                e.preventDefault();

                // if the content is already loaded don't load it again
                if ( this.contestTypeId !== e.target.dataset.contestType )
                {
                    this.contestTypeId = e.target.dataset.contestType;
                    newType = this.contestTypes.find(this.findContestType);


                    this.infoModalContent.title = newType.contest_type_name;
                    this.infoModalContent.rules = newType.contest_type_rules,
                    this.infoModalContent.image = newType.image_name;
                }

                this.infoModalClasses.push('show');
            },

            findContestType(contestType) {
                return contestType.id === parseInt(this.contestTypeId, 10);
            },

            infoModalClose(e) {
                e.preventDefault();

                this.infoModalClasses = ['infoModal'];
            },

            determineRank(standings) {
                var vm = this,
                    findPlayer = function(standing) {
                        return parseInt(standing.playerId, 10) === parseInt(vm.playerId, 10);
                    };

                this.playerRanking = ( standings.findIndex(findPlayer) + 1 );
            },

            showPrizeModal() {
                this.prizeModalClasses.push('show');
            },

            prizeModalClose(e) {
                e.preventDefault();

                this.prizeModalClasses = ['prizeModal'];
            },
        },

        watch: {
            'standingsList'() {
                let total = (this.contest.buy_in * this.contest.max_participants) - ((this.contest.buy_in * this.contest.max_participants)*0.15)

                let type = this.contest.contest_type_name
                let numOfParticipants = this.contest.max_participants

                let payoutArray = (this.contest.contest_type_id == 1) ? this.prizePoolPayouts[type][numOfParticipants] : this.prizePoolPayouts[type]
                let placePayouts = [];

                for(var i=0; i < payoutArray.length; i++) {
                    placePayouts.push(total*payoutArray[i])
                }

                this.prizePool = {
                    total: total,
                    payouts: placePayouts,
                }
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },
    };
</script>