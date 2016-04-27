// src/auth/index.js

import {router} from '../index';
import D from '../libs/d';

// URL and endpoint constants
const API_URL = URL.base + '/api/v1/';
const LOGIN_URL = API_URL + 'authenticate';
const SIGNUP_URL = API_URL + 'register';
const REFRESH_URL = API_URL + 'refresh';

export default {

    // User object will let us check authentication status
    user: {
        authenticated: true
    },

    // Send a request to the login URL and save the returned JWT
    login(context, creds, redirect) {
        context.working = true;
        context.$http.post(LOGIN_URL, creds, (data) => {
            localStorage.setItem('id_token', data.token);

            this.user.authenticated = true;
            // Redirect to a specified route
            if(redirect) {
                router.go(redirect);
            }

        }).error((err) => {
            context.error = err.error.message;
            context.alertType = 'error';
            context.working = false;
        });
    },

    signup(context, creds, redirect) {
        context.$http.post(SIGNUP_URL, creds, (data) => {
            localStorage.setItem('id_token', data.token);

            this.user.authenticated = true;

            if(redirect) {
                router.go(redirect);
            }

        }).error((err) => {
            context.error = err.error.message;
            context.alertType = 'error';
        });
    },

    // To log out, we just need to remove the token
    logout() {
        localStorage.removeItem('id_token');
        this.user.authenticated = false;
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

    refresh(context, redirect) {

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