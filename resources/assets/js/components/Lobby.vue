<template>
	<div>
		<standings v-if="deadlinePast" :standings-list="standingsList" :player-id="playerId"></standings>
		<contest-lobby v-else :participants-list="participantsList"></contest-lobby>
        <div :class="loaderClasses">
            <div class="js-global-loader loader">
                <svg viewBox="0 0 32 32" width="32" height="32">
                    <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
                </svg>
            </div>
        </div>
	</div>
</template>

<script>
    import auth from '../auth'
    import {router} from '../index'
    import localforage from 'localforage'
	import Standings from './Standings.vue'
	import ContestLobby from './ContestLobby.vue'

	export default {

		components: {
			'standings': Standings,
			'contest-lobby': ContestLobby,
		},

		data() {
            return {
            	participantsList: [{
                    contest: {
                        event_short_name: '',
                        event_date: '',
                        buy_in: '',
                        total_participants: '',
                        max_participants: '',
                        prize_pool: '',
                        contest_type_name: '',
                        contest_id: '',
                    },
                }],
                standingsList:[],
                playerId: 0,
            	deadlinePast: false,
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
            }
        },

        created() {
        	this.working = true
            console.log(this.$route.params.contest_id)
        },

        ready() {
        	var vm = this,
                params

            localforage.getItem('id_token')
            .then(function(token) {
                if ( token ) {
                    params = auth.parseToken(token)
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                        vm.fetch(token)
                    } else {
                        vm.tokenRefresh(token)
                    }
                } else {
                    router.go('login')
                }
            })
            .catch(function(err) {
                console.log(err)
            })
        },

        methods: {
        	tokenRefresh(token) {
                var vm = this;

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    localforage.setItem('id_token', response.data.token).then(function() {
                        vm.fetch(token);
                    })
                })
                .catch(function(err) {
                    router.go('login');
                })
            },

            fetch(token) {
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id, {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    var now = new Date(),
                        deadline;

                    this.participantsList = response.data.participants
                    deadline = new Date(this.entry_deadline)
                    this.deadlinePast = ( now.getTime() > deadline.getTime() )

                    if ( now.getTime() > deadline.getTime() ) {
                        this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/standings/1', {}, {
                            // Attach the JWT header
                            headers: { 'Authorization' : 'Bearer ' + token }
                        }).then(function(response) {
                            // console.log(response.data);
                            this.standingsList = response.data.data[0].standings;
                            this.playerId = response.data.data[0].player;
                            // this.contest = response.data.data[0].contest[0];
                            this.working = false;
                        }, function(err) {
                            console.log(err);
                        });
                    } else {
                        this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/players', {}, {
                            // Attach the JWT header
                            headers: { 'Authorization' : 'Bearer ' + token }
                        })
                        .then(function(response) {
                            this.participantsList = response.data.participants
                            this.working = false
                        })
                        .catch(function(err) {
                            console.log(err)
                        })
                    }

                    this.working = false
                })
                .catch(function(err) {
                    console.log(err)
                })
        	}
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },

        },
	}
</script>