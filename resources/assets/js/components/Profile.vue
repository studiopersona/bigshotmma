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
            <fieldset>
                <legend>Change Your Password</legend>
                <div class="profile__inputWrap form__row">
                    <input v-model="player.oldPassword" type="password" placeholder="Old Password">
                </div>
                <div class="profile__inputWrap form__row">
                    <input v-model="player.newPassword" type="password" placeholder="New Password">
                </div>
            </fieldset>
            <div class="profile__depositLink">
                Update your <a v-link="{ path: '/deposit-profile' }">Deposit Method</a>
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
    import localforage from 'localforage';

    export default {

        data() {
            return {
                player: {
                    name: '',
                    email: '',
                    avatar: '',
                    oldPassword: '',
                    newPassword: '',
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
                this.$http.get(URL.base + '/api/v1/profile', {}, {
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
            },

            profileUpdate() {
                this.working = true;

                this.$http.post(URL.base + '/api/v1/profile', {
                    player_name: this.player.name,
                    avatar: this.player.avatar,
                    old_password: this.player.oldPassword,
                    new_password: this.player.newPassword,
                }, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
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