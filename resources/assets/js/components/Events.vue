<template>
    <div>
        <h2>This is the events component</h2>
    </div>
    <pre>
        {{ $data | json }}
    </pre>
</template>

<script>
    import auth from '../auth';
    export default {
        data() {
            return {
                eventsList: ''
            }
        },

        methods: {
            ready() {
                this.$http
                .get('http://edward.dev/bsmma/api/v1/events', (data) => {
                    this.eventsList = data;
                }, {
                    // Attach the JWT header
                    headers: auth.getAuthHeader()
                })
                .error((err) => console.log(err))
                }
            },

        route: {
            // Check the users auth status before
            // allowing navigation to the route
            canActivate() {
                return auth.user.authenticated
            }
        }
    };
</script>