<template>
    <div id="templateWrap" :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">{{ player.name }}</h1>
            <h4 class="pageHeader__subheader">
                Update your <a v-link="{ path: '/deposit-profile' }">Deposit Method</a>
            </h4>
        </header>
        <div class="profile form">
            <div v-if="player.avatar === ''" class="profile__image">
                <img :src="URL.base + '/public/image/avatar/male.jpg'">
            </div>
            <div v-else class="profile__image">
                <img :src="URL.base + '/public/image/avatar/' + player.avatar">
            </div>
            <fieldset>
                <legend>Change Avatar</legend>
                 <div class="profile__inputWrap form__row container-fluid">
                    <input type="file" name="file" @change="onFileChange" placeholder="Avatar">
                </div>
            </fieldset>
            <fieldset>
                <legend>Change Username</legend>
                <div class="profile__inputWrap form__row">
                    <input v-model="player.name" type="text">
                </div>
            </fieldset>
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
                var vm = this;

                this.working = true;

                localforage.getItem('id_token').then(function(token) {
                    vm.$http.post(URL.base + '/api/v1/profile', {
                        player_name: vm.player.name,
                        avatar: vm.player.avatar,
                        old_password: vm.player.oldPassword,
                        new_password: vm.player.newPassword,
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

            onFileChange (e) {
                var file = e.target.files[0] || e.dataTransfer.files[0]
                var vm = this
                var uploadData = new FormData()

                // this.player.avatar = file.name
                uploadData.append('file', file)

                // upload the file to the server here
                localforage.getItem('id_token')
                .then(function(token) {
                    vm.$http.post(URL.base + '/api/v1/upload-avatar-image', uploadData, {
                        headers: {
                            'Authorization' : 'Bearer ' + token
                        }
                    })
                    .then(function(response) {
                        // console.log(response)
                        if (response.data.success) {
                            vm.player.avatar = response.data.fileName
                        }
                    })
                    .catch(function(err) {
                        console.log(err)
                    })
                })
                .catch(function(err) {
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