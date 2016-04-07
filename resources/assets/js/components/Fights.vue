<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Choose 5 Fights</h1>
            <h4 class="pageHeader__subheader">{{ fightList[0].event.event_short_name }}</h4>
        </header>
        <div class="fightsList">
            <ul>
                <li class="fightsList__item" v-for="fight in fightsList[0].fights">
                    <div class="container-fluid fightsList__fightersWrap">
                        <div class="col-xs-50 fightsList__fighterStatsWarp">
                            <div
                                class="fightsList__clickableArea"
                                @click.stop.prevent="selectFighter"
                                data-fighter-id="{{ fight.fighters[0].id }}"
                                data-fight-id="{{ fight.id }}"
                            ></div>
                            <div class="col-xs-40 fightsList__fighterWrap">
                                <div class="fightsList__fighterImgWrap" data-fight-id="{{ fight.id }}">
                                    <img
                                        :class="['fightsList__fighter', (fight.fighters[0].spread == fight.fighters[1].spread) ? 'favorite' : '']"
                                        :src="'public/image/fighters/' + fight.fighters[0].fighter_image_name"
                                        alt="{{ fight.fighters[0].firstname }} {{ fight.fighters[0].lastname }} Image"
                                        data-fighter-id="{{ fight.fighters[0].id }}"
                                    >
                                    <img
                                        class="fightsList__flag left"
                                        :src="'public/image/flags/' + fight.fighters[0].nationality.country_flag_uri"
                                        :show
                                        alt="{{ fight.fighters[0].nationality.country_name }} Flag"
                                    >
                                    <div class="fightsList__selectedIndicatorWrap">
                                        <div class="fightsList__selectedIndicator" data-fighter-id="{{ fight.fighters[0].id }}">
                                            <span v-if:"parseInt(fight.fighters[0].spread, 10) > parseInt(fight.fighters[1].spread, 10)">
                                                Favorite
                                            </span>
                                            <span v-else>
                                                Underdog
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-60">
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
                        </div><!-- .fightsList__fighterStatsWarp -->
                        <div class="col-xs-50 fightsList__fighterStatsWarp">
                            <div
                                class="fightsList__clickableArea"
                                @click.stop.prevent="selectFighter"
                                data-fighter-id="{{ fight.fighters[1].id }}"
                                data-fight-id="{{ fight.id }}"
                            ></div>
                            <div class="col-xs-60">
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
                            <div class="col-xs-40  fightsList__fighterWrap">
                                <div class="fightsList__fighterImgWrap" data-fight-id="{{ fight.id }}">
                                    <img
                                        :class="['fightsList__fighter', (fight.fighters[1].spread > fight.fighters[0].spread) ? 'favorite' : '']"
                                        :src="'public/image/fighters/' + fight.fighters[1].fighter_image_name"
                                        alt="{{ fight.fighters[1].firstname }} {{ fight.fighters[1].lastname }} Image"
                                        data-fighter-id="{{ fight.fighters[1].id }}"
                                    >
                                    <img
                                        class="fightsList__flag right"
                                        :src="'public/image/flags/' + fight.fighters[0].nationality.country_flag_uri"
                                        alt="{{ fight.fighters[1].nationality.country_name }} Flag"
                                    >
                                     <div class="fightsList__selectedIndicatorWrap">
                                        <div class="fightsList__selectedIndicator" data-fighter-id="{{ fight.fighters[1].id }}">
                                            <span v-if:"parseInt(fight.fighters[1].spread, 10) > parseInt(fight.fighters[0].spread, 10)">
                                                Favorite
                                            </span>
                                            <span v-else>
                                                Underdog
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- .fightsList__fighterImgWrap -->
                            </div><!-- .fightsList__fighterWrap -->
                        </div><!-- .fightsList__fighterStatsWarp -->
                    </div><!-- .fightsList__fightersWrap -->
                    <div class="fightsList__pick" data-fight-id="{{ fight.id }}">
                        <div class="container-fluid">
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">How will win?</div>
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
                                <div class="fightsList__pickHeader">Power-up (optional)</div>
                            </div>
                            <div class="col-xs-100 powerUpsList">
                                <div class="col-xs-23 col-xs-offset-2" v-for="powerUp in powerUps">
                                    <button class="powerUpList__btn" type="button" @click="confirmPowerUp" data-power-up="{{ powerUp.power_up_id }}">
                                        <span class="visuallyhidden">{{ powerUp.name }}</span>
                                        <img :src="'public/image/powerups/' + powerUp.image_name" alt="{{ powerUp.name }} Image">
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
                fightsList: [{
                    fights: [],
                    event: {
                        event_short_name: '',
                    },
                }],
                powerUps: {},
                finishes: {},
                working: false,
                powerUpModalClasses: ['powerUpModal'],
                fighterClasses: ['fightsList__fighter'],
                selectedPowerUp: {},
                powerUpId: '',
                playerPicks: [],
                currentFight: '',
            }
        },

        created() {
            this.working = true;
        },

        ready() {
            this.$http.get('http://edward.dev/bsmma/api/v1/contest/' + this.$route.params.contest_id + '/fights', (data) => {
                this.fightsList = data.fights;
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

            selectFighter(e) {
                var fightPickToShow,
                    fightPickIndicator,
                    fightPickImage,
                    resetFightSelectionImage,
                    resetFightSelectionIndicator;

                // check if working on the same fight that is open if so act accordingly
                // if not close all picks before opening again

                // reset the fight selection
                resetFightSelectionImage = document.querySelector('div.fightsList__fighterImgWrap[data-fight-id="' + e.target.dataset.fightId + '"] img.fightsList__fighter');
                resetFightSelectionIndicator = document.querySelector('div.fightsList__fighterImgWrap[data-fight-id="' + e.target.dataset.fightId + '"] div.fightsList__selectedIndicator');
                resetFightSelectionImage.classList.remove('selected');
                resetFightSelectionIndicator.classList.remove('show');
                // set new fight selection
                fightPickToShow = document.querySelector('div.fightsList__pick[data-fight-id="' + e.target.dataset.fightId + '"]');
                fightPickIndicator = document.querySelector('div.fightsList__selectedIndicator[data-fighter-id="' + e.target.dataset.fighterId + '"]');
                fightPickImage = document.querySelector('img.fightsList__fighter[data-fighter-id="' + e.target.dataset.fighterId + '"]');
                fightPickToShow.classList.toggle('show');
                fightPickIndicator.classList.toggle('show');
                fightPickImage.classList.toggle('selected');
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden';
            },

            fighterClasses() {
                return (fightsList[0].fight)
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