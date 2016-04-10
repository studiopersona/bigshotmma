<template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Enter a Contest</h1>
            <h4 class="pageHeader__subheader">
                Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
            </h4>
        </header>
        <div class="contestList">
            <ul class="stripped-list">
                <li class="contestList__item" v-for="contest in contestsList.contests">
                    <a v-link="{ path: '/contest/' + contest.contest_id + '/players' }" href="#">
                        <div class="container-fluid">
                            <div class="col-xs-20">
                                <img class="contestList__img" src="http://edward.dev/bsmma/public/image/events/ufn-rothwell-vs-dos-santos.jpg" alt="{{ contest.event_name }} Image">
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
                contestsList: { 'contests':{} },
                working: false,
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get(URL.base + '/api/v1/event/' + this.$route.params.event_id + '/contests', (data) => {
                this.contestsList = data;
                this.working = false;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))
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