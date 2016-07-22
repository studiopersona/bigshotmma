 <template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">{{ player.name }}</h1>
            <h4 class="pageHeader__subheader">
                Fund your account with a deposit.
            </h4>
        </header>
        <div v-if="eligible" class="deposit form container-fluid">
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
            <!-- if credit card is preferred deppsit method -->
            <div v-if="player.merchant == 1">
                <!-- if player has a card saved -->
                <div v-if="player.stripeId !== 0 ">
                    <div class="deposit__billedTo">
                        will be billed to your<br>
                        <span class="larger-text"><span class="larger-text">Credit Card</span> ending in <span class="larget-text">{{ player.ccDigits }}</span></span>
                    </div>
                    <div class="deposit__cardTypeImage">
                        <img :src="URL.base + '/public/image/creditcards/' + player.ccImageName" alt="{{ player.ccType }}">
                    </div>
                </div>
                <!-- no card saved -->
                <div v-else >
                    <div class="deposit__billedTo">
                        will be billed to your<br>
                        <span class="larger-text"><span class="larger-text">Credit Card</span></span>
                    </div>
                </div>
                <!-- if player has card saved -->
                <div v-if="player.stripeId !== 0" class="profile__inputWrap form__row container-fluid">
                    <label for="newCard">
                        <input v-model="customerState.currentCustomer.newCard" type="checkbox" value="1" id="newCard">
                        <span class="checkboxText">Use a different card for this deposit</span>
                    </label>
                </div>
                <!-- no card saved or player wants to use a new card -->
                <div v-if="player.stripeId === 0 || customerState.currentCustomer.newCard">
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
                    <!-- if player has a card saved -->
                    <div v-if="player.stripeId !== 0" class="profile__inputWrap form__row container-fluid">
                        <label for="storeCC">
                            <input v-model="customerState.currentCustomer.saveNewCard" type="checkbox" value="1" id="storeCC"> <span class="checkboxText">Please replace my current card with this card.</span>
                        </label>
                    </div>
                    <!-- no card saved -->
                    <div v-else class="profile__inputWrap form__row container-fluid">
                        <label for="storeCC">
                            <input v-model="customerState.addCustomer" type="checkbox" value="1" id="storeCC"> <span class="checkboxText">Please store this information for future use.</span>
                        </label>
                    </div>
                </div>
            </div>
            <!-- paypal is preferred deposit method -->
            <div v-else>
                <div class="deposit__billedTo">
                    will be billed to your<br>
                    <span class="larger-text"><span class="larger-text">PayPal Account</span></span>
                </div>
            </div>
            <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <div class="deposit__explanation">(Includes a $0.35 Transaction Fee)</div>
                    <button
                        id="depositBtn"
                        type="button"
                        class="button button--primary"
                        @click="makeDeposit"
                    >Deposit</button>
                </div>
            </div>
        </div>
        <div v-else class="notEligible">
            <p>Sorry, your state is not eligible for paid contests, therefore you can not make deposits.</p>
            <p>Please check your <a v-link="{ path: '/deposit-profile' }">deposit profile</a> to make sure you have entered the correct state.</p>
            <p>You are welcome to participate in any of our free <a v-link="{ path: '/events' }">contests</a>.</p>
        </div>
        <div :class="loaderClasses">
            <div class="js-global-loader loader">
                <svg viewBox="0 0 32 32" width="32" height="32">
                    <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
                </svg>
            </div>
        </div>
        <section :class="['syncAlert', alert.show ? 'show' : '']">
            <p :class="['syncAlert__body', alert.class]">{{ alert.body }}</p>
            <button @click="alertClose" type="button" class="alertModal__close">x</button>
        </section>
    </div>
</template>

