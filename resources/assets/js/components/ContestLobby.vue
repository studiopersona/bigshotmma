<template>
    <header class="pageHeader" :working.sync="working">
        <h1 class="pageHeader__header">Contest Lobby</h1>
        <h4 class="pageHeader__subheader">
            {{ participantsList.contest.event_short_name }} - {{ participantsList.contest.event_date }}
        </h4>
    </header>
    <div class="contestDetails">
        <table>
            <tbody>
                <tr>
                    <td>Buy in: ${{ participantsList.contest.buy_in }}</td>
                    <td>Entries: {{ participantsList.contest.total_participants }}/{{ participantsList.contest.max_participants }}</td>
                </tr>
                <tr>
                    <td>Prize Pool: ${{ participantsList.contest.prize_pool }}</td>
                    <td class="contestDetails__type">{{ participantsList.contest.contest_type_name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="participantsList">
        <ul class="stripped-list">
            <li class="participantsList__item" v-for="participant in participantsList.participants">
                <div class="container-fluid">
                    <div class="col-xs-15 participantsList__img">
                        <img src="http://edward.dev/bsmma/public/image/player.jpg">
                    </div>
                    <div class="col-xs-40 participantsList__name">
                        {{ participant.palyer_name }}
                    </div>
                    <div class="col-xs-25 participantsList__record">
                        {{ participant.wins }} - {{ participant.losses }}
                    </div>
                    <div class="col-xs-20 participantsList__wins">
                        {{ participant.win_percentage }}%
                    </div>
                </div>
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
                participantsList: { 'participants':[] },
                contest: {},
                working: false,
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/contest/' + this.$route.params.contest_id + '/participants', (data) => {
                this.participantsList = data;
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