<template>
	<div>
	    <div id="templateWrap" :working="working">
	        <header class="pageHeader">
	            <h1 class="pageHeader__header">Make a Withdrawl</h1>
	            <h4 class="pageHeader__subheader withdrawl__subheader">
	                Current Balance: ${{ playersBalance.toFixed(2) }}
	            </h4>
	        </header>
	        <div class="withdrawl-form form">
	        	<p>How much would you like to withdraw?</p>
	            <div class="withdrawl__inputWrap form__row">
	                <span class="form__inputBefore">$</span><input class="width-50" v-model="amount" type="text" placeholder="50.00">
	            </div>

	            <div v-if="player.merchant === 2" class="form__row withdrawl-type">
	            	<div class="radioWrap">
	            		<label for="paypal">
	            			<img :src="URL.base + '/public/image/withdraw-paypal.png">
	            			<input type="radio" v-model="withdrawlMethod" id="paypal" value="PayPal">
	            		</label>
	            	</div>
	            	<div class="radioWrap">
	            		<label for="check">
	            			<img :src="URL.base + '/public/image/withdraw-check.png">
	            			<input type="radio" v-model="withdrawlMethod" id="check" value="Check" checked>
	            		</label>
	            	</div>
	            </div>
	        </div>
	        <!-- -->
	        <div v-if="player.merchant === 1 || withdrawlMethod === 'Check'" class="withdrawl__confirmation">
	        	<p class="withdrawl__instruction">Click 'Withdraw' to confirm your intent to have a check mailed to:</p>
	        	<div class="withdrawl__detailsWrap">
		        	<div class="withdrawl__mailingAddress">
		        		{{ player.firstname }} {{ player.lastname }}<br>
		        		{{ player.address }}<br>
		        		<span v-if="player.address2 !== ''">{{ player.address2 }}<br></span>
		        		{{ player.city }},{{ player.state }} {{ player.zipcode }}
		        	</div>
		        	<div  v-show="!isNaN(parseFloat(amount))" class="withdrawl__totalAmount">
		        		${{ parseFloat(amount).toFixed(2) }}
		        	</div>
		        </div>
	        </div>
	        <div v-if="player.merchant === 2 && widthdrawlMethod === 'PayPal'">
	        	<p class="withdrawl__instruction">Click 'Withdraw' to comfirm your intent to have a PayPal transfer sent to:</p>
	        	<div class="withdrawl__detailsWrap">
	        		<div class="withdrawl__paypalEmail">
	        			{{ player.paypalEmail }}
	        		</div>
		        	<div v-show="!isNaN(parseFloat(amount))" class="withdrawl__totalAmount">
		        		${{ parseFloat(amount).toFixed(2) }}
		        	</div>
		        </div>
		    </div>
		    <!-- -->
	        <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        @click="confirm"
                    >Withdraw</button>
                </div>
            </div>
	    </div>
        <section :class="alertNoticeClasses">
            <div>
                <h2 class="alertNotice__header">{{ alertNotice.header }}</h2>
                <div class="alertNotice__subject">{{{ alertNotice.subject }}}</div>
                <div class="alertNotice__body">
                    {{{ alertNotice.body }}}
                </div>
                <button @click="alertNoticeClose" type="button" class="alertModal__close">x</button>
            </div>
            <div v-if="alertNotice.action" class="button-wrap">
                <button @click="submit" type="button" class="button button--green">Confirm</button>
            </div>
        </section>
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
    import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

    export default {

        data() {
            return {
            	player: {},
            	amount: '',
            	playersBalance: 0.00,
            	withdrawlMethod: 'Check',
                alertNotice: {
                    header: 'Alert',
                    subject: '',
                    body: '',
                    action: false,
                },
                alertNoticeClasses: ['alertNotice'],
                working: false,
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
            }
        },

        created() {
            this.working = true;
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
            	this.working = true

                this.$http.get(URL.base + '/api/v1/player-balance', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    console.log(response);
                    this.playersBalance = response.data.playerBalance;
                }, function(err) {
                    console.log(err);
                });

                this.$http.get(URL.base + '/api/v1/deposit-profile', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    console.log(response)
                    this.player = response.data.profile
                    this.working = false
                }, function(err) {
                    console.log(err)
                    this.working = false
                });
            },

            confirm() {
            	let date = new Date()
            	let errors = []
            	console.log(parseFloat(this.amount))
            	console.log(parseFloat(this.playersBalance))
            	if ( parseFloat(this.amount) > parseFloat(this.playersBalance) ) errors.push('You\'re withdrawl cannot exceed your balance.')
            	if ( parseFloat(this.amount) === 0 || this.amount === '' ) errors.push('You must enter an amount to withdraw.')

            	if ( errors.length ) {
            		let errorString = ''

            		errors.forEach(function(err, index) {
            			errorString += '<li style="color: #2dbe0c;">' + err + '</li>'
            		})

            		this.alert({
            			header: 'Wait',
            			subject: 'We found something wrong.',
            			body: '<ul>' + errorString + '</ul><p>&nbsp;</p><p>Please remedy the error and re-submit your request.</p>',
            			action: false,
            		})
            	} else {
            		this.alert({
            			header: 'Withdrawl',
            			subject: '<div class="withdrawl__confirmAmountWrap"><div class="withdrawl__confirmAmount">$' + parseFloat(this.amount).toFixed(2) + '</div><div class="withdrawl__confirmTypeWrap"><div class="withdrawl__confirmType">' + this.withdrawlMethod + '</div><div class="withdrawl__confirmDate">' + date.getMonth() + '/' + date.getDate() + '/' + date.getFullYear() + '</div></div></div>',
            			body: '<p>Please note that all withdrawl requests are subject to audit though funds are typically issued within 48 hours.</p><p>Any attempts to defraud Big Shot MMA will result in account termination.',
            			action: true,
            		})
            	}
            },

            submit() {
            	let vm = this

            	localforage.getItem('id_token').then(function(token) {
                    vm.$http.post(URL.base + '/api/v1/withdraw', { amount: parseFloat(vm.amount) }, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        vm.flash(response.data)
                        vm.working = false
                        vm.fetch(token)
                    }, function(err) {
                        console.log(err)
                        vm.working = false
                    })
                })
            },

            flash(response) {
                this.alert.body = response.msg
                this.alert.show = true

                this.alert.class = ( response.success ) ? 'syncAlert--success' : 'syncAlert--failed'
            },

            alert(options) {
            	console.log('Alerting')
                this.alertNotice.header = (options.header) ? options.header : 'Alert'
                this.alertNotice.subject = (options.subject ) ? options.subject : 'You did something wrong.'
                this.alertNotice.body = (options.body ) ? options.body : ''

                this.alertNotice.action = (options.action) ? options.action : false

                this.alertNoticeClasses = ['alertNotice', 'show']
            },

            alertNoticeClose(e) {
                e.preventDefault()

                this.alertNoticeClasses = ['alertNotice']
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden'
            },
        },
    };
</script>