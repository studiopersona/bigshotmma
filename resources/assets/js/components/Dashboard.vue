<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Dashboard</h1>
            <h4 class="pageHeader__subheader"></h4>
        </header>
        <div class="profile form">
            <div v-if="player.avatar === ''" class="profile__image">
                <img :src="URL.base + '/public/image/avatar/male.jpg'">
            </div>
            <div v-else class="profile__image">
                <img :src="URL.base + '/public/image/avatar/' + player.avatar">
            </div>
            <div class="profile__playerName">
                {{ player.name }}
            </div>
            <ul class="profile__details">
                <li class="profile__detail">
                    <label class="profile__detailsLabel profile__detailsHighlight">Balance:</label>
                    <span class="profile__detailsItem profile__detailsHighlight">$25.00</span>
                </li>
                <li class="profile__detail">
                    <label class="profile__detailsLabel">Points:</label>
                    <span class="profile__detailsItem">25000</span>
                </li>
                <li class="profile__detail">
                    <label class="profile__detailsLabel">Promos:</label>

                    <span v-if="player.promo.id === 0" class="profile__detailsItem">
                        <button class="promoRevealBtn" @click="showPromoField = !showPromoField">Have a Promo Code?</button>
                    </span>
                    <span v-else class="profile__detailsItem">
                        {{ player.promo.status }}
                    </span>
                </li>
                <li v-if="showPromoField" transition="fade" class="profile__detail">
                    <label for="bogoPromoCode">
                        <input id="bogoPromoCode" type="text" placeholder="ENTER PROMO CODE">
                    </label>
                    <div class="button-wrap">
                        <button class="button button--primary"@click="checkPromoCode">Verify Code</button>
                    </div>
                    <div if="promoCodeError.show" transition="fade" class="promoCodeError">
                        {{ promoCodeError.message }}
                    </div>
                </li>
            </ul>
            <div class="contestsList">
                <contest-list-widget></contest-list-widget>
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
    import ContestListWidget from './ContestListWidget.vue'

    export default {

        components: {
            'contest-list-widget': ContestListWidget,
        },

        data() {
            return {
                player: {
                    name: '',
                    avatar: '',
                    promo: {
                        id: 0,
                        status: '',
                    },
                },
                showPromoField: false,
                promoCodeError: {
                    show: false,
                    message: '',
                },
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

            localforage.getItem('id_token')
            .then(function(token) {
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
                })
                .then(function(response) {
                    localforage.setItem('id_token', response.data.token).then(function() {
                        vm.fetch(token);
                    });
                })
                .catch(function(err) {
                    router.go('login');
                });
            },

            fetch(token) {
                this.$http.get(URL.base + '/api/v1/profile', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                })
                .then(function(response) {
                    console.log('player', response)

                    this.player.name    = response.data.profile.name
                    this.player.avatar  = response.data.profile.avatar
                    this.player.points  = response.data.profile.points
                    this.player.balance = response.data.profile.balance
                    // this.player.promo   = response.data.profile.promo

                    this.working = false
                })
                .catch(function(err) {
                    console.log(err);
                    this.working = false;
                });
            },

            checkPromoCode() {
                let vm = this

                localforage.getItem('id_token')
                .then(function(token) {

                    this.$http.post(URL.base + '/api/v1/validate-promo-code', { promoCode: this.newPromoCode }, {
                        headers: { 'Authorization' : 'Bearer ' + token }
                    })
                    .then(function(res) {
                        let response = res.data

                        console.log('promo check', response)

                        if ( response.valid ) {
                            vm.player.promo.id = response.promo.id
                            vm.player.promo.status = response.promo.status

                            vm.showPromoField = false
                        } else {
                            vm.promoCodeError = response.error
                        }
                    })
                    .catch(function(err) {
                        console.log('there was an error while trying to validate the code')
                        console.log(err)
                    })
                })
                .catch(function(err) {
                    console.log('there was an error getting the token')
                    console.log(err)
                })
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