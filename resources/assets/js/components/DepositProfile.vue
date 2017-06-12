<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">{{ player.name }}</h1>
            <h4 class="pageHeader__subheader">
                Modify your deposit profile information
            </h4>
        </header>
        <div class="profile form">
            <fieldset>
                <legend>Billing &amp; Payout Details</legend>
                <div class="profile__inputWrap form__row container-fluid">
                    <div  class="col-xs-100">
                        <select v-model="player.state" @change="checkEligibility">
                            <option value="">State</option>
                            <option value="AL">Alabama</option>
                            <option value="AK">Alaska</option>
                            <option value="AZ">Arizona</option>
                            <option value="AR">Arkansas</option>
                            <option value="CA">California</option>
                            <option value="CO">Colorado</option>
                            <option value="CT">Connecticut</option>
                            <option value="DE">Delaware</option>
                            <option value="DC">District Of Columbia</option>
                            <option value="FL">Florida</option>
                            <option value="GA">Georgia</option>
                            <option value="HI">Hawaii</option>
                            <option value="ID">Idaho</option>
                            <option value="IL">Illinois</option>
                            <option value="IN">Indiana</option>
                            <option value="IA">Iowa</option>
                            <option value="KS">Kansas</option>
                            <option value="KY">Kentucky</option>
                            <option value="LA">Louisiana</option>
                            <option value="ME">Maine</option>
                            <option value="MD">Maryland</option>
                            <option value="MA">Massachusetts</option>
                            <option value="MI">Michigan</option>
                            <option value="MN">Minnesota</option>
                            <option value="MS">Mississippi</option>
                            <option value="MO">Missouri</option>
                            <option value="MT">Montana</option>
                            <option value="NE">Nebraska</option>
                            <option value="NV">Nevada</option>
                            <option value="NH">New Hampshire</option>
                            <option value="NJ">New Jersey</option>
                            <option value="NM">New Mexico</option>
                            <option value="NY">New York</option>
                            <option value="NC">North Carolina</option>
                            <option value="ND">North Dakota</option>
                            <option value="OH">Ohio</option>
                            <option value="OK">Oklahoma</option>
                            <option value="OR">Oregon</option>
                            <option value="PA">Pennsylvania</option>
                            <option value="RI">Rhode Island</option>
                            <option value="SC">South Carolina</option>
                            <option value="SD">South Dakota</option>
                            <option value="TN">Tennessee</option>
                            <option value="TX">Texas</option>
                            <option value="UT">Utah</option>
                            <option value="VT">Vermont</option>
                            <option value="VA">Virginia</option>
                            <option value="WA">Washington</option>
                            <option value="WV">West Virginia</option>
                            <option value="WI">Wisconsin</option>
                            <option value="WY">Wyoming</option>
                        </select>
                    </div>
                </div>
                <div class="profile__inputWrap form__row container-fluid">
                    <label for="firstname" class="col-xs-100">
                        <span class="visuallyhidden">First Name</span>
                        <input v-model="player.firstname" type="text" placeholder="First Name" id="firstname">
                    </label>
                </div>
                <div class="profile__inputWrap form__row container-fluid">
                    <label for="lastname" class="col-xs-100">
                        <span class="visuallyhidden">Last Name</span>
                        <input v-model="player.lastname" type="text" placeholder="Last Name" id="lastname">
                    </label>
                </div>
                <div class="profile__inputWrap form__row container-fluid">
                    <label for="address" class="col-xs-100">
                        <span class="visuallyhidden">Address</span>
                        <input v-model="player.address" type="text" placeholder="Address" id="address">
                    </label>
                </div>
                <div class="profile__inputWrap form__row container-fluid">
                    <label for="address2" class="col-xs-100">
                        <span class="visuallyhidden">Address Line 2</span>
                        <input v-model="player.address2" type="text" placeholder="Address Line 2" id="address2">
                    </label>
                </div>
                <div class="profile__inputWrap form__row container-fluid">
                    <label for="city" class="col-xs-66">
                        <span class="visuallyhidden">City</span>
                        <input v-model="player.city" type="text" placeholder="City" id="city">
                    </label>
                </div>
                <div class="profile__inputWrap form__row container-fluid">
                    <div class="col-xs-50">
                        <input v-model="player.zipcode" type="text" placeholder="Zip Code">
                    </div>
                </div>
            </fieldset>
            <fieldset>
                <legend>Preferred Payment Method</legend>
                <div class="profile__inputWrap form__row">
                    <select v-model="player.merchant">
                        <option value="0">Select Payment Method</option>
                        <option value="1">Credit Card</option>
                        <option value="2">PayPal</option>
                    </select>
                </div>
                 <div v-show="parseInt(player.merchant, 10) === 2" transition="fade" class="profile__inputWrap form__row container-fluid">
                    <label for="lastname" class="col-xs-100">
                        <span class="visuallyhidden">PayPal Account Email</span>
                        <input type="email" v-model="paypalEmail" placeholder="PayPal Account Email">
                    </label>
                </div>
            </fieldset>
            <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        @click="depositUpdate"
                    >Update</button>
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
        <section :class="eligibilityModalClasses">
            <div :class="['eligibilitySlider__slide', 'current']">
                <h3 class="rulesSlider__title">Eligibilty</h3>
                <div class="rulesSlider__icon">
                    <img :src="URL.base + '/public/image/info/eligibility.png'">
                </div>
                <div class="rulesSlider__description">
                    <p class="smaller-text">Daily fantasy sports (DFS) is legal in 41 US states, but residents of the following states are not eligible to participate in paid entry contests:</p>
                    <p>Alabama, Arizona, Hawaii, Idaho, Iowa, Louisiana, Montana, Nevada and Washington.</p>
                </div>
                <div class="button-wrap">
                    <button @click="eligibilityModalClose" type="button" class="button button--green">Got It</button>
                </div>
                <button @click="eligibilityModalClose" type="button" class="alertModal__close">x</button>
            </div>
        </section>
        <section :class="['syncAlert', alert.show ? 'show' : '']">
            <p :class="['syncAlert__body', alert.class]">{{ alert.body }}</p>
            <button @click="alertClose" type="button" class="alertModal__close">x</button>
        </section>
    </div>
