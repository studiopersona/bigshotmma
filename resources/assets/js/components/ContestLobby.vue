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
                        <span class="contestDetails__title"><a href="#" @click="showPrizeModal">Prize Pool</a>:</span> $<span v-if="! isNaN(parseFloat(prizeTotal))">{{ parseFloat(prizeTotal).toFixed(2) }}
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
                            <div class="participantsList__itemTitle">Career Points</div>
                            <div class="participantsList__wins">
                                {{ participant.overallPoints }}
                            </div>
                        </div>
                        <div class="col-xs-20">
                            <div class="participantsList__itemTitle">Player Rank</div>
                            <div class="participantsList__rank">
                                <img :src="'public/image/level-icons/rank-icon-level-' + participant.rank + '.png'">
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
        <prize-pool-panel></prize-pool-panel>
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
    import PrizePoolPanel from './PrizePoolPanel.vue';

    export default {

        components: {
            'prize-pool-panel': PrizePoolPanel,
        },

        props: ['working', 'participantsList'],

        data() {
            return {
                prizeTotal: 0.00,
                playersBalance: 0,
                playerPromo: {
                    promoUserId: 0,
                    id: 0,
                    code: '',
                    status: [],
                    validEntryFees: [],
                },
                playerRecords: [],
                playersOverallScores: [],
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
                confirmModalClassList: [],
                contest: {},
                contestsEntered: [],
                deadlinePast: false,
                contestFull: false,
                infoModalClasses: ['infoModal'],
                fundsModalClasses: ['fundsModal'],
                confirmModalClasses: ['confirmModal'],
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
            let vm = this;

            localforage.getItem('id_token').then(function(token) {
                if ( token ) {
                    let params = auth.parseToken(token);
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
                let vm = this;

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    localforage.setItem('id_token', response.data.token).then(function() {
                        vm.fetch(token);
                    });
                })
                .catch(function(err) {
                    router.go('login');
                });
            },

            fetch(token) {
                // console.log(this.participantsList)
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/players', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    this.working = false;

                    this.contestFull = this.isContestFull(response.data.participants[0]);
                })
                .catch(function(err) {
                    console.log('an error was encountered while getting the players list')
                    console.log(err);
                });

                this.$http.get(URL.base + '/api/v1/player/contests-entered', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    this.contestsEntered = response.data.contests;
                })
                .catch(function(err) {
                    console.log('an error was encountered while getting the contests the player is entered in');
                    console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/contest-types', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    this.contestTypes = response.data;
                })
                .catch(function(err) {
                    console.log('an error was encountered while getting the contest types');
                    console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/player-balance', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    this.playersBalance = response.data.playerBalance;
                })
                .catch(function(err) {
                    console.log('an error was encountered while getting the players balance');
                    console.log(err);
                });
            },

            isContestFull(participantsData) {
                return (participantsData.participants.length >= parseInt(participantsData.contest.max_participants, 10) );
            },

            showContestRules(e) {
                var newType;
                e.preventDefault();

                // if the content is already loaded don't load it again
                if ( this.contestTypeId !== e.target.dataset.contestType )
                {
                    // load the contest type info into the panel
                    this.contestTypeId = e.target.dataset.contestType;
                    newType = this.contestTypes.find(this.findContestType);


                    this.infoModalContent.title = newType.contest_type_name;
                    this.infoModalContent.rules = newType.contest_type_rules,
                    this.infoModalContent.image = URL.base + '/public/image/info/' + newType.image_name;
                }
                // slide the panel into view
                this.infoModalClasses.push('show');
            },

            findContestType(contestType) {
                return contestType.id === parseInt(this.contestTypeId, 10);
            },

            showPrizeModal(e) {
                e.preventDefault()

                this.$broadcast('show-prize-modal');
            },

            infoModalClose(e) {
                e.preventDefault();

                this.infoModalClasses = ['infoModal'];
            },

            fundsModalClose(e) {
                e.preventDefault();

                this.fundsModalClasses = ['fundsModal'];
            },

            parsePlayerScores() {
                var vm = this;

                this.playersOverallScores.forEach(function(player, index) {
                    var findPlayer = function(player) {
                        // console.log('player_id: ', player.id);
                        // console.log('currentPlayerId: ', currentPlayerId);
                        return parseInt(player.id, 10) === parseInt(currentPlayerId, 10);
                    },
                    match,
                    currentPlayerId;

                    currentPlayerId = player.id;
                    match  = vm.participantsList[0].participants.find(findPlayer);
                    match.overallPoints = (player.totalPoints === null) ? 0 : player.totalPoints
                });
            },

            confirmQuit(e) {
                let vm = this;

                // get the players balance and promo from the server
                let fetch = function(token) {
                    vm.$http.get( URL.base + '/api/v1/player-balance', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    })
                    .then(function(response) {
                        vm.$root.playersBalance = response.data.playerBalance;
                    })
                    .then(function() {
                        vm.$http.get(URL.base + '/api/v1/player-promo', {}, {
                            headers: { 'Authorization' : 'Bearer ' + token }
                        })
                        .then(function(response) {

                            if (response.data.valid) vm.playerPromo = response.data.promo

                            this.confirmModalContent.action = 'quit';
                            this.confirmModalContent.title = 'Quit Contest';
                            this.confirmModalContent.image = URL.base + '/public/image/events/' + this.participantsList[0].contest.event_image;
                            this.confirmModalContent.body = '<p>' + this.participantsList[0].contest.contest_type_name + '<br>' + this.participantsList[0].contest.total_participants + ' / ' + this.participantsList[0].contest.max_participants + ' players</p><p class="highlight">Entry Fee: $' + this.participantsList[0].contest.buy_in + '</p><p>Are you sure you want to quit this contest?</p>';

                            this.confirmModalClassList.add('show');
                        })
                        .catch(function(err) {
                            console.log('there was an error while fetching the player promo from quit')
                            console.log(err)
                        })
                    })
                    .catch(function(err) {
                        console.log('there was an error while fetching the players balance')
                        console.log(err)
                    })
                };

                // get token and re-query server to get players true balance (protect against client side changes to balance total)
                localforage.getItem('id_token')
                .then(function(token) {
                    if ( token ) {
                        let params = auth.parseToken(token);
                        // if token is not expired continue to fetch data
                        if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                            fetch(token);
                        } else {
                            // request a server token refresh
                            vm.$http.post(URL.base + '/api/v1/refresh', {}, {
                                headers: { 'Authorization' : 'Bearer ' + token }
                            }).then(function(response) {
                                // save the new token and fetch the data
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

            confirmEnter(e) {
                let vm = this;

                // get the players balance and promo from the server
                var fetch = function(token) {
                    vm.$http.get( URL.base + '/api/v1/player-balance', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    })
                    .then(function(response) {
                            let playersBalance = response.data.playerBalance;
                            vm.$root.playersBalance = response.data.playerBalance;

                            vm.$http.get(URL.base + '/api/v1/player-promo', {}, {
                                headers: { 'Authorization' : 'Bearer ' + token }
                            })
                            .then(function(response) {

                                if (response.data.valid) vm.playerPromo = response.data.promo

                                // console.log(vm.playerPromo)

                                  // has a balance to cover the entry and has and active promo in stage 1 (need to enter paid contest)
                                if ( playersBalance >= parseInt(vm.participantsList[0].contest.buy_in, 10) && vm.playerPromo.status.stage === 1 && vm.playerPromo.validEntryFees.includes(vm.participantsList[0].contest.buy_in.toString()) ) {

                                    vm.confirmModalContent.action = 'enter';
                                    vm.confirmModalContent.title = 'Enter Contest';
                                    vm.confirmModalContent.image = URL.base + '/public/image/events/' + vm.participantsList[0].contest.event_image;
                                    vm.confirmModalContent.body = '<p>' + vm.participantsList[0].contest.contest_type_name + '<br>' + vm.participantsList[0].contest.total_participants + ' / ' + vm.participantsList[0].contest.max_participants + ' players</p><p class="highlight">Entry Fee: $' + vm.participantsList[0].contest.buy_in + '</p><p class="highlight small">UNLOCK PROMO ' + vm.playerPromo.code + ' IF YOU COMPLETE THIS CONTEST. <br> EARN A FREE $' + vm.participantsList[0].contest.buy_in + ' ENTRY.</p><p>Are you sure you want to enter this contest?</p>';

                                    vm.confirmModalClassList.add('show');
                                  // check if a free contest has been earned and the entry fee matches the entry fee of the paid contest
                                } else if ( vm.playerPromo.status.stage === 3 && vm.playerPromo.validEntryFees.includes(vm.participantsList[0].contest.buy_in.toString()) && (vm.playerPromo.paidContestEntryFee === vm.participantsList[0].contest.buy_in) ) {

                                    vm.confirmModalContent.action = 'enter';
                                    vm.confirmModalContent.title = 'Enter Contest';
                                    vm.confirmModalContent.image = URL.base + '/public/image/events/' + vm.participantsList[0].contest.event_image;
                                    vm.confirmModalContent.body = '<p>' + vm.participantsList[0].contest.contest_type_name + '<br>' + vm.participantsList[0].contest.total_participants + ' / ' + vm.participantsList[0].contest.max_participants + ' players</p><p class="highlight-red strike-through">Entry Fee: $' + vm.participantsList[0].contest.buy_in + '</p><p class="highlight">Promo Fee: $0</p><p>Are you sure you want to enter this contest?</p>';

                                    vm.confirmModalClassList.add('show');
                                } else if ( playersBalance < parseInt(vm.participantsList[0].contest.buy_in, 10)) {

                                    vm.fundsModalContent.title = 'Insufficent Funds';
                                    vm.fundsModalContent.image = URL.base + '/public/image/events/' + vm.participantsList[0].contest.event_image;
                                    vm.fundsModalContent.body = '<p>Your current balance is <span class="highlight">$' + vm.playersBalance + '</span> you need a minimum balance of <span class="highlight">$' + vm.participantsList[0].contest.buy_in + '</span> in order to enter this contest.';

                                    vm.fundsModalClasses.push('show');
                                } else {
                                    vm.confirmModalContent.action = 'enter';
                                    vm.confirmModalContent.title = 'Enter Contest';
                                    vm.confirmModalContent.image = URL.base + '/public/image/events/' + vm.participantsList[0].contest.event_image;
                                    vm.confirmModalContent.body = '<p>' + vm.participantsList[0].contest.contest_type_name + '<br>' + vm.participantsList[0].contest.total_participants + ' / ' + vm.participantsList[0].contest.max_participants + ' players</p><p class="highlight">Entry Fee: $' + vm.participantsList[0].contest.buy_in + '</p><p>Are you sure you want to enter this contest?</p>';

                                    vm.confirmModalClassList.add('show');
                                }
                            })
                            .catch(function(err) {
                                console.log('there was a problem checking for players promotions')
                                console.log(err)
                            })
                        })
                        .catch(function(err) {
                            console.log('there was an problem getting the players balance')
                            console.log(err);
                    });
                }
                // get token and re-query server to get players true balance (protect against client side changes to balance total)
                localforage.getItem('id_token').then(function(token) {
                    if ( token ) {
                        let params = auth.parseToken(token);

                        if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                            fetch(token);
                        } else {
                            vm.$http.post(URL.base + '/api/v1/refresh', {}, {
                                headers: { 'Authorization' : 'Bearer ' + token }
                            })
                            .then(function(response) {
                                localforage.setItem('id_token', response.data.token)
                                .then(function() {
                                    fetch(token);
                                });
                            })
                            .catch(function(err) {
                                router.go('login');
                            });
                        }
                    } else {
                        router.go('login');
                    }
                })
                .catch(function(err) {
                    router.go('login')
                    console.log(err);
                });
            },

            confirmModalClose(e) {
                this.confirmModalClassList.remove('show');
            },

            actionConfirmed(actionData, e) {
                var vm = this;

                localforage.getItem('id_token')
                .then(function(token) {
                    if ( actionData.action === 'quit' ) {
                        vm.$http.get(URL.base + '/api/v1/contest/' + actionData.contestId +'/quit', {}, {
                            // Attach the JWT header
                            headers: { 'Authorization' : 'Bearer ' + token }
                        })
                        .then(function(response) {
                            // console.log(response);
                            // console.log(vm.playerPromo.id)
                            // console.log(vm.playerPromo)
                            // console.log(vm.participantsList[0].contest.buy_in.toString())
                            // if player has a promo running need to set it back a stage
                            if ( vm.playerPromo.id !== 0 && vm.playerPromo.validEntryFees.includes(vm.participantsList[0].contest.buy_in.toString()) ) {
                                vm.$http.post(URL.base + '/api/v1/backup-promo', {
                                    'promoUserId': vm.playerPromo.promoUserId
                                }, {
                                    // Attach the JWT header
                                    headers: { 'Authorization' : 'Bearer ' + token }
                                })
                                .then(function() {
                                    vm.$root.playersBalance = vm.$root.playersBalance + parseInt(vm.participantsList[0].contest.buy_in, 10);
                                    router.go('/event/' + response.data.data.eventId + '/contests');
                                })
                                .catch(function(err) {
                                    console.log('an error was encountered while backing up promo')
                                    console.log(err)
                                })
                            } else {
                                vm.$root.playersBalance = vm.$root.playersBalance + parseInt(vm.participantsList[0].contest.buy_in, 10);
                                router.go('/event/' + response.data.data.eventId + '/contests');
                            }

                        })
                        .catch(function(err) {
                            console.log('an error was encountered while trying to quit the contest');
                            console.log(err);
                        });
                    } else if ( actionData.action === 'enter' ) {
                        // if the player has a valid active promo move to stage 2
                        if ( vm.playerPromo.id !== 0 && vm.playerPromo.validEntryFees.includes(vm.participantsList[0].contest.buy_in.toString()) ) {
                            vm.$http.post(URL.base + '/api/v1/update-promo', {
                                'stage': vm.playerPromo.status.stage + 1,
                                'paid_contest_id': actionData.contestId,
                                'free_contest_id': actionData.contestId,
                                'promoUserId': vm.playerPromo.promoUserId,
                                'entryFee': vm.participantsList[0].contest.buy_in,
                            }, {
                                // Attach the JWT header
                                headers: { 'Authorization' : 'Bearer ' + token }
                            })
                        }
                        router.go(actionData.path);
                    }
                });
            },
        },

        watch: {
            'participantsList'() {
                this.$broadcast('participants-list-changed', this.participantsList[0]);

                // let total = (this.participantsList[0].contest.buy_in * this.participantsList[0].contest.max_participants) - ((this.participantsList[0].contest.buy_in * this.participantsList[0].contest.max_participants)*0.15)

                // let type = this.participantsList[0].contest.contest_type_name
                // let numOfParticipants = this.participantsList[0].contest.max_participants

                // // console.log(type)

                // let payoutArray = (this.participantsList[0].contest.contest_type_id == 1) ? this.prizePoolPayouts[type][numOfParticipants] : this.prizePoolPayouts[type]
                // let placePayouts = [];

                // for(var i=0; i < payoutArray.length; i++) {
                //     placePayouts.push(total*payoutArray[i])
                // }

                // this.prizePool = {
                //     total: total,
                //     payouts: placePayouts,
                // }

                // console.log(this.prizePool.payouts)
                this.parsePlayerScores()
            },
        },

        events: {

            'prize-total-calculated'(prizeTotal) {
                this.prizeTotal = prizeTotal;
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },

        },
    };
</script>