<template>
	<div :working="working" :loggedIn.sync="false">
		<header class="pageHeader">
			<h1 class="pageHeader__header">Reset Password</h1>
			<h4 class="pageHeader__subheader">
				Request a password reset
			</h4>
		</header>
		<div class="loginForm form">
			<div class="container-fluid">
		    	<div class="row form__row">
			        <div class="col-xs-100">
	                    <label for="email" class="col-md-4 control-label">
	                    	<span class="visuallyhidden">E-Mail Address</span>
	                    	<input id="email" type="email" class="form-control" name="email" v-model="email" placeholder="EMAIL ADDRESS">
	                    </label>
	                </div>
	            </div>
	      		<div class="row form__row" v-if="error">
					<div :class="alertClasses" :type="alertType">
						<p>{{ error }}</p>
						<span class="Alert__close" @click="error = flase">x</span>
					</div>
				</div>
	            <div class="row">
	                <div class="col-xs-100">
	                    <button type="submit" class="button button--primary" @click="submit()">Send Request</button>
	                </div>
	            </div>
		    </div>
		    <div :class="loaderClasses">
	            <div class="js-global-loader loader">
	                <svg viewBox="0 0 32 32" width="32" height="32">
	                    <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
	                </svg>
	            </div>
	        </div>
	    </div>
		<div class="container-fluid">
			<div class="col-xs-100">
				<div class="logo">
					<img :src="URL.base + '/public/image/logo.png'" alt="Big Shot Fantasy MMA Logo">
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

		components: {

		},

		props: ['working'],

		data() {
			return {
				email: '',
				error: '',
				URL: window.URL,
			}
		},

		created() {
			this.working = true;
		},

		ready() {
			this.working = false;
		},

		watch: {

		},

		computed: {

	        loaderClasses() {
	            return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
	        },

		},

		methods: {

			submit() {
				this.$http.post(this.URL.base + '/api/v1/forgot-password', {}, {})
				.then(function(res) {
					let response = res.json()


				})
				.catch(function(err) {
					console.log('')
					console.log(err)
				})
			},

		},

		events: {

		}
	}
</script>
