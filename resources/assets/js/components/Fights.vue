<template>
    <header class="pageHeader" :working.sync="working">
        <h1 class="pageHeader__header">Choose an Event</h1>
        <h4 class="pageHeader__subheader">
            Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
        </h4>
    </header>
    <div class="fightsList">
        <ul>
            <li class="fightsList__item" v-for="fight in fightsList.fights">

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
                fightsList: { 'fights':[] },
                working: false,
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/contest/' + this.$route.contest_id + '/fights', (data) => {
                this.fightsList = data;
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