</template>

<script>
    import auth from '../auth'
    import {router} from '../index'
    import localforage from 'localforage'
    import ineligiblStates from '../config/ineligibile-states.json'

    export default {

        data() {
            return {
                player: {
                    name: '',
                    email: '',
                    firstname: '',
                    lastname: '',
                    address: '',
                    address2: '',
                    city: '',
                    state: '',
                    zipcode: '',
                    merchant: 1,
                },
                cardInfo: {
                    number: '',
                    expMonth: 0,
                    expYear: 0,
                    cvv: '',
                },
                paypalEmail: '',
                expYears: [],
                ineligiblStates: [],
                eligible: false,
                eligibilityModalClasses: ['rulesSlider'],
                alert: {
                    body: '',
                    class: 'syncAlert--success',
                    show: false,
                },
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
                params;

            this.ineligiblStates = ineligiblStates.states

            localforage.getItem('id_token').then(function(token) {
                if ( token ) {
                    params = auth.parseToken(token);
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                        vm.fetch(token);
                    } else {
                        vm.tokenRefresh(token)
                    }
                } else {
                    router.go('login')
                }
            })
            .catch(function(err) {
                console.log(err)
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
                console.log(this.ineligiblStates)

                this.$http.get(URL.base + '/api/v1/deposit-profile', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    console.log(response)
                    this.player = response.data.profile
                    this.paypalEmail = response.data.profile.paypalEmail
                    this.checkEligibility()
                    this.working = false
                }, function(err) {
                    console.log(err)
                    this.working = false
                });
                this.expirationYears()
            },

            expirationYears() {
                var thisYear,
                    years = [],
                    now = new Date();

                years[0] = thisYear = now.getFullYear()

                for ( var i=1; i < 10; ++i ) {
                    now.setFullYear(thisYear + i)
                    years[i] = now.getFullYear()
                }

                this.expYears = years
            },

            depositUpdate() {
                let vm = this
                let errors = []
                let emailRe = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                let submit = function() {
                    localforage.getItem('id_token').then(function(token) {
                        vm.$http.post(URL.base + '/api/v1/deposit-profile', {
                            firstname: vm.player.firstname,
                            lastname: vm.player.lastname,
                            address: vm.player.address,
                            address2: vm.player.address2,
                            city: vm.player.city,
                            state: vm.player.state,
                            zipcode: vm.player.zipcode,
                            merchant_id: vm.player.merchant,
                            paypalEmail: vm.paypalEmail,
                        }, {
                            // Attach the JWT header
                            headers: { 'Authorization' : 'Bearer ' + token }
                        }).then(function(response) {
                            vm.flash(response.data)
                            vm.working = false
                        }, function(err) {
                            console.log(err)
                            vm.working = false
                        })
                    });
                }

                this.working = true

                if (parseInt(this.player.merchant, 10) === 2) {
                    if ( this.paypalEmail === '') errors.push('You must enter a PayPal email address ')
                    if ( !emailRe.test(this.paypalEmail) ) errors.push('Please enter a valid PayPal email address')
                }

                if ( errors.length > 0 ) {
                    this.displayErrors(errors)
                } else {
                    submit()
                }
            },

            displayErrors(errors) {
                this.working = false

                this.flash({ msg: errors[0] });
            },

            checkEligibility() {
                this.eligible = ( this.ineligiblStates.indexOf(this.player.state) === -1 )
                if ( ! this.eligible ) this.eligibilityModalClasses = ['rulesSlider', 'show']
            },

            eligibilityModalClose() {
                this.eligibilityModalClasses = ['rulesSlider']
            },

            flash(response) {
                this.alert.body = response.msg
                this.alert.show = true

                this.alert.class = ( response.success ) ? 'syncAlert--success' : 'syncAlert--failed'
            },

            alertClose(e) {
                this.alert.show = false
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden'
            },
        },
    }
</script>