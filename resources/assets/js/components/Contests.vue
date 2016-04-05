<template>
    <header class="pageHeader">
        <h1 class="pageHeader__header">Enter a Contest</h1>
        <h4 class="pageHeader__subheader">
            Over <span class="pageHeader--highlight">{{ poolTotal }}</span> Total Prize Pool
        </h4>
    </header>
    <div class="contestList">
        <ul>
            <li @click="showItem()" class="contestList__item" v-for="contest in contentList.events">
                <div class="container-fluid">
                    <div class="col-xs-20">
                        <img class="contestList__img" src="http://edward.dev/bsmma/public/image/events/ufn-rothwell-vs-dos-santos.jpg" alt="{{ event.event_name }} Image">
                    </div>
                    <div class="col-xs-45">
                        <div class="contestList__date"></div>
                        <div class="contestList__name"></div>
                        <div class="contestList__type"></div>
                    </div>
                    <div class="col-xs-25">
                        <div class="contestList__entriesTitle"></div>
                        <div class="contestList__entries"></div>
                    </div>
                    <div class="col-xs-10">
                        <div class="contestList__buyinTitle"></div>
                        <div class="contestList__buyin"></div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import auth from '../auth';
    export default {
        data() {
            return {
                contestsList: '',
                imageSrc: 'public/images/events/' + this.imageName,
                imageName: '',
            }
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/contests', (data) => {
                this.contestsList = data;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))
        },

        methods: {
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