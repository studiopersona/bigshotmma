<template>
    <div :working="working">
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
                    <div  class="col-xs-offset-2 col-xs-32">
                        <select v-model="player.state">
                            <option value="">State</option>
                            <option value="AL">AL</option>
                            <option value="AK">AK</option>
                            <option value="AZ">AZ</option>
                            <option value="AR">AR</option>
                            <option value="CA">CA</option>
                            <option value="CO">CO</option>
                            <option value="CT">CT</option>
                            <option value="DE">DE</option>
                            <option value="DC">DC</option>
                            <option value="FL">FL</option>
                            <option value="GA">GA</option>
                            <option value="HI">HI</option>
                            <option value="ID">ID</option>
                            <option value="IL">IL</option>
                            <option value="IN">IN</option>
                            <option value="IA">IA</option>
                            <option value="KS">KS</option>
                            <option value="KY">KY</option>
                            <option value="LA">LA</option>
                            <option value="ME">ME</option>
                            <option value="MD">MD</option>
                            <option value="MA">MA</option>
                            <option value="MI">MI</option>
                            <option value="MN">MN</option>
                            <option value="MS">MS</option>
                            <option value="MO">MO</option>
                            <option value="MT">MT</option>
                            <option value="NE">NE</option>
                            <option value="NV">NV</option>
                            <option value="NH">NH</option>
                            <option value="NJ">NJ</option>
                            <option value="NM">NM</option>
                            <option value="NY">NY</option>
                            <option value="NC">NC</option>
                            <option value="ND">ND</option>
                            <option value="OH">OH</option>
                            <option value="OK">OK</option>
                            <option value="OR">OR</option>
                            <option value="PA">PA</option>
                            <option value="RI">RI</option>
                            <option value="SC">SC</option>
                            <option value="SD">SD</option>
                            <option value="TN">TN</option>
                            <option value="TX">TX</option>
                            <option value="UT">UT</option>
                            <option value="VT">VT</option>
                            <option value="VA">VA</option>
                            <option value="WA">WA</option>
                            <option value="WV">WV</option>
                            <option value="WI">WI</option>
                            <option value="WY">WY</option>
                        </select>
                    </div>
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
    </div>
    <section :class="['syncAlert', alert.show ? 'show' : '']">
        <p :class="['syncAlert__body', alert.class]">{{ alert.body }}</p>
        <button @click="alertClose" type="button" class="alertModal__close">x</button>
    </section>
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';
    import localforage from 'localforage';

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
                    merchant: 0,
                },
                cardInfo: {
                    number: '',
                    expMonth: 0,
                    expYear: 0,
                    cvv: '',
                },
                paypalEmail: '',
                expYears: [],
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
                this.$http.get(URL.base + '/api/v1/deposit-profile', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    console.log(response);
                    this.player = response.data.profile;
                    this.working = false;
                }, function(err) {
                    console.log(err);
                    this.working = false;
                });
                this.expirationYears();
            },

            expirationYears() {
                var thisYear,
                    years = [],
                    now = new Date();

                years[0] = thisYear = now.getFullYear();

                for ( var i=1; i < 10; ++i ) {
                    now.setFullYear(thisYear + i)
                    years[i] = now.getFullYear();
                }

                this.expYears = years;
            },

            depositUpdate() {
                var vm = this;

                this.working = true;

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
                    }, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        vm.flash(response.data);
                        vm.working = false;
                    }, function(err) {
                        console.log(err);
                        vm.working = false;
                    });
                });
            },

            flash(response) {
                this.alert.body = response.msg;
                this.alert.show = true;

                this.alert.class = ( response.success ) ? 'syncAlert--success' : 'syncAlert--failed';
            },

            alertClose(e) {
                this.alert.show = false;
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },
    };
</script>