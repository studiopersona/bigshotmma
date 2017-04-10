<template>
	<div :working="working" :loggedIn.sync="false">
		<header class="pageHeader">
			<h1 class="pageHeader__header">Player Login</h1>
			<h4 class="pageHeader__subheader">
				Don't have an account yet?
				<a v-link="{ path: '/register' }">Sign Up</a>
			</h4>
		</header>
		<div class="loginForm form">
			<div class="container-fluid">
				<div class="row form__row">
					<div class="col-xs-100">
						<label for="email">
							<span class="visuallyhidden">Email</span>
							<input type="email" placeholder="EMAIL ADDRESS" v-model="credentials.email" autofocus>
						</label>
					</div>
				</div>
				<div class="row form__row">
					<div class="col-xs-100">
						<label for="password">
							<span class="visuallyhidden">Password</span>
							<input type="password" placeholder="PASSWORD" v-model="credentials.password">
						</label>
					</div>
					<div class="col-xs-50 col-xs-offset-50 forgotPassword">
						<a :href="URL.base + '/password/email'" tabindex="200">forgot password?</a>
					</div>
				</div>
				<div class="row form_row">
					<p>iPhone users please turn off <a target="_blank" tabindex="-1"href="https://support.apple.com/en-us/HT203036">Private Mode</a> to login</p>
				</div>
				<div class="row form__row" v-if="error">
					<div :class="alertClasses" :type="alertType">
						<p>{{ error }}</p>
						<span class="Alert__close" @click="error = flase">x</span>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-100">
						<button @click="submit()" type="submit" class="button button--primary">Login</button>
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
	// URL and endpoint constants
	const API_URL = URL.base + '/api/v1/';
	const LOGIN_URL = API_URL + 'authenticate';
	const SIGNUP_URL = API_URL + 'register';
	const REFRESH_URL = API_URL + 'refresh';

  	import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

	export default {

		data() {
			return {
				credentials: {
					email: '',
					password: ''
				},
				error: '',
				alertType: '',
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
                working: true,
			}
		},

		ready() {
			let vm = this;

			localforage.getItem('id_token')
			.then(function(token) {
				if (token) {
					let params = vm.parseToken(token)

					if ( Math.round(new Date().getTime() / 1000) >= params.exp ) {
						vm.tokenRefresh(token);
					} else {
						vm.$dispatch('logged-in', 'dashboard', token)
					}
				} else {
					vm.working = false
				}
			});
		},

		methods: {
			tokenRefresh(token) {
                let vm = this;

                if (token) {

	                this.$http.post(URL.base + '/api/v1/refresh', {}, {
	                    headers: { 'Authorization' : 'Bearer ' + token }
	                })
	                .then(function(response) {
	                    localforage.setItem('id_token', response.data.token).then(function() {
							vm.$dispatch('logged-in', '/dashboard', response.data.token)
	                    });
	                })
	                .catch(function(err) {
	                    router.go('login');
	                })

	            } else {
	            	this.working = false
	            }
            },

			submit() {
				let credentials = {
					email: this.credentials.email,
					password: this.credentials.password
				}

				this.working = true

				// We need to pass the component's this context
				// to properly make use of http in the auth service
				this.login(this, credentials, 'dashboard')
			},

			login(context, creds, redirect) {
		        this.initLocalforage();

		        context.$http.post(LOGIN_URL, creds)
	            .then(function(response) {
	                localforage.setItem('id_token', response.data.token)
	                .then(function(value) {
	                    // Redirect to a specified route
	                    if(redirect) {
	                    	context.$dispatch('logged-in', redirect, value)
	                    }
	                })
	                .catch(function(err) {
	                    console.log(err);
	                });
	            })
	            .catch(function(err) {
	                if (err.data ) {
	                    context.error = err.data.error.message;
	                    context.alertType = 'error';
	                    context.working = false;
	                }
	                console.log(err);
	            });
		    },

		    initLocalforage() {
		        localforage.config({
		            name: 'Big Shot MMA',
		        });
		    },

		    parseToken(token) {
		        if (token) {
		            var base64Url = token.split('.')[1],
		                base64 = base64Url.replace('-', '+').replace('_', '/');

		            return JSON.parse(window.atob(base64));
		        }
		    },
		},

    	computed: {
			alertClasses() {
				let type = this.alertType;

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