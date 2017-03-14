<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Contest Lobby</h1>
            <div v-if="contestsEntered.indexOf(parseInt(participantsList[0].contest.contest_id, 10)) === -1">
                <h4 v-if="deadlinePast  || contestFull" class="pageHeader__subheader">
                    {{ participantsList[0].contest.event_short_name }} / Contest Closed
                </h4>
                <h4 v-else class="pageHeader__subheader">
                    {{ participantsList[0].contest.event_short_name }} / <a @click.prevent="confirmEnter">Enter</a>
                </h4>
            </div>
            <div v-else>
                <h4 v-if="deadlinePast" class="pageHeader__subheader">
                    {{ participantsList[0].contest.event_short_name }} / <a v-link="{ path: '/contest/' + participantsList[0].contest.contest_id + '/results' }">Results</a>
                </h4>
                <h4 v-else class="pageHeader__subheader">
                    {{ participantsList[0].contest.event_short_name }} / <a @click.prevent="confirmQuit">Quit Contest</a>
                </h4>
            </div>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Entry Fee:</span> $<span v-if="! isNaN(parseFloat(participantsList[0].contest.buy_in))">{{ parseFloat(participantsList[0].contest.buy_in).toFixed(2) }}</span>
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Entries:</span> {{ participantsList[0].contest.total_participants }}/{{ participantsList[0].contest.max_participants }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title"><a @click="showPrizeModal">Prize Pool</a>:</span> $<span v-if="! isNaN(parseFloat(prizePool.total))">{{ parseFloat(prizePool.total).toFixed(2) }}
                    </div>
                    <div class="col-xs-50 contestDetails__type">
                        <a href="#" @click="showContestRules" data-contest-type="{{ participantsList[0].contest.contest_type_id }}">
                            {{ participantsList[0].contest.contest_type_name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="participantsList">
            <ul class="stripped-list">
                <li class="participantsList__item" v-for="participant in participantsList[0].participants">
                    <div class="container-fluid">
                        <div v-if="participant.avatar === ''" class="col-xs-15 participantsList__img">
                            <img :src="'public/image/avatar/male.jpg'">
                        </div>
                        <div v-else class="col-xs-15 participantsList__img">
                            <img :src="'public/image/avatar/' + participant.avatar">
                        </div>
                        <div class="col-xs-40">
                            <div class="participantsList__itemTitle">&nbsp;</div>
                            <div class="participantsList__name">
                                {{ participant.player_name }}
                            </div>
                        </div>
                        <div class="col-xs-25">
                            <div class="participantsList__itemTitle">Record</div>
                            <div class="participantsList__record">
                                {{ participant.record.correctPicks }} - {{ participant.record.incorrectPicks }}
                            </div>
                        </div>
                        <div class="col-xs-20">
                            <div class="participantsList__itemTitle">Win %</div>
                            <div class="participantsList__wins">
                                {{ participant.record.percent }}%
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-if="contestsEntered.indexOf(parseInt(participantsList[0].contest.contest_id, 10)) === -1" class="container-fluid">
                <div v-if="deadlinePast || contestFull" class="col-xs-100 button-wrap">
                    This Contest is Closed <span v-if="contestFull">the Maximum Number of Players has Been Reached</span>
                </div>
                <div v-else class="col-xs-100 button-wrap">
                    <a @click.prevent="confirmEnter" class="button button--primary">Enter</a>
                </div>
            </div>

            <div v-else class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <a v-link="{ path: '/contest/' + participantsList[0].contest.contest_id + '/picks' }" class="button button--primary">My Picks</a>
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
            <p>In a <a @click="showContestRules" data-contest-type="{{ participantsList[0].contest.contest_type_id }}">{{ participantsList[0].contest.contest_type_name }}</a> contest with {{ participantsList[0].contest.max_participants }} players:</p>
                <div class="prizeModal__entryFeeWrap">
                    <span class="prizeModal__entryFeeTitle">Entry Fee:</span> <span class="prizeModal__entryFee">${{ parseFloat(participantsList[0].contest.buy_in).toFixed(2) }}</span>
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
            <img class="infoModal__image" :src="infoModalContent.image" alt="{{ infoModalContent.title }} Image">
            <div class="infoModal__rules">
                {{{ infoModalContent.rules }}}
            </div>
            <div class="button-wrap">
                <button @click="infoModalClose" type="button" class="button button--green">Got It</button>
            </div>
            <button @click="infoModalClose" type="button" class="infoModal__close">x</button>
        </section>
        <section :class="fundsModalClasses">
            <h3 class="fundsModal__title">{{ fundsModalContent.title }}</h3>
            <img class="fundsModal__image" :src="fundsModalContent.image" alt="{{ fundsModalContent.title }} Image">
            <div class="fundsModal__body">
                {{{ fundsModalContent.body }}}
            </div>
            <div class="button-wrap">
                <button @click="fundsModalClose" type="button" class="button button--green">Got It</button>
            </div>
            <button @click="fundsModalClose" type="button" class="infoModal__close">x</button>
        </section>
        <section :class="confirmModalClasses">
            <h3 class="confirmModal__title">{{ confirmModalContent.title }}</h3>
            <img class="confirmModal__image" :src="confirmModalContent.image" alt="{{ confirmModalContent.title }} Image">
            <div class="confirmModal__body">
                {{{ confirmModalContent.body }}}
            </div>
            <div class="confirmModal__confirm">
                <button @click="confirmModalClose" class="confirmModal__confirm--no">No</button>
                <button @click="actionConfirmed({ action: confirmModalContent.action, contestId: participantsList[0].contest.contest_id, path:  '/contest/' + participantsList[0].contest.contest_id + '/fights' }, $event)" class="confirmModal__confirm--yes">Yes</button>
            </div>
            <button @click="confirmModalClose" type="button" class="confirmModal__close">x</button>
        </section>
    </div>
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

    export default {

        props: ['working', 'participantsList'],

        data() {
            return {
                prizePool: {},
                playersBalance: 0,
                playerRecords: [],
                contestTypes: {},
                contestTypeId: '',
                infoModalContent: {
                    title: '',
                    rules: '',
                    image: ''
                },
                fundsModalContent: {
                    title: '',
                    body: '',
                    image: ''
                },
                confirmModalContent: {
                    action: '',
                    title: '',
                    image: '',
                    body: '',
                },
                confirmModalClasList: [],
                contest: {},
                contestsEntered: [],
                deadlinePast: false,
                contestFull: false,
                prizePoolPayouts: {
                    Classic: {
                        10: [0.7, 0.3],
                        20: [0.5, 0.25, 0.15, 0.10],
                        50: [0.365, 0.21, 0.15, 0.10, 0.05, 0.025, 0.025, 0.025, 0.025, 0.025],
                        100: [0.03275, 0.150, 0.08, 0.07, 0.06, 0.05, 0.04, 0.03, 0.0275, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150],
                    },
                    Greed: [1],
                },
                // working: false,
                infoModalClasses: ['infoModal'],
                fundsModalClasses: ['fundsModal'],
                confirmModalClasses: ['confirmModal'],
                prizeModalClasses: ['prizeModal'],
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
            }
        },

        created() {
            // this.working = true;
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



            this.confirmModalClassList = document.querySelector('.confirmModal').classList;
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
                console.log(this.participantsList)
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/players', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    var now = new Date(),
                        deadline;

                    this.working = false;

                    this.contestFull = ( response.data.participants[0].participants.length >= parseInt(response.data.participants[0].contest.max_participants, 10) );
                }, function(err) {
                    console.log(err);
                });

                this.$http.get(URL.base + '/api/v1/player/contests-entered', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    this.contestsEntered = response.data.contests;
                }, function(err) {
                    console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/contest-types', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    this.contestTypes = response.data;
                });

                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/players-records', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    // console.log(response.data);
                    this.playerRecords = response.data.data;
                    // this.parsePlayerRecords();
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
                    this.infoModalContent.image = URL.base + '/public/image/info/' + newType.image_name;
                }

                this.infoModalClasses.push('show');
            },

            findContestType(contestType) {
                return contestType.id === parseInt(this.contestTypeId, 10);
            },

            showPrizeModal() {
                this.prizeModalClasses.push('show');
            },

            infoModalClose(e) {
                e.preventDefault();

                this.infoModalClasses = ['infoModal'];
            },

            fundsModalClose(e) {
                e.preventDefault();

                this.fundsModalClasses = ['fundsModal'];
            },

            prizeModalClose(e) {
                e.preventDefault();

                this.prizeModalClasses = ['prizeModal'];
            },

            parsePlayerRecords() {
                var vm = this;

                this.playerRecords.forEach(function(player, index) {
                    var findPlayer = function(player) {
                        // console.log('player_id: ', player.id);
                        // console.log('currentPlayerId: ', currentPlayerId);
                        return parseInt(player.id, 10) === parseInt(currentPlayerId, 10);
                    },
                    match,
                    currentPlayerId;

                    currentPlayerId = player.id;
                    match  = vm.participantsList[0].participants.find(findPlayer);
                    match.record = {
                        correctPicks: player.record.correctPicks,
                        incorrectPicks: player.record.incorrectPicks,
                        percent: (parseInt(player.record.totalPicks, 10) === 0) ? 0 : Math.round(( parseInt(player.record.correctPicks, 10)/parseInt(player.record.totalPicks, 10) ) * 100),
                    };
                });

                // console.log(this.participantsList[0].participants);
            },

            confirmQuit(e) {
                this.confirmModalContent.action = 'quit';
                this.confirmModalContent.title = 'Quit Contest';
                this.confirmModalContent.image = URL.base + '/public/image/events/' + this.participantsList[0].contest.event_image;
                this.confirmModalContent.body = '<p>' + this.participantsList[0].contest.contest_type_name + '<br>' + this.participantsList[0].contest.total_participants + ' / ' + this.participantsList[0].contest.max_participants + ' players</p><p class="highlight">Entry Fee: $' + this.participantsList[0].contest.buy_in + '</p><p>Are you sure you want to quit this contest?</p>';

                this.confirmModalClassList.add('show');
            },

            confirmEnter(e) {
                var vm = this,
                    params,
                    playersBalance
                // get the players balance from the server
                var fetch = function(token) {
                    vm.$http.get( URL.base + '/api/v1/player-balance', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(
                        function(response) {
                            playersBalance = response.data.playerBalance;
                            vm.$root.playersBalance = response.data.playerBalance;
                            if ( playersBalance >= parseInt(vm.participantsList[0].contest.buy_in, 10) ) {
                                vm.confirmModalContent.action = 'enter';
                                vm.confirmModalContent.title = 'Enter Contest';
                                vm.confirmModalContent.image = URL.base + '/public/image/events/' + vm.participantsList[0].contest.event_image;
                                vm.confirmModalContent.body = '<p>' + vm.participantsList[0].contest.contest_type_name + '<br>' + vm.participantsList[0].contest.total_participants + ' / ' + vm.participantsList[0].contest.max_participants + ' players</p><p class="highlight">Entry Fee: $' + vm.participantsList[0].contest.buy_in + '</p><p>Are you sure you want to enter this contest?</p>';

                                vm.confirmModalClassList.add('show');
                            } else {
                                vm.fundsModalContent.title = 'Insufficent Funds';
                                vm.fundsModalContent.image = URL.base + '/public/image/events/' + vm.participantsList[0].contest.event_image;
                                vm.fundsModalContent.body = '<p>Your current balance is <span class="highlight">$' + vm.playersBalance + '</span> you need a minimum balance of <span class="highlight">$' + vm.participantsList[0].contest.buy_in + '</span> in order to enter this contest.';

                                vm.fundsModalClasses.push('show');
                            }
                        },
                        function(err) {
                            console.log(err);
                    });
                }
                // get token and re-query server to get players true balance (protect against client side changes to balance total)
                localforage.getItem('id_token').then(function(token) {
                    if ( token ) {
                        params = auth.parseToken(token);
                        if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                            fetch(token);
                        } else {
                            vm.$http.post(URL.base + '/api/v1/refresh', {}, {
                                headers: { 'Authorization' : 'Bearer ' + token }
                            }).then(function(response) {
                                localforage.setItem('id_token', response.data.token).then(function() {
                                    fetch(token);
                                });
                            }, function(err) {
                                router.go('login');
                            });
                        }
                    } else {
                        router.go('login');
                    }
                })
                .catch(function(err) {
                    console.log(err);
                });
            },

            confirmModalClose(e) {
                this.confirmModalClassList.remove('show');
            },

            actionConfirmed(actionData, e) {
                var vm = this;

                localforage.getItem('id_token').then(function(token) {
                    if ( actionData.action === 'quit' ) {
                        vm.$http.get(URL.base + '/api/v1/contest/' + actionData.contestId +'/quit', {}, {
                            // Attach the JWT header
                            headers: { 'Authorization' : 'Bearer ' + token }
                        }).then(function(response) {
                            // console.log(response);
                            vm.$root.playersBalance = vm.$root.playersBalance + parseInt(vm.participantsList[0].contest.buy_in, 10);
                            router.go('/event/' + response.data.data.eventId + '/contests');
                        });
                    } else if ( actionData.action === 'enter' ) {
                        router.go(actionData.path);
                    }
                });
            },
        },

        watch: {
            'participantsList'() {
                let total = (this.participantsList[0].contest.buy_in * this.participantsList[0].contest.max_participants) - ((this.participantsList[0].contest.buy_in * this.participantsList[0].contest.max_participants)*0.15)

                let type = this.participantsList[0].contest.contest_type_name
                let numOfParticipants = this.participantsList[0].contest.max_participants

                console.log(type)

                let payoutArray = (this.participantsList[0].contest.contest_type_id == 1) ? this.prizePoolPayouts[type][numOfParticipants] : this.prizePoolPayouts[type]
                let placePayouts = [];

                for(var i=0; i < payoutArray.length; i++) {
                    placePayouts.push(total*payoutArray[i])
                }

                this.prizePool = {
                    total: total,
                    payouts: placePayouts,
                }

                console.log(this.prizePool.payouts)

                this.parsePlayerRecords()
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },

        },
    };
</script>