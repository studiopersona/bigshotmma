<template>
    <header class="pageHeader" :working.sync="working">
        <h1 class="pageHeader__header">Choose an Event</h1>
        <h4 class="pageHeader__subheader">
            Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
        </h4>
    </header>
    <div class="eventList">
        <ul>
            <li class="eventList__item" v-for="event in eventsList.events">
                <a v-link="{ path: 'event/' + event.event_id + '/contests' }" href="#">
                    <img class="eventList__img" src="http://edward.dev/bsmma/public/image/events/ufn-rothwell-vs-dos-santos.jpg" alt="{{ event.event_name }} Image">
                    <div class="eventList__name">{{ event.event_name }}</div>
                    <div class="eventList__date">{{ event.date }} {{ event.time }}</div>
                </a>
            </li>
        </ul>
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
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))
        },

        methods: {
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