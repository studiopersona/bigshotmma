// src/auth/index.js

import {router} from '../index';
import D from '../libs/d';

// URL and endpoint constants
const API_URL = URL.base + '/api/v1/';
const LOGIN_URL = API_URL + 'authenticate';
const SIGNUP_URL = API_URL + 'register';
const REFRESH_URL = API_URL + 'refresh';

export default {

    login(context, creds, redirect) {
        context.working = true;
        context.$http.post(LOGIN_URL, creds)
            .then(function(response) {
                localStorage.setItem('id_token', response.data.token);

                // Redirect to a specified route
                if(redirect) {
                    router.go(redirect);
                }
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
        context.$http.post(SIGNUP_URL, creds)
            .then(function(response) {
                localStorage.setItem('id_token', response.data.token);

                if(redirect) {
                    router.go(redirect);
                }
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
        localStorage.removeItem('id_token');
    },

    validate() {
        var token = localStorage.getItem('id_token'),
            params;

        if ( token ) {
            params = this.parseToken(token)
            return Math.round(new Date().getTime() / 1000) <= params.exp;
        } else {
            return false;
        }
    },

    // The object to be passed as a header for authenticated requests
    getAuthHeader() {
        return {
            'Authorization': 'Bearer ' + localStorage.getItem('id_token')
        };
    },

    parseToken(token) {
        var base64Url = token.split('.')[1],
            base64 = base64Url.replace('-', '+').replace('_', '/');

        return JSON.parse(window.atob(base64));
    },
};