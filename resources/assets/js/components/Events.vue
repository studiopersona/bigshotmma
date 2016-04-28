<template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Choose an Event</h1>
            <h4 class="pageHeader__subheader">
                Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
            </h4>
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

    export default {

        props: ['working'],

        data() {
            return {
                eventsList: { 'events':[] },
                working: false,
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
                playerIsValid: true,
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
                this.$http.get( URL.base + '/api/v1/events', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
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

        route: {
            // Check the users auth status before
            // allowing navigation to the route
            canActivate() {

            }
        }
    };
</script>