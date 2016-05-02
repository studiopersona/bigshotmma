<template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Enter a Contest</h1>
            <h4 class="pageHeader__subheader">
                Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
            </h4>
        </header>
        <div class="contestList">
            <ul v-if="contestsList.contests.length > 0" class="stripped-list">
                <li class="contestList__item" v-for="contest in contestsList.contests">
                    <a v-if="contestsEntered.indexOf(parseInt(contest.contest_id, 10)) !== -1" v-link="{ path: '/contest/' + contest.contest_id + '/picks' }">
                        <div class="container-fluid">
                            <div class="col-xs-20">
                                <img class="contestList__img" :src="URL.base + '/public/image/events/' + contest.event_image_file" alt="{{ contest.event_name }} Image">
                            </div>
                            <div class="col-xs-45 contestList__infoWarp">
                                <div class="contestList__date">{{ contest.event_date }}</div>
                                <div class="contestList__name">{{ contest.event_short_name }}</div>
                                <div class="contestList__type">{{ contest.contest_type_name }}</div>
                            </div>
                            <div class="col-xs-20">
                                <div class="contestList__entriesTitle">Entries</div>
                                <div class="contestList__entries">{{ contest.total_participants }}/{{ contest.max_participants }}</div>
                            </div>
                            <div class="col-xs-15">
                                <div class="contestList__buyinTitle">Buy-In</div>
                                <div class="contestList__buyin">${{ contest.buy_in }}</div>
                            </div>
                        </div>
                    </a>
                    <a v-else v-link="{ path: '/contest/' + contest.contest_id + '/players' }">
                        <div class="container-fluid">
                            <div class="col-xs-20">
                                <img class="contestList__img" :src="URL.base + '/public/image/events/' + contest.event_image_file" alt="{{ contest.event_name }} Image">
                            </div>
                            <div class="col-xs-45 contestList__infoWarp">
                                <div class="contestList__date">{{ contest.event_date }}</div>
                                <div class="contestList__name">{{ contest.event_short_name }}</div>
                                <div class="contestList__type">{{ contest.contest_type_name }}</div>
                            </div>
                            <div class="col-xs-20">
                                <div class="contestList__entriesTitle">Entries</div>
                                <div class="contestList__entries">{{ contest.total_participants }}/{{ contest.max_participants }}</div>
                            </div>
                            <div class="col-xs-15">
                                <div class="contestList__buyinTitle">Buy-In</div>
                                <div class="contestList__buyin">${{ contest.buy_in }}</div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <ul v-else>
                <li class="noResults">There are no contests currently avaiable for this event.</li>
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

    export default {

        props: ['working'],

        data() {
            return {
                contestsList: { 'contests':{} },
                contestsEntered: [],
                working: false,
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
                this.$http.get(URL.base + '/api/v1/event/' + this.$route.params.event_id + '/contests', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.contestsList = response.data;
                    this.working = false;
                }, function(err) {
                    console.log(err);
                    this.working = false;
                });

                this.$http.get(URL.base + '/api/v1/player/contests-entered', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.contestsEntered = response.data.contests;
                }, function(err) {
                    console.log(err);
                });
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