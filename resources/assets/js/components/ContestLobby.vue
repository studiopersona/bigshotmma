<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Contest Lobby</h1>
            <h4 class="pageHeader__subheader">
                {{ participantsList[0].contest.event_short_name }} / <a v-link="{ path: '/contest/' + participantsList[0].contest.contest_id + '/results' }">Results</a>
            </h4>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Entry Fee:</span> ${{ participantsList[0].contest.buy_in }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Entries:</span> {{ participantsList[0].contest.total_participants }}/{{ participantsList[0].contest.max_participants }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-50">
                    <span class="contestDetails__title">Prize Pool:</span> ${{ participantsList[0].contest.prize_pool }}
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
                        <div class="col-xs-15 participantsList__img">
                            <img src="public/image/avatar/male.jpg">
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
            <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <a v-link="{ path: '/contest/' + participantsList[0].contest.contest_id + '/fights' }" class="button button--primary">Enter</a>
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
        <section :class="infoModalClasses">
            <h3 class="infoModal__title">{{ infoModalContent.title }}</h3>
            <img class="infoModal__image" :src="URL.base + '/public/image/info/' + infoModalContent.image" alt="{{ infoModalContent.title }} Image">
            <div class="infoModal__rules">
                {{{ infoModalContent.rules }}}
            </div>
            <div class="button-wrap">
                <button @click="infoModalClose" type="button" class="button button--green">Got It</button>
            </div>
            <button @click="infoModalClose" type="button" class="infoModal__close">x</button>
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
                participantsList: [{
                    contest: {
                        event_short_name: '',
                        event_date: '',
                        buy_in: '',
                        total_participants: '',
                        max_participants: '',
                        prize_pool: '',
                        contest_type_name: '',
                        contest_id: '',
                    },
                }],
                playerRecords: [],
                contestTypes: {},
                contestTypeId: '',
                infoModalContent: {
                    title: '',
                    rules: '',
                    image: ''
                },
                contest: {},
                working: false,
                infoModalClasses: ['infoModal'],
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
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/players', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.participantsList = response.data.participants;
                    this.working = false;
                }, function(err) {
                    console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/contest-types', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.contestTypes = response.data;
                });

                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/players-records', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    console.log(response.data);
                    this.playerRecords = response.data.data;
                    this.parsePlayerRecords();
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

            parsePlayerRecords() {
                var vm = this;

                this.playerRecords.forEach(function(player, index) {
                    var findPlayer = function(player) {
                        console.log('player_id: ', player.id);
                        console.log('currentPlayerId: ', currentPlayerId);
                        return parseInt(player.id, 10) === parseInt(currentPlayerId, 10);
                    },
                    match,
                    currentPlayerId;

                    currentPlayerId = player.id;
                    match  = vm.participantsList[0].participants.find(findPlayer);
                    match.record = {
                        correctPicks: player.record.correctPicks,
                        incorrectPicks: player.record.incorrectPicks,
                        percent: Math.round(( parseInt(player.record.correctPicks, 10)/parseInt(player.record.totalPicks, 10) ) * 100),
                    };
                });

                console.log(this.participantsList[0].participants);
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
            }
        }
    };
</script>