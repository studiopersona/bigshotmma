 <template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">{{ player.name }}</h1>
            <h4 class="pageHeader__subheader">
                Fund your account with a deposit.
            </h4>
        </header>
        <div class="deposit form container-fluid">
            <div class="col-xs-100 amountSelectionWrap">
                <label
                    @click.prevent="selectAmount(0, 5, $event)"
                    for="amount5"
                    class="fightsList__pickButton"
                >
                    <input
                        v-model="deposit.amount.dollars"
                        type="radio"
                        value="5"
                        name="amount"
                        id="amount5"
                    >
                    <span :class="[amountIndicators[0] ? 'show' : '']" data-amount="5">$5</span>
                </label>
                <label
                    @click.prevent="selectAmount(1, 10, $event)"
                    for="amount10"
                    class="fightsList__pickButton"
                >
                    <input
                        v-model="deposit.amount.dollars"
                        type="radio"
                        value="10"
                        name="amount"
                        id="amount10"
                    >
                    <span :class="[amountIndicators[1] ? 'show' : '']" data-amount="10">$10</span>
                </label>
                <label
                    @click.prevent="selectAmount(2, 25, $event)"
                    for="amount25"
                    class="fightsList__pickButton"
                >
                    <input
                        v-model="deposit.amount.dollars"
                        type="radio"
                        value="25"
                        name="amount"
                        id="amount25"
                    >
                    <span :class="[amountIndicators[2] ? 'show' : '']" data-amount="25">$25</span>
                </label>
                <label
                    @click.prevent="selectAmount(3, 50, $event)"
                    for="amount50"
                    class="fightsList__pickButton"
                >
                    <input
                        v-model="deposit.amount.dollars"
                        type="radio"
                        value="50"
                        name="amount"
                        id="amount50"
                    >
                    <span :class="[amountIndicators[3] ? 'show' : '']" data-amount="50">$50</span>
                </label>
                <label
                    @click.prevent="selectAmount(4, 100, $event)"
                    for="amount100"
                    class="fightsList__pickButton"
                >
                    <input
                        v-model="deposit.amount.dollars"
                        type="radio"
                        value="100"
                        name="amount"
                        id="amount100"
                    >
                    <span :class="[amountIndicators[4] ? 'show' : '']" data-amount="100">$100</span>
                </label>
            </div>
            <div class="deposit__amountWrap">
                The following amount:
                <div class="deposit__amount">${{ deposit.amount.dollars }}<sup>.{{ deposit.amount.cents }}</sup></div>
            </div>
            <div v-if="player.merchant == 1">
                <div v-if="player.stripeId !== 0 ">
                    <div class="deposit__billedTo">
                        will be billed to your<br>
                        <span class="larger-text"><span class="larger-text">Credit Card</span> ending in <span class="larget-text">{{ player.ccDigits }}</span></span>
                    </div>
                    <div class="deposit__cardTypeImage">
                        <img :src="URL.base + '/public/image/creditcards/' + player.ccImageName" alt="{{ player.ccType }}">
                    </div>
                </div>
                <div v-else >
                    <div class="deposit__billedTo">
                        will be billed to your<br>
                        <span class="larger-text"><span class="larger-text">Credit Card</span></span>
                    </div>
                    <div class="profile__inputWrap form__row">
                        <input v-model="cardInfo.name" type="text" placeholder="Name on Card">
                    </div>
                    <div class="profile__inputWrap form__row">
                        <input v-model="cardInfo.number" type="text" placeholder="Card Number">
                    </div>
                    <div class="profile__inputWrap form__row container-fluid">
                        <div class="col-xs-36">
                            <select v-model="cardInfo.expMonth" class="card-expiry-month stripe-sensitive required">
                                <option value="00">Month</option>
                                <option value="01">01-Jan</option>
                                <option value="02">02-Feb</option>
                                <option value="03">03-Mar</option>
                                <option value="04">04-Apr</option>
                                <option value="05">05-May</option>
                                <option value="06">06-Jun</option>
                                <option value="07">07-Jul</option>
                                <option value="08">08-Aug</option>
                                <option value="09">09-Sep</option>
                                <option value="10">10-Oct</option>
                                <option value="11">11-Nov</option>
                                <option value="12">12-Dec</option>
                            </select>
                        </div>
                        <div class="col-xs-36 col-xs-offset-2">
                            <select v-model="cardInfo.expYear" class="card-expiry-year stripe-sensitive required">
                                <option value="0000">Year</option>
                                <option v-for="year in expYears" value="{{ year}}">{{ year }}</option>
                            </select>
                        </div>
                        <div class="col-xs-24 col-xs-offset-2">
                            <label for="cvv">
                                <span class="visuallyhidden">CVV/CCV</span>
                                <input v-model="cardInfo.cvv" type="text" placeholder="CVV/CCV">
                            </label>
                        </div>
                    </div>
                    <div class="profile__inputWrap form__row container-fluid">
                        <label for="storeCC">
                            <input v-model="persist.cc" type="checkbox" value="1" id="storeCC"> <span class="checkboxText">Please store this information for future use.</span>
                        </label>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="deposit__billedTo">
                    will be billed to your<br>
                    <span class="larger-text"><span class="larger-text">PayPal Account</span></span>
                </div>
                <div class="profile__inputWrap form__row">
                    <input v-model="player.paypalEmail" type="text" placeholder="PayPal Email">
                </div>
                <div class="profile__inputWrap form__row container-fluid">
                    <label for="storePP">
                        <input v-model="persist.paypal" type="checkbox" value="1" id="storePP"> <span class="checkboxText">Please store this information for future use.</span>
                    </label>
                </div>
            </div>
            <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                <div class="deposit__explanation">(Includes a $0.35 Transaction Fee)</div>
                    <button
                        type="button"
                        class="button button--primary"
                        @click="makeDeposit"
                    >Deposit</button>
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
    import D from '../libs/d.js';
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
                    stripeId: 0,
                    ccDigits: 0,
                    ccType: 0,
                    ccImageName: '',
                    paypalEmail: '',
                },
                deposit: {
                    amount: {
                        dollars: 5,
                        cents: 35,
                        total: this.depositTotal,
                    },
                },
                cardInfo: {
                    number: '',
                    expMonth: 0,
                    expYear: 0,
                    cvv: '',
                },
                paypalEmail: '',
                persist: {
                    cc: false,
                    paypal: false,
                },
                expYears: [],
                amountIndicators: [
                    1,
                    0,
                    0,
                    0,
                    0
                ],
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

            removeAmountIndicators() {
                var deferred = D();

                for (var i=0; i < this.amountIndicators.length; ++i) {
                    this.amountIndicators.$set(i, 0);
                }

                deferred.resolve();

                return deferred.promise;
            },

            selectAmount(index, amount, e) {
                this.amountIndicators.$set(0, 0);
                this.amountIndicators.$set(1, 0);
                this.amountIndicators.$set(2, 0);
                this.amountIndicators.$set(3, 0);
                this.amountIndicators.$set(4, 0);

                this.amountIndicators.$set(index, 1);

                this.deposit.amount.dollars = amount;
            },

            makeDeposit() {
                var data = {
                        amount: this.depositTotal,
                    },
                    vm = this;

                this.working = true;

                // if player would like to save payment info
                if ( this.persist.paypal ) {
                    data.persist = true;
                    data.paypal = this.player.paypalEmail
                };

                localforage.getItem('id_token').then(function(token) {
                    vm.$http.post(URL.base + '/api/v1/deposit', data, {
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
            depositTotal() {
                return (this.deposit.amount.dollars * 100) + this.deposit.amount.cents;
            },
        },
    };
</script>