<script>
    import auth from '../auth'
    import {router} from '../index'
    import D from '../libs/d.js'
    import localforage from 'localforage'
    import paypalPay from 'paypal-rest-sdk'
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
                    fee: 35,
                },
                cardInfo: {
                    name: '',
                    number: '',
                    expMonth: 0,
                    expYear: 0,
                    cvv: '',
                    address: '',
                    city: '',
                    state: '',
                    zipcode: '',
                    firstname: '',
                    lastname: ''
                },
                paypalEmail: '',
                persistPaypal: false,
                customerState: {
                    isCustomer: false,
                    addCustomer: false,
                    removeCustomer: false,
                    currentCustomer: {
                        newCard: false,
                        saveNewCard: false,
                    },
                },
                expYears: [],
                amountIndicators: [
                    1,
                    0,
                    0,
                    0,
                    0
                ],
                eligible: false,
                eligibilityModalClasses: ['rulesSlider'],
                ineligiblStates: [],
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
                params,
                head,
                script

            this.ineligiblStates = ineligiblStates.states

            localforage.getItem('id_token').then(function(token) {
                if ( token ) {
                    params = auth.parseToken(token)
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                        vm.fetch(token)
                    } else {
                        vm.tokenRefresh(token)
                    }
                } else {
                    router.go('login')
                }
            })
            .catch(function(err) {
                console.log(err)
            })
        },

        methods: {
            tokenRefresh(token) {
                var vm = this

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    localforage.setItem('id_token', response.data.token).then(function() {
                        vm.fetch(token)
                    })
                }, function(err) {
                    router.go('login')
                })
            },

            fetch(token) {
                this.$http.get(URL.base + '/api/v1/deposit-profile', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    // console.log(response.data.profile)
                    this.player = response.data.profile
                    this.checkEligibility()
                    this.cardInfo.address = response.data.profile.address
                    this.cardInfo.city = response.data.profile.city
                    this.cardInfo.state = response.data.profile.state
                    this.cardInfo.zipcode = response.data.profile.zipcode
                    this.cardInfo.firstname = response.data.profile.firstname
                    this.cardInfo.lastname = response.data.profile.lastname
                    if ( this.player.stripeId !== 0 ) this.customerState.isCustomer = true
                    this.working = false
                }, function(err) {
                    console.log(err)
                    this.working = false
                })
                this.expirationYears()
                if ( this.$route.params.transactionId ) this.flash({
                    msg: 'Your PayPal deposit was successful. Your transaction id is ' + this.$route.params.transactionId,
                    success: true,
                })
            },

            expirationYears() {
                var thisYear,
                    years = [],
                    now = new Date()

                years[0] = thisYear = now.getFullYear()

                for ( var i=1; i < 10; ++i ) {
                    now.setFullYear(thisYear + i)
                    years[i] = now.getFullYear()
                }

                this.expYears = years
            },

            removeAmountIndicators() {
                var deferred = D()

                for (var i=0; i < this.amountIndicators.length; ++i) {
                    this.amountIndicators.$set(i, 0)
                }

                deferred.resolve()

                return deferred.promise
            },

            selectAmount(index, amount, e) {
                this.amountIndicators.$set(0, 0)
                this.amountIndicators.$set(1, 0)
                this.amountIndicators.$set(2, 0)
                this.amountIndicators.$set(3, 0)
                this.amountIndicators.$set(4, 0)

                this.amountIndicators.$set(index, 1)

                this.deposit.amount.dollars = amount
            },

            makeDeposit() {
                var data = {
                        amount: this.depositTotal,
                        fee: this.deposit.fee,
                        deposit: this.depositTotal - this.deposit.fee,
                        card: this.cardInfo,
                        merchantId: parseInt(this.player.merchant, 10),
                        customerState: this.customerState,
                        paypalEmail: this.player.paypalEmail,
                        persistPaypal: this.persistPaypal,
                    },
                    vm = this,
                    depositBtn = document.getElementById('depositBtn')

                this.working = true

                // disable the deposit button
                depositBtn.setAttribute('disabled', true)

                // if there is a player stripeId
                if ( this.player.stripeId !== 0 ) data.customerState.isCustomer = true

                localforage.getItem('id_token').then(function(token) {
                    vm.$http.post(URL.base + '/api/v1/deposit', data, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        if (response.data.approvalLink) window.location = response.data.approvalLink;
                        vm.flash(response.data)
                        // re-enable the deposit button
                        depositBtn.removeAttribute('disabled')
                        // Update players balance display
                        if (response.data.success) vm.$root.playersBalance = vm.$root.playersBalance + this.deposit.amount.dollars
                        vm.working = false
                    }, function(err) {
                        console.log(err)
                        vm.working = false
                    })
                })
            },

            checkEligibility() {
                this.eligible = ( this.ineligiblStates.indexOf(this.player.state) === -1 )
                if ( ! this.eligible ) this.eligibilityModalClasses = ['rulesSlider', 'show']
            },

            eligibilityModalClose() {
                this.eligibilityModalClasses = ['rulesSlider']
            },

            flash(response) {
                this.alert.body = response.msg || response.error.message
                this.alert.show = true

                // console.log('reponse', response)
                // console.log('alert body', this.alert.body)

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
            depositTotal() {
                // return the total charge for the deposit in cents
                return (this.deposit.amount.dollars * 100) + this.deposit.amount.cents
            },
            isCurrentCustomer() {
                return (this.player.stripeId !== 0)
            },
        },
    }
</script>