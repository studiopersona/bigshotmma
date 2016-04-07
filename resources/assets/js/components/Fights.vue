<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Choose 5 Fights</h1>
            <h4 class="pageHeader__subheader">
            </h4>
        </header>
        <div class="fightsList">
            <ul>
                <li class="fightsList__item" v-for="fight in fightsList.fights">
                    <div class="container-fluid">
                        <div class="col-xs-50">
                            <div class="col-xs-50">
                                <img class="fightsList__fighter" src="" alt="{{ fight.fighters[0].firstname }} {{ fight.fighters[0].lastname }} Image">
                                <img class="fightsList__flag" src="" alt="{{ fight.fighters[0].nationality.country_name }} Flag">
                            </div>
                            <div class="col-xs-50">
                                <div class="fightsList__fighterName">
                                    {{ fight.fighters[0].firstname }} {{ fight.fighters[0].lastname }}
                                </div>
                                <div class="fightsList__fighterHeight">
                                    {{ fight.fighters[0].height_ft }}' {{ fight.fighters[0].height_in }}"
                                    {{ fight.fighters[0].weight_lbs }}lbs.
                                </div>
                                <div class="fightsList__fighterRecord">
                                    {{ fight.fighters[0].wins }} - {{ fight.fighters[0].loses }} - {{ fight.fighters[0].draws }}
                                </div>
                                <div class="fightsList__spread">
                                    150
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-50">
                            <div class="col-xs-50">
                                <img class="fightsList__fighter" src="" alt="{{ fight.fighters[1].firstname }} {{ fight.fighters[1].lastname }} Image">
                                <img class="fightsList__flag" src="" alt="{{ fight.fighters[1].nationality.country_name }} Flag">
                            </div>
                            <div class="col-xs-50">
                                <div class="fightsList__fighterName">
                                    {{ fight.fighters[1].firstname }} {{ fight.fighters[1].lastname }}
                                </div>
                                <div class="fightsList__fighterHeight">
                                    {{ fight.fighters[1].height_ft }}' {{ fight.fighters[1].height_in }}"
                                    {{ fight.fighters[1].weight_lbs }}lbs.
                                </div>
                                <div class="fightsList__fighterRecord">
                                    {{ fight.fighters[1].wins }} - {{ fight.fighters[1].loses }} - {{ fight.fighters[1].draws }}
                                </div>
                                <div class="fightsList__spread">
                                    150
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fightsList__pick">
                        <div class="container-fluid">
                            <div class="col-xs-100">
                                How will win?
                            </div>
                            <div class="col-xs-100">
                                <select>
                                    <option value="0">Choose Finish</option>
                                    <option v-for="finish in finishes" value="{{ finish.id }}">{{ finish.name }}({{ finish.points }})</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <select>
                                    <option value="0">Choose Round</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <select>
                                    <option value="0">Choose Minute</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <div class="col-xs-25" v-for="powerUp in powerUps">
                                    <button type="button" @click="confirmPowerUp" data-power-up="{{ powerUp.power_up_id }}">
                                        <img src="" alt="{{ powerUp.name }}">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div :class="loaderClasses">
                <div class="js-global-loader loader">
                    <svg viewBox="0 0 32 32" width="32" height="32">
                        <circle id="spinner" cx="16" cy="16" r="14" fill="none"></circle>
                    </svg>
                </div>
            </div>
            <section :class="powerUpModalClasses">
                <h3 class="powerUpModal__title">{{ selectedPowerUp.title }}</h3>
                <img class="powerUpModal__image" src="" alt="{{ selectedPowerUp.title }}">
                <div class="powerUpModal__description">
                    {{{ selectedPowerUp.description }}}
                </div>
                <div class="powerUpModal__points">
                    +{{ selectedPowerUp.bonus_points }} Points
                </div>
                <div class="powerUpModal__apply">
                    <p>Apply this power up?</p>
                    <p>Failure results in a -{{ selectedPowerUp.penalty_points }} penalty</p>
                </div>
                <div class="powerUpModal__confirm">
                    <button class="powerUpModal__confirm--no">No</button>
                    <button class="powerUpModal__confirm--yes">Yes</button>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import auth from '../auth';
    export default {

        props: ['working'],

        data() {
            return {
                fightsList: {
                    fights: [],
                    event: {
                        event_short_name: '',
                    },
                },
                powerUps: {},
                finishes: {},
                working: false,
                powerUpModalClasses: ['powerUpModal'],
                selectedPowerUp: {},
                powerUpId: '',
                playerPicks: [],
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/contest/' + this.$route.params.contest_id + '/fights', (data) => {
                this.fightsList = data;
                this.working = false;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))

            this.$http.get('http://edward.dev/bsmma/api/v1/power-ups', (data) => {
                this.powerUps = data;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            })

            this.$http.get('http://edward.dev/bsmma/api/v1/finishes', (data) => {
                this.finishes = data;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            })
        },

        methods: {
            confirmPowerUp(e) {
                var newPowerUp;
                e.preventDefault();

                // if the content is already loaded don't load it again
                if ( this.powerUpId !== e.target.dataset.powerUp )
                {
                    this.powerUpId = e.target.dataset.powerUp;
                    newPowerUp = this.powerUps.find(this.findPowerUp);


                    this.selectedPowerUp.title = newPowerUp.name;
                    this.selectedPowerUp.rules = newPowerUp.description,
                    this.selectedPowerUp.image = newPowerUp.image_name;
                    this.selectedPowerUp.color = newPowerUp.color;
                    this.selectedPowerUp.bonus_points = newPowerUp.bonus_points;
                    this.selectedPowerUp.penalty_points = newPowerUp.penalty_points;
                }

                this.powerUpModalClasses.push('show');
            },

            findPowerUp(powerUp) {
                return powerUp.id === parseInt(this.powerUpId, 10);
            },

            powerUpModalClose(e) {
                e.preventDefault();

                this.powerUpModalClasses = ['powerUpModal'];
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