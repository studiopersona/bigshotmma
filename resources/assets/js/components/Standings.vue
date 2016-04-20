<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Contest Standings</h1>
            <h4 class="pageHeader__subheader">
                {{ contest.event_short_name }} - {{ contest.event_date }}
            </h4>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Buy in:</span> ${{ contest.buy_in }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Rank:</span> {{ playerRanking }}/{{ standingsList.length }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-50">
                    <span class="contestDetails__title">Prize Pool:</span> ${{ (parseInt(contest.buy_in, 10) * standingsList.length) * .9 }}
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
            <ul class="stripped-list">
                <li class="participantsList__item" v-for="participant in standingsList">
                    <div class="container-fluid">
                        <div class="col-xs-15 participantsList__img">
                            <img src="public/image/avatar/male.jpg">
                        </div>
                        <div class="col-xs-35">
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
                                {{ participant.total }}
                            </div>
                        </div>
                        <div class="col-xs-20">
                            <div class="participantsList__itemTitle">Fights</div>
                            <div class="standingsList__wins">
                                {{ participant.fights_won }}/5
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div :class="loaderClasses">
                <div class="js-global-loader loader">
                    <svg viewBox="0 0 32 32" width="32" height="32">
                        <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
                    </svg>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="col-xs-100 button-wrap">
                <a v-link="{ path: '/contest/' + contest.contest_id + '/picks' }" class="button button--primary">View Your Picks</a>
            </div>
        </div>
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
    export default {

        props: ['working'],

        data() {
            return {
                standingsList: [],
                contest: {},
                contestTypes: {},
                contestTypeId: '',
                infoModalContent: {
                    title: '',
                    rules: '',
                    image: ''
                },
                playerId: 0,
                playerRanking: 0,
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
            this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/standings-list', (data) => {
                this.standingsList = data.data[0].standings;
                this.playerId = data.data[0].player;
                this.contest = data.data[0].contest[0];
                this.determineRank(data.data[0].standings);
                this.working = false;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err));

            this.$http.get( URL.base + '/api/v1/contest-types', (data) => {
                this.contestTypes = data;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            });
        },

        methods: {
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