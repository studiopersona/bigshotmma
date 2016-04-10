<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Picks</h1>
            <h4 class="pageHeader__subheader">{{ picksList[0].event.event_short_name }}</h4>
        </header>
        <div class="contestDetails">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Buy in:</span> ${{ picksList[0].contest.buy_in }}
                    </div>
                    <div class="col-xs-50 text-right">
                        <span class="contestDetails__title">Entries:</span> {{ picksList[0].contest.total_participants }}/{{ picksList[0].contest.max_participants }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-50">
                        <span class="contestDetails__title">Prize Pool:</span> ${{ picksList[0].contest.prize_pool  }}
                    </div>
                    <div class="col-xs-50 text-right">
                        Winning
                    </div>
                </div>
            </div>
        </div>
        <div class="fightPicksList">
            <ul class="stripped-list">
                <li
                    class="fightPicksList__item"
                    @click="toggleDetails(pick.pick_id, $event)"
                    v-for="pick in picksList"
                    data-pick-id="{{ pick.pick_id }}"
                >
                    <div class="container-fluid">
                        <div class="col-xs-15">
                            <img class="fightPicksList__image" :src="'public/image/fighters/' +  pick.fighter.image">
                        </div>
                        <div class="col-xs-40">
                            <div class="fightPicksList__fighterName">
                                {{ pick.fighter.name }}
                            </div>
                            <div :class="['fightPicksList__odds']">
                                {{ pick.fighter.odds  }}
                            </div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Result</div>
                            <div v-if="!results.length" class="fightPicksList__stat">---</div>
                            <div v-else class="fightPicksList__stat">Actual Stat Here</div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Finish</div>
                            <div v-if="!results.length" class="fightPicksList__stat">---</div>
                            <div v-else class="fightPicksList__stat">Actual Stat Here</div>
                        </div>
                        <div class="col-xs-15">
                            <div class="fightPicksList__title">Points</div>
                            <div v-if="!results.length" class="fightPicksList__stat">---</div>
                            <div v-else class="fightPicksList__stat">Actual Stat Here</div>
                        </div>
                    </div>
                    <div class="fightPicksList__details">
                        <div class="container-fluid">
                            <div v-if="!results.length" class="col-xs-100 fightPicksList__resultString">
                                Fight Results Not Available
                            </div>
                            <div v-else class="col-xs-100 fightPicksList__resultString">
                                Actual Results here
                            </div>
                            <div class="col-xs-100 fightPicksList__choicesTitle">
                                Choices
                            </div>
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/star.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">Favorite to Win</h4>
                                    <p v-show="results.length">

                                    </p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else class="col-xs-15 fightPicksList__points">
                                    result here
                                </div>
                            </div>
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/fist.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">{{ pick.finish.finish_type }}</h4>
                                    <p v-show="results.length"></p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else class="col-xs-15 fightPicksList__points">
                                    result
                                </div>
                            </div>
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/bell.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">Round {{ numberNames[parseInt(pick.round, 10) - 1] }}</h4>
                                    <p v-show="results.length"></p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else class="col-xs-15 fightPicksList__points">
                                    result
                                </div>
                            </div>
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img src="public/image/icons/stopwatch.png">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__selectionTitle">Minute {{ numberNames[parseInt(pick.minute, 10) - 1] }}</h4>
                                    <p v-show="results.length"></p>

                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points">
                                    ---
                                </div>
                                <div v-else class="col-xs-15 fightPicksList__points">
                                    result
                                </div>
                            </div>
                            <div class="col-xs-100 fightPicksList__row">
                                <div class="col-xs-10 fightPicksList__icon">
                                    <img :src="'public/image/powerups/' + pick.power_up.image">
                                </div>
                                <div class="col-xs-75">
                                    <h4 class="fightPicksList__powerUpName" style="color:{{ pick.power_up.color }}">{{ pick.power_up.power_up_name }}</h4>
                                    <p v-show="results.length"></p>
                                </div>
                                <div v-if="!results.length" class="col-xs-15 fightPicksList__points" style="color: {{ pick.power_up.color }}">
                                    ---
                                </div>
                                <div v-else class="col-xs-15 fightPicksList__points" style="color: {{ pick.power_up.color }}">
                                    result
                                </div>
                            </div>
                            <div class="col-xs-100 fightPicksList__totalWrap">
                                <div class="col-xs-50 fightPicksList__totalTitle">
                                    Total
                                </div>
                                <div class="col-xs-50 fightPicksList__totalValue">
                                    ---
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="container-fluid">
                <div class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        v-link="{ path: '/' }"
                    >View Results</button>
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
</template>

<script>
    import auth from '../auth';
    export default {

        props: ['working'],

        data() {
            return {
                picksList: [{
                    event: {
                        event_short_name: '',
                    },
                    contest: {
                        buy_in: '',
                        max_participants: '',
                        prize_pool: '',
                    },
                }],
                results: [],
                working: false,
                numberNames: ['One', 'Two', 'Three', 'Four', 'Five'],
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/contest/' + this.$route.params.contest_id + '/picks', (data) => {
                this.picksList = data.picks;
                console.log(this.picksList);
                this.working = false;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))

            this.$http.get('http://edward.dev/bsmma/api/v1/contest/' + this.$route.params.contest_id + '/results', (data) => {
                this.results = data.results;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => {
                console.log(err);
            });
        },

        methods: {
            toggleDetails(pickId, e) {
                e.preventDefault();
                console.log(pickId);
                var pickToToggle = document.querySelector('li.fightPicksList__item[data-pick-id="' + pickId + '"]');
                console.log(pickToToggle);
                pickToToggle.classList.toggle('show');
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },
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