<template>
	<div class="registerForm">
		<div class="container-fluid">
			<div class="alert alert-danger" v-if="error">
	        	<p>{{ error }}</p>
	      	</div>
			<div class="row form__row">
				<div class="col-xs-100">
					<label for="email">
						<span class="visuallyhidden">Email</span>
						<input type="email" placeholder="EMAIL ADDRESS" v-model="credentials.username">
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
						<input type="text" placeholder="PLAYER NAME" v-model="credentials.playerName">
					</label>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-100">
					<button @click="submit()" type="submit" class="button button--primary">Sign Up</button>
				</div>
			</div>
		</div>
	</div>
  </template>

  <script>
  import auth from '../auth'
  export default {
    data() {
      return {
        // We need to initialize the component with any
        // properties that will be used in it
        credentials: {
          username: '',
          password: '',
          playerName: '',
        },
        error: ''
      }
    },
    methods: {
      submit() {
        var credentials = {
          username: this.credentials.username,
          password: this.credentials.password,
          playerName: this.credentials.playerName,
        }
        // We need to pass the component's this context
        // to properly make use of http in the auth service
        auth.login(this, credentials, 'events')
      }
    }

  }
  </script>