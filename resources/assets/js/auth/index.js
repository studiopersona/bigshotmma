// src/auth/index.js

import {router} from '../index';
import D from '../libs/d';
import localforage from 'localforage';

// URL and endpoint constants
const API_URL = URL.base + '/api/v1/';
const LOGIN_URL = API_URL + 'authenticate';
const SIGNUP_URL = API_URL + 'register';
const REFRESH_URL = API_URL + 'refresh';

export default {

    login(context, creds, redirect) {
        this.initLocalforage();
        context.working = true;
        context.$http.post(LOGIN_URL, creds)
            .then(function(response) {
                //storage.setItem('id_token', response.data.token);
                localforage.setItem('id_token', response.data.token)
                .then(function(value) {
                    // Redirect to a specified route
                    if(redirect) {
                        router.go(redirect);
                    }
                })
                .catch(function(err) {
                    console.log(err);
                });
            })
            .catch(function(err) {
                if (err.data ) {
                    context.error = err.data.error.message;
                    context.alertType = 'error';
                    context.working = false;
                }
                console.log(err);
            });
    },

    signup(context, creds, redirect) {
        this.initLocalforage();

        context.$http.post(SIGNUP_URL, creds)
            .then(function(response) {
                localforage.setItem('id_token', respone.data.token)
                .then(function(value) {
                    if(redirect) {
                        router.go(redirect);
                    }
                })
                .catch(function(err) {
                    consloe.log(err);
                });
            })
            .catch(function(err) {
                if ( err.data ) {
                    context.error = err.data.error.message;
                    context.alertType = 'error';
                }
                console.log(err);
            });
    },

    logout() {
        this.initLocalforage();

        localforage.removeItem('id_token');
    },

    validate() {
        var params,
            vm = this;

        this.initLocalforage();

        localforage.getItem('id_token')
        .then(function(token) {
             if ( token ) {
                params = vm.parseToken(token)
                return Math.round(new Date().getTime() / 1000) <= params.exp;
            } else {
                return false;
            }
        })
        .catch(function(err) {
            console.log(err);
        });
    },

    // The object to be passed as a header for authenticated requests
    getAuthHeader() {
        var header,
            sendBackToken = function(token) {
                console.log('wtf2:', token);
                return {
                    'Authorization': 'Bearer ' + token,
                };
            },
            vm = this;

        this.initLocalforage();
        header = localforage.getItem('id_token').then(function(token) {
            return sendBackToken(token);
        });

        return;
    },

    parseToken(token) {
        var base64Url = token.split('.')[1],
            base64 = base64Url.replace('-', '+').replace('_', '/');

        return JSON.parse(window.atob(base64));
    },

    Storage(){
        var storage = {
            type: "localStorage",
            setItem: function setItem(key, value){
                if(!window.localStorage || !window.sessionStorage) {
                    throw new Error("really old browser");
                }
                try {
                    localStorage.setItem(key, value);
                }catch(err) {
                    try { //this should always work
                        storage.type = "sessionStorage";
                        sessionStorage.setItem(key, value);
                    }catch(sessErr) {
                        //do we dare try cookies?
                        throw sessErr;
                    }
                }
            },
            getItem: function getItem(key) {
                  return window[storage.type].getItem(key);
            },
            removeItem: function removeItem(key) {
                  return window[storage.type].removeItem(key);
            }
        };
        return storage;
    },

    initLocalforage() {
        localforage.config({
            name: 'Blood Sport MMA',
        });
    },
};