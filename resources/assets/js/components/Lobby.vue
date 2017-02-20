<template>
	<div>
		<standings v-if="deadlinePast" :participants-list="participantsList"></standings>
		<contest-lobby v-else :participants-list="participantsList"></contest-lobby>
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
            	deadlinePast: false,
            	working: false,
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
            }
        },

        created() {
        	this.working = true
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
                this.$http.get( URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/players', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    var now = new Date(),
                        deadline;

                    this.participantsList = response.data.participants
                    deadline = new Date(this.participantsList[0].contest.entry_deadline)
                    this.deadlinePast = ( now.getTime() > deadline.getTime() )
                })
                .catch(function(err) {
                    console.log(err)
                })
        	}
        },
	}
</script>