<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Picks</h1>
            <h4 class="pageHeader__subheader">{{ fightsList[0].event.event_short_name }}</h4>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Buy in:</span> ${{ participantsList[0].contest.buy_in }}
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
        <div class="fightPicksList">
            <ul>
                <li class="fightPicksList__item" v-for="pick in picksList[0].picks">
                    <div class="container-fluid">
                        <div class="col-xs-10">
                            {{ }}
                        </div>
                        <div class="col-xs-45">
                            <div class="fightPicksList__fighterName"></div>
                            <div class="fightPicksList__odds"></div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title"></div>
                            <div class="fightPicksList__stat"></div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title"></div>
                            <div class="fightPicksList__stat"></div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title"></div>
                            <div class="fightPicksList__stat"></div>
                        </div>
                    </div>
                    <div class="fightPicksList__details">
                        <div class="container-fluid">
                            <div class="col-xs-100 fightPicksList__resultString">

                            </div>
                            <div class="col-xs-100 fightPicksList__choicesTitle">

                            </div>
                            <div class="col-xs-100">
                                <div class="col-xs-10">

                                </div>
                                <div class="col-xs-80">

                                </div>
                                <div class="col-xs-10">

                                </div>
                            </div>
                            <div class="col-xs-100 fightPicksList__totalWrap">
                                <div class="col-xs-50 fightPicksList__totalTitle">
                                    Total
                                </div>
                                <div class="col-xs-50 fightPicksList__totalValue">

                                </div>
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
                        v-link="{ path: '/' }"
                    >View Results</button>
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

            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/contest/' + this.$route.params.contest_id + '/picks', (data) => {
                this.fightPicksList = data;
                this.working = false;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))
        },

        methods: {

        },

        computed: {
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