<template>
    <div :working="working">
        <header class="pageHeader">
            <h1 class="pageHeader__header">Choose an Event</h1>
            <h4 class="pageHeader__subheader">
                Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
            </h4>
        </header>
        <div class="container-fluid">
            <div class="col-xs-100">

            </div>
        </div>
    </div>
</template>

<script>
    import auth from '../auth';
    import {router} from '../index';

    export default {

        props: ['working'],

        data() {
            return {
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

            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
        },
    };
</script>