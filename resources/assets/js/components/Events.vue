<template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Choose an Event</h1>
            <h4 class="pageHeader__subheader">
                Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
            </h4>
        </header>
        <div class="eventList clearfix">
            <ul>
                <li class="eventList__item" v-for="event in eventsList.events">
                    <a v-link="{ path: '/event/' + event.event_id + '/contests' }" href="#">
                        <img class="eventList__img" src="http://edward.dev/bsmma/public/image/events/ufn-rothwell-vs-dos-santos.jpg" alt="{{ event.event_name }} Image">
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
</template>

<script>
    import auth from '../auth';
    export default {

        props: ['working'],

        data() {
            return {
                eventsList: { 'events':[] },
                imageSrc: 'public/images/events/' + this.imageName,
                imageName: '',
                working: false,
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/events', (data) => {
                this.eventsList = data;
                this.working = false;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))
        },

        methods: {
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