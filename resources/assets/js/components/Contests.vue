<template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Enter a Contest</h1>
            <h4 class="pageHeader__subheader">
                Total Prize Pool ${{ poolTotal }}
            </h4>
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
    import localforage from 'localforage';

    export default {

        props: ['working'],

        data() {
            return {
                contestsList: { 'contests':{} },
                contestsEntered: [],
                working: false,
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
                this.$http.get(URL.base + '/api/v1/event/' + this.$route.params.event_id + '/contests', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    this.contestsList = response.data;
                    this.working = false;
                }, function(err) {
                    console.log(err);
                    this.working = false;
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