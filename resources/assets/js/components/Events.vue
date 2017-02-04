<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Choose an Event</h1>
            <!--<h4 class="pageHeader__subheader">
                Total Prize Pool $<span class="pageHeader--highlight">{{ poolTotal.toFixed(2) }}</span>
            </h4>-->
        </header>
        <div class="container-fluid">
            <div class="col-xs-100">
                <div class="eventList clearfix">
                    <ul>
                        <li class="eventList__item" v-for="event in eventsList.events">
                            <a v-link="{ path: '/event/' + event.event_id + '/contests' }" href="#">
                                <img class="eventList__img" :src="URL.base + '/public/image/events/' + event.event_image_file" alt="{{ event.event_name }} Image">
                                <div class="eventList__name">{{ event.event_name }}</div>
                                <div class="eventList__date">{{ event.date }} {{ event.time }}</div>
                            </a>
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
            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

    export default {

        props: ['working', 'loggedIn'],

        data() {
            return {
                eventsList: { 'events':[] },
                poolTotal: 0,
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
            var vm = this,
                params;

            localforage.getItem('id_token').then(function(token) {
                if ( token ) {
                    params = auth.parseToken(token);
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                        vm.fetch(token);
                        vm.$root.loggedIn = true;
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
                        vm.$root.loggedIn = true;
                    });
                }, function(err) {
                    router.go('login');
                });
            },

            fetch(token) {
                // get the player name and balance after login since this lookup will have failed in the app vue
                this.$http.get( URL.base + '/api/v1/player-name', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(
                    function(response) {
                        this.$root.playersName = response.data.player_name;
                    },
                    function(err) {
                        console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/player-balance', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(
                    function(response) {
                        this.$root.playersBalance = response.data.playerBalance;
                    },
                    function(err) {
                        console.log(err);
                });

                this.$http.get( URL.base + '/api/v1/events', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(
                    function(response) {
                        this.eventsList = response.data;
                        this.working = false;
                    },
                    function(err) {
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