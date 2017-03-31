<template>
	<div v-if="contestList.length">
		<h4>Contests Running</h4>
		<div class="contestWidgetList">
			<div v-for="contest in contestList" class="contestWidgetList__item">
				<a v-link="{ path: '/contest/' + contest.contest_id + '/players' }">
	                <img class="contestList__img" :src="URL.base + '/public/image/events/' + contest.event_image_file" alt="{{ contest.event_name }} Image">
	            </a>
	        </div>
		</div>
	</div>
</template>

<script>

import auth from '../auth';
import {router} from '../index';
import localforage from 'localforage';

export default {

	components: {

	},

	props: [],

	data() {
		return {
			contestList: [],
			URL: window.URL,
		}
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

            this.$http.get(URL.base + '/api/v1/contests', {}, {
                // Attach the JWT header
                headers: { 'Authorization' : 'Bearer ' + token }
            })
            .then(function(response) {
            	console.log('contest widget', response)
                this.contestList = response.data.contests;
            })
            .catch(function(err) {
                console.log(err);
                this.working = false;
            });
        },
	},

	events: {

	}
}
</script>