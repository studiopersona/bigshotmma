<template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">{{ player.name }}</h1>
            <h4 class="pageHeader__subheader">
                Modify your profile information
            </h4>
        </header>
        <div class="profile form">
            <div class="profile__image">
                <img :src="URL.base + '/public/image/avatar/male.jpg'">
                <!--<button type="button">Upload New Image</button>-->
            </div>
            <div class="profile__inputWrap form__row">
                <input v-model="player.name" type="text">
            </div>
            <div class="profile__inputWrap form__row">
                <input v-model="player.email" type="text">
            </div>
            <div class="profile__depositLink">
                Update your <a :v-link="{ path: '/deposit-profile' }">Deposit Method</a>
            </div>
            <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        @click="profileUpdate"
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

    export default {

        data() {
            return {
                player: {
                    name: '',
                    email: '',
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
            if ( ! auth.validate() ) {
                this.tokenRefresh();
            } else {
                this.fetch();
            }
        },

        methods: {
            tokenRefresh() {
                var vm = this;

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    localStorage.setItem('id_token', response.data.token);
                    vm.fetch();
                }, function(err) {
                    router.go('login');
                });
            },

            fetch() {
                this.$http.get(URL.base + '/api/v1/profile', {}, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    console.log(response);
                    this.player = response.data.profile;
                    this.working = false;
                }, function(err) {
                    console.log(err);
                    this.working = false;
                });
            },

            profileUpdate() {
                this.working = true;

                this.$http.post(URL.base + '/api/v1/profile', {
                    player_name: this.player.name,
                    email: this.player.email,
                    avatar: this.player.avatar,
                }, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                }).then(function(response) {
                    this.flash(response.data);
                    this.working = false;
                }, function(err) {
                    console.log(err);
                    this.working = false;
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