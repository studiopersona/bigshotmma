<template>
	<header class="pageHeader" :working.syc="working">
		<h1 class="pageHeader__header">Player Sign-up</h1>
		<h4 class="pageHeader__subheader">
			Already have an account?
			<a v-link="{ path: '/login' }">Login</a>
		</h4>
	</header>
	<div class="registerForm form">
		<div class="container-fluid">
			<div class="row form__row">
				<div class="col-xs-100">
					<label for="email">
						<span class="visuallyhidden">Email</span>
						<input type="email" placeholder="EMAIL ADDRESS" v-model="credentials.email">
					</label>
				</div>
			</div>
			<div class="row form__row">
				<div class="col-xs-100">
					<label for="password">
						<span class="visuallyhidden">Password</span>
						<input type="password" placeholder="PASSWORD"  v-model="credentials.password">
					</label>
				</div>
			</div>
			<div class="row form__row">
				<div class="col-xs-100">
					<label for="playerName">
						<span class="visuallyhidden">Player Name</span>
						<input type="text" placeholder="PLAYER NAME" v-model="credentials.player_name">
					</label>
				</div>
			</div>
			<div class="row form_row">
				<p>iPhone users please turn off <a target="_blank" href="https://support.apple.com/en-us/HT203036">Private Mode</a> to sign-up</p>
			</div>
			<div class="row form__row" v-if="error">
				<div :class="alertClasses" :type="alertType">
					<p>{{ error }}</p>
					<span class="Alert__close" @click="error = flase">x</span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-100">
					<button @click="submit()" type="submit" class="button button--primary">Sign Up</button>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="col-xs-100">
			<div class="logo">
				<img :src="URL.base + '/public/image/logo.png'" alt="Blood Sport Fantasy MMA Logo">
			</div>
		</div>
	</div>
  </template>

  <script>
	import auth from '../auth'
	export default {

		props: ['working'],

		data() {
			return {
				// We need to initialize the component with any
				// properties that will be used in it
				credentials: {
					email: '',
					password: '',
					player_name: '',
				},
				error: '',
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
			}
		},

		methods: {
			submit() {
				var credentials = {
					email: this.credentials.email,
					password: this.credentials.password,
					player_name: this.credentials.player_name,
				}
				// We need to pass the component's this context
				// to properly make use of http in the auth service
				auth.signup(this, credentials, 'events')
			}
		},

		computed: {
			alertClasses() {
				var type = this.alertType;

				return {
					'Alert': true,
					'Alert--Success': type == 'success',
					'Alert--Error': type == 'error'
				};
			},

			loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
		},
	}
  </script>