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
							<input type="email" placeholder="EMAIL ADDRESS" v-model="credentials.email">
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
					<img :src="URL.base + '/public/image/logo.jpg'" alt="Blood Sport Fantasy MMA Logo">
				</div>
			</div>
		</div>
	</div>
</template>

  <script>
  	import auth from '../auth';
    import {router} from '../index';

	export default {

  		props: ['working'],

		data() {
			return {

				credentials: {
					email: '',
					password: ''
				},
				error: '',
				alertType: '',
				working: false,
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
			}
		},

		ceated() {
			this.working = true;
		},

		ready() {
			if ( ! auth.validate() ) {
				this.tokenRefresh();
			} else {
				router.go('/events');
			}
			this.working = false;
		},

		methods: {
			tokenRefresh() {
                var vm = this;

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    localStorage.setItem('id_token', response.data.token);
                    router.go('/events');
                }, function(err) {
                    router.go('login');
                });
            },

			submit() {
				var credentials = {
					email: this.credentials.email,
					password: this.credentials.password
				}

				// We need to pass the component's this context
				// to properly make use of http in the auth service
				auth.login(this, credentials, 'events')
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