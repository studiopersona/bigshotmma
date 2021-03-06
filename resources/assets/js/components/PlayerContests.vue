<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Enter a Contest</h1>
            <!--<h4 class="pageHeader__subheader">
                Total Prize Pool ${{ poolTotal.toFixed(2) }}
            </h4>-->
        </header>
        <div class="contestList">
            <ul v-if="contestsList.contests.length > 0" class="stripped-list">
                <li class="contestList__item" v-for="contest in contestsList.contests">
                    <a v-link="{ path: '/contest/' + contest.contest_id + '/players' }">
                        <div class="container-fluid">
                            <div class="col-xs-23">
                                <img class="contestList__img" :src="URL.base + '/public/image/events/' + contest.event_image_file" alt="{{ contest.event_name }} Image">
                            </div>
                            <div class="col-xs-42 contestList__infoWarp">
                                <div class="contestList__date">{{ contest.event_date }} {{ contest.event_time }}</div>
                                <div class="contestList__name">{{ contest.event_name }}</div>
                                <div class="contestList__type">{{ contest.contest_type_name }}</div>
                            </div>
                            <div class="col-xs-20">
                                <div class="contestList__entriesTitle">Entries</div>
                                <div class="contestList__entries">{{ contest.total_participants }}/{{ contest.max_participants }}</div>
                            </div>
                            <div class="col-xs-15">
                                <div class="contestList__buyinTitle">Entry Fee</div>
                                <div class="contestList__buyin">${{ parseFloat(contest.buy_in).toFixed(2) }}</div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <ul v-else>
                <li class="noResults">There are no Contests available at this time. Check back soon for our next set of contests.</li>
            </ul>
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
                contestsList: { 'contests':{} },
                contestsEntered: [],
                poolTotal: 0,
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

            localforage.getItem('id_token')
            .then(function(token) {
                if ( token ) {
                    params = auth.parseToken(token);
                    // if the token has not expired get the required data
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                        vm.fetch(token);
                    } else {
                        // make a request to the server to refresh the token
                        vm.tokenRefresh(token);
                    }
                } else {
                    // player needs to login
                    router.go('login');
                }
            })
            .catch(function(err) {
                // player needs to login
                route.go('login');
                console.log(err);
            });
        },

        methods: {
            tokenRefresh(token) {
                var vm = this;

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    // store the new token and fetch required data
                    localforage.setItem('id_token', response.data.token)
                    .then(function() {
                        vm.fetch(token);
                    });
                })
                .catch(function(err) {
                    // player needs to login again
                    router.go('login');
                });
            },

            fetch(token) {
                // get a list of all contests
                this.$http.get(URL.base + '/api/v1/contests', {}, {
                    // Attach the JWT header
                    headers:{ 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    this.contestsList = response.data;

                    this.working = false;
                })
                .catch(function(err) {
                    console.log('an error was encounter while getting the contest list')
                    console.log(err);

                    this.working = false;
                });
                // get a list of contests the player has entered
                this.$http.get(URL.base + '/api/v1/player/contests-entered', {}, {
                    // Attach the JWT header
                    headers:{ 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    this.contestsEntered = response.data.contests;
                })
                .catch(function(err) {
                    console.log('an error was encounter while getting the players entered contset list');
                    console.log(err);
                });
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },
    };
</script>