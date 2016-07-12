<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <div class="container-fluid">
                <div class="col-xs-40">
                    <h1 class="pageHeader__header">My Picks</h1>
                    <h4 class="pageHeader__subheader pageHeader__subheader-toLeft pageHeader__subheader-uppercase">Power Ups:
                        <span :class="powerupIndicatorsClasses[0]"></span>
                        <span :class="powerupIndicatorsClasses[1]"></span>
                        <span :class="powerupIndicatorsClasses[2]"></span>
                    </h4>
                </div>
                <div class="col-xs-60">
                    <span @click="switchFight" id="fi0" class="fighterIndicators"></span>
                    <span @click="switchFight" id="fi1" class="fighterIndicators"></span>
                    <span @click="switchFight" id="fi2" class="fighterIndicators"></span>
                    <span @click="switchFight" id="fi3" class="fighterIndicators"></span>
                    <span @click="switchFight" id="fi4" class="fighterIndicators"></span>
                </div>
            </div>
        </header>
        <div class="fightsList">
            <ul class="stripped-list">
                <li class="fightsList__item" v-for="fight in fightsList[0].fights">
                    <div class="container-fluid fightsList__fightersWrap" data-fight-id="{{ fight.id }}">
                        <div class="col-xs-50 fightsList__fighterStatsWarp">
                            <div
                                class="fightsList__clickableArea"
                                @click.stop.prevent="selectFight"
                                data-fighter-id="{{ fight.fighters[0].id }}"
                                data-fight-id="{{ fight.id }}"
                            ></div>
                            <div class="col-xs-40 fightsList__fighterWrap">
                                <div class="fightsList__fighterImgWrap" data-fight-id="{{ fight.id }}">
                                    <img
                                        :class="['fightsList__fighter', (fight.fighters[0].pivot.odds < fight.fighters[1].pivot.odds) ? 'favorite' : '']"
                                        :src="'public/image/fighters/' + fight.fighters[0].fighter_image_name"
                                        alt="{{ fight.fighters[0].firstname }} {{ fight.fighters[0].lastname }} Image"
                                        data-fighter-id="{{ fight.fighters[0].id }}"
                                    >
                                    <img
                                        class="fightsList__powerup left"
                                        :src="'public/image/powerups/' + fightData[fight.id].powerupImage"
                                        data-fighter-id="{{ fight.fighters[0].id }}"
                                        data-fight-id="{{ fight.id }}"
                                        @click="confirmRemovePowerUp(fight.id, $event)"
                                    >
                                    <button @click="clearFight(fight.id, fight.fighters[0].id, $event)"
                                            type="button"
                                            class="fightsList__clearButton left"
                                            data-fighter-id="{{fight.fighters[0].id}}"
                                            >X<span class="visuallyhidden">Clear</span>
                                    </button>
                                    <div class="fightsList__selectedIndicatorWrap">
                                        <div :class="['fightsList__selectedIndicator', (parseInt(fight.fighters[0].pivot.odds, 10) < parseInt(fight.fighters[1].pivot.odds, 10)) ? 'favorite' : '']" data-fighter-id="{{ fight.fighters[0].id }}">
                                            <span>
                                                {{ (parseInt(fight.fighters[0].pivot.odds, 10) < parseInt(fight.fighters[1].pivot.odds, 10)) ? 'Favorite (+3)' : 'Underdog (+5)' }}
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
                                <div :class="['fightsList__spread', fight.fighters[0].pivot.odds < 0 ? 'favorite' : '']">
                                    {{ fight.fighters[0].pivot.odds > 0 ? '+' : '' }}{{ fight.fighters[0].pivot.odds }}
                                </div>
                            </div>
                        </div><!-- .fightsList__fighterStatsWarp -->
                        <div class="col-xs-50 fightsList__fighterStatsWarp">
                            <div
                                class="fightsList__clickableArea"
                                @click.stop.prevent="selectFight"
                                data-fighter-id="{{ fight.fighters[1].id }}"
                                data-fight-id="{{ fight.id }}"
                            ></div>
                            <div class="col-xs-60 right">
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
                                <div :class="['fightsList__spread', fight.fighters[1].pivot.odds < 0 ? 'favorite' : '']">
                                    {{ fight.fighters[1].pivot.odds > 0 ? '+' : '' }}{{ fight.fighters[1].pivot.odds }}
                                </div>
                            </div>
                            <div class="col-xs-40  fightsList__fighterWrap">
                                <div class="fightsList__fighterImgWrap" data-fight-id="{{ fight.id }}">
                                    <img
                                        :class="['fightsList__fighter', (parseInt(fight.fighters[1].pivot.odds, 10) < parseInt(fight.fighters[0].pivot.odds, 10)) ? 'favorite' : '']"
                                        :src="'public/image/fighters/' + fight.fighters[1].fighter_image_name"
                                        alt="{{ fight.fighters[1].firstname }} {{ fight.fighters[1].lastname }} Image"
                                        data-fighter-id="{{ fight.fighters[1].id }}"
                                    >
                                    <img
                                        class="fightsList__powerup right"
                                        :src="'public/image/powerups/' + fightData[fight.id].powerupImage"
                                        data-fighter-id="{{ fight.fighters[1].id }}"
                                        data-fight-id="{{ fight.id }}"
                                        @click="confirmRemovePowerUp(fight.id, $event)"
                                    >
                                    <button @click="clearFight(fight.id, fight.fighters[1].id, $event)"
                                            type="button"
                                            class="fightsList__clearButton right"
                                            data-fighter-id="{{fight.fighters[1].id}}"
                                            >X<span class="visuallyhidden">Clear</span>
                                    </button>
                                    <div class="fightsList__selectedIndicatorWrap">
                                        <div
                                            :class="['fightsList__selectedIndicator', (fight.fighters[1].pivot.odds < fight.fighters[0].pivot.odds) ? 'favorite' : '']"
                                            data-fighter-id="{{ fight.fighters[1].id }}">
                                            <span>
                                                {{ (parseInt(fight.fighters[1].pivot.odds, 10) < parseInt(fight.fighters[0].pivot.odds, 10)) ? 'Favorite (+3)' : 'Underdog (+5)' }}
                                            </span>
                                        </div>
                                    </div>
                                </div><!-- .fightsList__fighterImgWrap -->
                            </div><!-- .fightsList__fighterWrap -->
                        </div><!-- .fightsList__fighterStatsWarp -->
                    </div><!-- .fightsList__fightersWrap -->
                    <div class="fightsList__pick closed" id="{{ fight.id }}" data-fight-id="{{ fight.id }}">
                        <div class="container-fluid">
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">How will {{ currentFighterName }} win? <span class="fightsList__finishPointsIndicator">+{{ finishPoints }} Points</span></div>
                            </div>
                            <!-- finish selectors -->
                            <div class="col-xs-100">
                                <label
                                    v-for="finish in finishes"
                                    v-if="parseInt(finish.id, 10) !== 4"
                                    @click.prevent="selectDecision(fight.id, finish.id, $event)"
                                    for="finish{{finish.id}}"
                                    class="fightsList__pickButton"
                                >
                                    <input
                                        v-model="fightData[parseInt(fight.id, 10)].finishId"
                                        type="radio"
                                        value="{{ finish.id }}"
                                        name="finish"
                                        id="finish{{ finish.id }}"
                                        data-points="{{ finish.points }}"
                                    >
                                    <span data-fight-id="{{ fight.id }}">{{ finish.name }}</span>
                                </label>
                            </div>
                            <!-- round selector -->
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">Which round? <span class="fightsList__roundPointsIndicator">+{{ roundPoints }} Points</span></div>
                                <label
                                    v-for="round in parseInt(fight.rounds, 10)"
                                    @click.prevent="selectRound(fight.id, round + 1, $event)"
                                    for="round{{round + 1}}"
                                    class="fightsList__pickButton"
                                >
                                    <input
                                        v-model="fightData[parseInt(fight.id, 10)].round"
                                        type="radio"
                                        value="{{ round + 1 }}"
                                        name="round"
                                        id="round{{ round + 1 }}"
                                        data-points="2"
                                    >
                                    <span data-fight-id="{{ fight.id }}">{{ round + 1 }}</span>
                                </label>
                            </div>
                            <!-- minute selectors -->
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">Which minute? <span class="fightsList__minutePointsIndicator">+{{ minutePoints }} Point</span></div>
                                <label
                                    v-for="minute in 5"
                                    @click.prevent="selectMinute(fight.id, minute + 1, $event)"
                                    for="minute{{ minute + 1 }}"
                                    class="fightsList__pickButton"
                                >
                                    <input
                                        v-model="fightData[parseInt(fight.id, 10)].minute"
                                        type="radio"
                                        value="{{ minute + 1 }}"
                                        name="minute"
                                        id="minute{{ minute + 1 }}"
                                        data-points="1"
                                    >
                                    <span data-fight-id="{{ fight.id }}">{{ minute + 1 }}</span>
                                </label>
                            </div>
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">Power-up (optional) <span class="fightsList__powerupPointsIndicator">+{{ powerupPoints }} Points</span></div>
                            </div>
                            <div class="col-xs-100 powerUpsList">
                                <div class="col-xs-24 col-xs-offset-1" v-for="powerUp in powerUps">
                                    <button class="powerUpList__btn" type="button">
                                        <span class="visuallyhidden">{{ powerUp.name }}</span>
                                        <img
                                            :src="'public/image/powerups/' + powerUp.image_name"
                                            @click="confirmPowerUp"
                                            data-power-up="{{ powerUp.power_up_id }}"
                                            data-fight-id="{{ fight.id }}"
                                            alt="{{ powerUp.name }} Image"
                                        >
                                        <span :style="{color: powerUp.color}">{{ powerUp.name }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-100 button-wrap">
                                <button @click="closeFight(fight.id, $event)"
                                        type="button"
                                        class="button button--green"
                                >Done</button>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="container-fluid">
                <div id="save-button-wrap" class="col-xs-100 button-wrap">
                    <button
                        type="button"
                        class="button button--primary"
                        @click="commitPicks"
                    >Save Picks</button>
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
        <section :class="powerUpModalClasses">
            <h3 class="powerUpModal__title" :style="{color: selectedPowerUp.color}">{{ selectedPowerUp.title }}</h3>
            <img class="powerUpModal__image" :src="'public/image/powerups/' + selectedPowerUp.image_name" alt="{{ selectedPowerUp.title }}">
            <div class="powerUpModal__description">
                {{{ selectedPowerUp.description }}}
            </div>
            <div class="powerUpModal__points" :style="{color: selectedPowerUp.color}">
                +{{ selectedPowerUp.bonus_points }} points
            </div>
            <div class="powerUpModal__apply">
                <p class="powerUpModal__apply--big">Apply this power up?</p>
                <p :style="{color: selectedPowerUp.color}">Failure results in a -{{ selectedPowerUp.penalty_points }} penalty</p>
            </div>
            <div class="powerUpModal__confirm">
                <button @click="powerUpModalClose" class="powerUpModal__confirm--no">No</button>
                <button @click="selectPowerUp(selectedPowerUp.fightId, selectedPowerUp.id, selectedPowerUp.image_name, $event)" class="powerUpModal__confirm--yes">Yes</button>
            </div>
            <button @click="powerUpModalClose" type="button" class="powerUpModal__close">x</button>
        </section>
        <section :class="powerUpRemoveModalClasses">
            <h3 class="powerUpModal__title" :style="{color: selectedPowerUp.color}">{{ selectedPowerUp.title }}</h3>
            <img class="powerUpModal__image" :src="'public/image/powerups/' + selectedPowerUp.image_name" alt="{{ selectedPowerUp.title }}">
            <div class="powerUpModal__description">
                {{{ selectedPowerUp.description }}}
            </div>
            <div class="powerUpModal__points" :style="{color: selectedPowerUp.color}">
                +{{ selectedPowerUp.bonus_points }} points
            </div>
            <div class="powerUpModal__apply">
                <p class="powerUpModal__apply--big">Remove this power up?</p>
            </div>
            <div class="powerUpModal__confirm">
                <button @click="powerUpModalClose" class="powerUpModal__confirm--no">No</button>
                <button @click="removePowerUp" class="powerUpModal__confirm--yes">Yes</button>
            </div>
            <button @click="powerUpModalClose" type="button" class="powerUpModal__close">x</button>
        </section>
        <section :class="howToPlayModalClasses">
            <h3 class="howToPlayModal__title">How to Play</h3>
            <img class="howToPlayModal__image" :src="'public/image/logo.png'" alt="Blood Sport MMA">
            <div class="howToPlayModal__body">
                <p>Tap a fighters avatar to choose that fighter as the winner of that fight. Then, in the revealed selection area, choose how &amp; when you think the fight will end.</p>
                <p>Apply a power up to your chosen fighter by tapping its icon. A detail of the power up will appear and ask you to confirm your choice.</p>
                <p>For more information on scoring and game play please checkout the HOW TO PLAY section of your <a @click="showDashboard">Dashboard</a>.</p>
            </div>
            <div class="button-wrap">
                <button @click="howToPlayModalClose" type="button" class="button button--green">Got It</button>
            </div>
            <button @click="howToPlayModalClose" type="button" class="infoModal__close">x</button>
        </section>
        <section :class="alertNoticeClasses">
            <div>
                <h2 class="alertNotice__header">{{ alertNotice.header }}</h2>
                <div class="alertNotice__subject">{{ alertNotice.subject }}</div>
                <div class="alertNotice__body">
                    {{{ alertNotice.body }}}
                </div>
                <button @click="alertNoticeClose" type="button" class="alertModal__close">x</button>
            </div>
            <div v-if="alertNotice.action" class="button-wrap">
                <button @click="alertNoticeClose" type="button" class="button button--green">Got It</button>
            </div>
        </section>
    </div>
</template>

<script>
    import auth from '../auth'
    import {router} from '../index'
    import D from '../libs/d.js'
    import animatedScrollTo from '../libs/animatedScrollTo.js'
    import localforage from 'localforage'

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
                powerUpRemoveModalClasses: ['powerUpModal'],
                alertNoticeClasses: ['alertNotice'],
                howToPlayModalClasses: ['howToPlayModal'],
                selectedPowerUp: {
                    id: 0,
                    title: '',
                    description: '',
                    image_name: '',
                    color: '',
                    bonus_points: 0,
                    penalty_points: 0,
                },
                powerUpId: '',
                totalPowerUps: 0,
                playerPicks: [],
                fightData: [],
                fighterImages: [],
                powerupIndicatorsClasses: [
                    ['powerupIndicators'],
                    ['powerupIndicators'],
                    ['powerupIndicators'],
                ],
                finishPoints: 0,
                roundPoints: 2,
                minutePoints: 1,
                powerupPoints: 0,
                currentFightId: '',
                currentFighterId: '',
                findFighterId: 0,
                currentFighterName: '',
                alertNotice: {
                    header: 'Alert',
                    subject: '',
                    body: '',
                    action: false,
                },
                contestId: this.$route.params.contest_id,
                URL: {
                    base: window.URL.base,
                    current: window.URL.current,
                    full: window.URL.full,
                },
            }
        },

        created() {
            this.working = true
        },

        ready() {
            var vm = this,
                params

            localforage.getItem('id_token').then(function(token) {
                if ( token ) {
                    params = auth.parseToken(token)
                    if ( Math.round(new Date().getTime() / 1000) <= params.exp ) {
                        vm.fetch(token)
                    } else {
                        vm.tokenRefresh(token)
                    }
                } else {
                    router.go('login')
                }
            })
            .catch(function(err) {
                console.log(err)
            })
        },

        methods: {
            tokenRefresh(token) {
                var vm = this

                this.$http.post(URL.base + '/api/v1/refresh', {}, {
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    localforage.setItem('id_token', response.data.token).then(function() {
                        vm.fetch(token)
                    })
                }, function(err) {
                    router.go('login')
                })
            },

            fetch(token) {
                var vm = this

                this.$http.get(URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/check-for-picks', {}, {
                    // Attach the JWT header
                    headers: { 'Authorization' : 'Bearer ' + token }
                }).then(function(response) {
                    router.go('/contest/' + this.$route.params.contest_id + '/picks')
                }, function(err) {
                    this.$http.get(URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/fights', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        // console.log(response.data.fights)
                        this.fightsList = response.data.fights
                        this.initializeFightData(response.data.fights[0].fights)
                        this.working = false
                    }, function(err) {
                        console.log(err)
                    })

                    this.$http.get(URL.base + '/api/v1/power-ups', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        this.powerUps = response.data
                        // console.log(this.powerUps)
                    })

                    this.$http.get(URL.base + '/api/v1/finishes', {}, {
                        // Attach the JWT header
                        headers: { 'Authorization' : 'Bearer ' + token }
                    }).then(function(response) {
                        this.finishes = response.data
                    })
                })

                //if ( localStorage.getItem('newplayer') ) this.showHowToPlay()
                localforage.getItem('newplayer')
                .then(function(newplayer) {
                    if ( parseInt(newplayer, 10) === 1 ) vm.showHowToPlay()
                })

            },

            showDashboard() {
                this.$root.toggleMenu()
            },

            showHowToPlay() {
                this.howToPlayModalClasses.push('show')
                //localStorage.removeItem('newplayer')
                localforage.removeItem('newplayer')
            },

            howToPlayModalClose(e) {
                e.preventDefault()

                this.howToPlayModalClasses = ['howToPlayModal']
            },

            initializeFightData(fights) {
                var th = this

                fights.forEach(function(fight, i) {
                    th.fightData.$set(parseInt(fight.id, 10), {
                        finishId: 0,
                        round: 0,
                        minute: 0,
                        powerupId: 0,
                        powerupImage: '',
                    })

                    fight.fighters.forEach(function(fighter, j) {
                        th.fighterImages.push({
                            fighterId: fighter.id,
                            image: fighter.fighter_image_name,
                        })
                    })
                })
            },

            confirmPowerUp(e) {
                var newPowerUp
                e.preventDefault()

                this.powerUpId = e.target.dataset.powerUp
                newPowerUp = this.powerUps.find(this.findPowerUp)

                this.selectedPowerUp.id = newPowerUp.power_up_id
                this.selectedPowerUp.fightId = e.target.dataset.fightId
                this.selectedPowerUp.title = newPowerUp.name
                this.selectedPowerUp.description = newPowerUp.description
                this.selectedPowerUp.image_name = newPowerUp.image_name
                this.selectedPowerUp.color = newPowerUp.color
                this.selectedPowerUp.bonus_points = newPowerUp.bonus_points
                this.selectedPowerUp.penalty_points = newPowerUp.penalty_points

                this.powerUpModalClasses.push('show')
            },

            selectPowerUp(fightId, powerUpId, powerUpImage, e) {
                var findFight = function(playerPick) {
                        return parseInt(playerPick.fightId, 10) === parseInt(fightId, 10)
                    },
                    findPowerUp = function(powerup) {
                        return parseInt(powerup.power_up_id, 10) === parseInt(powerUpId, 10)
                    },
                    fightPick,
                    powerupData,
                    puIndicatorImage

                if ( this.totalPowerUps < 3 ) {
                    if (this.fightData[fightId].powerupId === 0) ++this.totalPowerUps
                    this.fightData[fightId].powerupId = powerUpId
                    this.fightData[fightId].powerupImage = powerUpImage
                    this.powerUpModalClose(e)
                    // sjow the poinst indicator
                    document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] .fightsList__powerupPointsIndicator').classList.add('show')
                    powerupData = this.powerUps.find(findPowerUp)
                    // console.log(powerupData)
                    this.powerupPoints = powerupData.bonus_points
                    // find fighter choosen for the fight and add show class to powerup image indicator
                    fightPick = this.playerPicks.find(findFight)
                    document.querySelector('img.fightsList__powerup[data-fight-id="' + fightId +'"][data-fighter-id="' + fightPick.fighterId + '"]').classList.add('show')

                    this.powerupIndicatorsClasses.$set(this.totalPowerUps - 1, ['powerupIndicators','active'])

                } else {
                    this.alert({
                        header: 'Heads Up!',
                        subject: 'Too Many Power Ups',
                        body: '<p>You can only apply 3 power ups per contest.</p><p>You can remove a power up by tapping the icon that appears over the fighter it is applied to.</p>',
                        action: true,
                    })
                }
            },

            confirmRemovePowerUp(fightId, e) {
                var newPowerUp
                e.preventDefault()

                this.powerUpId = this.fightData[fightId].powerupId
                newPowerUp = this.powerUps.find(this.findPowerUp)

                this.selectedPowerUp.id = newPowerUp.power_up_id
                this.selectedPowerUp.fightId = e.target.dataset.fightId
                this.selectedPowerUp.title = newPowerUp.name
                this.selectedPowerUp.description = newPowerUp.description
                this.selectedPowerUp.image_name = newPowerUp.image_name
                this.selectedPowerUp.color = newPowerUp.color
                this.selectedPowerUp.bonus_points = newPowerUp.bonus_points
                this.selectedPowerUp.penalty_points = newPowerUp.penalty_points

                this.powerUpRemoveModalClasses.push('show')
            },

            removePowerUp(e) {
                var powerupImageIndicator

                this.fightData[this.selectedPowerUp.fightId].powerupId = 0
                this.powerupImage = ''

                // remove points indicator
                document.querySelector('.fightsList__pick[data-fight-id="' + this.selectedPowerUp.fightId + '"] .fightsList__powerupPointsIndicator').classList.remove('show')

                // remove the show class form powerup images
                powerupImageIndicator = document.querySelectorAll('img.fightsList__powerup[data-fight-id="' + this.selectedPowerUp.fightId + '"]')
                for (var i = 0; i < powerupImageIndicator.length; ++i) {
                    powerupImageIndicator[i].classList.remove('show')
                }

                this.totalPowerUps--
                this.powerUpModalClose(e)

                this.powerupIndicatorsClasses.$set(this.totalPowerUps, ['powerupIndicators'])
            },

            findPowerUp(powerUp) {
                return powerUp.power_up_id === parseInt(this.powerUpId, 10)
            },

            powerUpModalClose(e) {
                e.preventDefault()

                this.powerUpModalClasses = ['powerUpModal']
                this.powerUpRemoveModalClasses = ['powerUpModal']
            },

            removeShowOnButtons(buttons) {
                var deferred = D()

                for (var i=0; i < buttons.length; ++i) {
                    buttons[i].classList.remove('show')
                }

                deferred.resolve()

                return deferred.promise
            },

            selectDecision(fightId, id, e) {
                var siblingButtons = document.querySelectorAll('.fightsList__pick[data-fight-id="' + fightId + '"] input[name="finish"] + span'),
                    findFight = function(fight) {
                        return fight.id === fightId
                    },
                    findFinish = function(finish) {
                        return parseInt(finish.id, 10) === parseInt(id, 10)
                    },
                    fightData,
                    finishData

                finishData = this.finishes.find(findFinish)
                this.finishPoints = finishData.points
                document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] .fightsList__finishPointsIndicator').classList.add('show')

                if ( parseInt(id, 10) === 3 ) {
                    fightData = this.fightsList[0].fights.find(findFight)
                    // console.log(finishData)
                    this.fightData[parseInt(fightId, 10)].round = parseInt(fightData.rounds, 10)
                    this.fightData[parseInt(fightId, 10)].minute = 5

                    this.removeShowOnButtons(document.querySelectorAll('.fightsList__pick[data-fight-id="' + fightId + '"] label.fightsList__pickButton > span')).then(function() {
                        e.target.classList.add('show')
                        // need to change the round selector to work with the number of rpunds form the database
                        document.querySelector('label[for="round' + fightData.rounds + '"] span[data-fight-id="' + fightId + '"]').classList.add('show')
                        document.querySelector('label[for="minute5"] span[data-fight-id="' + fightId + '"]').classList.add('show')
                    })

                    document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] .fightsList__roundPointsIndicator').classList.add('show')
                    document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] .fightsList__minutePointsIndicator').classList.add('show')

                } else {
                    this.removeShowOnButtons(siblingButtons).then(function() {
                        e.target.classList.add('show')
                    })
                }

                this.fightData[parseInt(fightId, 10)].finishId = id
            },

            selectRound(fightId, id, e) {
                var siblingButtons = document.querySelectorAll('.fightsList__pick[data-fight-id="' + fightId + '"] input[name="round"] + span')

                document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] .fightsList__roundPointsIndicator').classList.add('show')

                if ( this.fightData[parseInt(fightId, 10)].finishId !== 3 ) {
                    this.removeShowOnButtons(siblingButtons).then(function() {
                        e.target.classList.add('show')
                    })

                    this.fightData[parseInt(fightId, 10)].round = id
                }
            },

            selectMinute(fightId, id, e) {
                var siblingButtons = document.querySelectorAll('.fightsList__pick[data-fight-id="' + fightId + '"] input[name="minute"] + span')

                document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] .fightsList__minutePointsIndicator').classList.add('show')

                if ( this.fightData[parseInt(fightId, 10)].finishId !== 3 ) {
                    this.removeShowOnButtons(siblingButtons).then(function() {
                        e.target.classList.add('show')
                    })

                    this.fightData[parseInt(fightId, 10)].minute = id
                }
            },

            selectFight(e) {
                var checkPick = function(playerPick) {
                        return playerPick.fightId === e.target.dataset.fightId
                    },
                    clearBtnNode

                if ( this.playerPicks.length === 4 ) {
                    document.querySelector('#save-button-wrap').classList.add('show')
                    document.querySelector('.fightsList').classList.add('save-btn-visible')
                }

                if ( this.playerPicks.length === 5) {
                    if ( this.playerPicks.findIndex(checkPick) !== -1 ) {
                        this.switchFight(e)
                        this.updatePicks({
                            fighterId: e.target.dataset.fighterId,
                            fightId: e.target.dataset.fightId,
                            offset: e.target.getBoundingClientRect().top + window.scrollY - 200,
                        })
                    } else {
                        this.alert({
                            header: 'Heads Up',
                            body: '<p>You have already five (5) fighters, which is the limit. Clear an existing pick by tapping the <span class="fightsList__clearButtonExample">X</span> icon, then you can make another pick.</p>',
                            subject: 'Too Many Fights Picked',
                            action: true,
                        })
                    }
                    // show the clear button
                    clearBtnNode = document.querySelector('.fightsList__clearButton[data-fighter-id="' + e.target.dataset.fighterId + '"]')
                    clearBtnNode.classList.add('show')
                } else {
                    // show the clear button
                    clearBtnNode = document.querySelector('.fightsList__clearButton[data-fighter-id="' + e.target.dataset.fighterId + '"]')
                    clearBtnNode.classList.add('show')

                    if ( this.currentFightId !== e.target.dataset.fightId ) {
                        this.switchFight(e)
                        this.updatePicks({
                            fighterId: e.target.dataset.fighterId,
                            fightId: e.target.dataset.fightId,
                            offset: e.target.getBoundingClientRect().top + window.scrollY - 200,
                        })

                    } else {
                        this.updatePicks({
                            fighterId: e.target.dataset.fighterId,
                            fightId: e.target.dataset.fightId,
                            offset: e.target.getBoundingClientRect().top + window.scrollY - 200,
                        })
                    }
                }
            },

            addPick(newData) {
                this.playerPicks.push({
                    fightId: this.currentFightId,
                    fighterId: newData.fighterId,
                })
            },

            findPick(playerPick) {
                return playerPick.fightId === this.currentFightId
            },

            findImage(fighter) {
                return parseInt(fighter.fighterId, 10) === this.findFighterId;
            },

            updatePicks(newData) {
                var pickData,
                    pickDataIndex = -1,
                    fighterImageData,
                    fightIndicator

                // search for this fight in picks
                pickData = this.playerPicks.find(this.findPick)
                this.findFighterId = parseInt(newData.fighterId, 10)
                fighterImageData = this.fighterImages.find(this.findImage)
                // if a pic was already made for this fight
                if ( pickData ) {
                    // check pick fighter id with current fighter id
                    // change if different do nothing if its the same
                    if ( newData.fighterId !== '') {
                        if ( pickData.fighterId !== newData.fighterId ) {
                            this.deselectFighter(pickData.fighterId)

                            if (pickDataIndex === -1 ) pickDataIndex = this.playerPicks.findIndex(this.findPick)

                            if ( pickDataIndex !== -1 ) this.playerPicks[pickDataIndex].fighterId = newData.fighterId

                            this.selectFighter(newData.fighterId, newData.fightId)

                            // change the fighter indicators
                            // find indicator with fight id and swap the image with new fighter
                            fightIndicator = document.querySelector('.fighterIndicators[data-fight-id="' + newData.fightId + '"]')
                            fightIndicator.setAttribute('style', 'background-image:url(public/image/fighters/' + fighterImageData.image + ')')
                        } else {
                            this.selectFighter(newData.fighterId, newData.fightId)
                        }
                    }
                } else {
                    this.addPick(newData)
                    this.selectFighter(newData.fighterId, newData.fightId)
                    // add fighter image to the fight indicators
                    // find out how many fights have been selected (this.playerPicks.length) add image to appropriate indicator
                    fightIndicator = document.getElementById('fi' + (this.playerPicks.length - 1))
                    fightIndicator.setAttribute('style', 'background-image:url(public/image/fighters/' + fighterImageData.image + ')')
                    fightIndicator.setAttribute('data-fight-id', newData.fightId)
                }
            },

            switchFight(e) {
                var fightToClose,
                    fightToOpen,
                    target,
                    position

                if ( e.target.hasAttribute('data-fight-id') ) {

                    fightToClose = document.querySelector('div.fightsList__pick[data-fight-id="' + this.currentFightId + '"]')
                    if ( fightToClose ) fightToClose.classList.toggle('closed')

                    fightToOpen = document.querySelector('div.fightsList__pick[data-fight-id="' + e.target.dataset.fightId + '"]')
                    fightToOpen.classList.toggle('closed')
                    // update currentFightId
                    this.currentFightId = e.target.dataset.fightId

                    setTimeout(function() {
                        target = document.querySelector('.fightsList__fightersWrap[data-fight-id="' + e.target.dataset.fightId + '"]')
                        position = target.getBoundingClientRect().top + window.scrollY - 110
                        animatedScrollTo(document.body, position, 300)
                    }, 300)

                }
            },

            closeFight(fightId) {
                var fightPickEl,
                    position,
                    target

                fightPickEl = document.querySelector('div.fightsList__pick[data-fight-id="' + fightId + '"]')
                fightPickEl.classList.toggle('closed')

                target = document.querySelector('.fightsList__fightersWrap[data-fight-id="' + fightId + '"]')
                position = target.getBoundingClientRect().top + window.scrollY - 110
                animatedScrollTo(document.body, position, 300)

                this.currentFightId = ''
                this.currentFighterId = ''
            },

            clearFight(fightId, e) {
                var findPick = function(playerPick) {
                        return parseInt(playerPick.fightId, 10) === parseInt(fightId, 10)
                    },
                    fightersEls,
                    fighterIndicatorEls,
                    fightPickEl,
                    powerupIndicators,
                    pickIndicators,
                    pointIndicators,
                    pickDataIndex,
                    clearBtnNodes,
                    fighterIndicator,
                    fighterIndicatorToRemove,
                    styleAttrs = []

                // hide the clear button
                clearBtnNodes = document.querySelectorAll('div.fightsList__fightersWrap[data-fight-id="' + fightId + '"] .fightsList__clearButton')
                for (var i=0; i < clearBtnNodes.length; ++i) {
                    clearBtnNodes[i].classList.remove('show')
                }

                // search for this fight in picks
                pickDataIndex = this.playerPicks.findIndex(findPick)
                if ( pickDataIndex !== -1 ) {
                    // check for applied power-ups and remove if applied
                    if (this.fightData[parseInt(this.playerPicks[parseInt(pickDataIndex, 10)].fightId, 10)].powerupId !== 0 ) {
                        this.totalPowerUps--
                        // remove the power-up icons
                        powerupIndicators = document.querySelectorAll('img.fightsList__powerup[data-fight-id="' + fightId + '"]')
                        for ( var i = 0; i < powerupIndicators.length; ++i) {
                            powerupIndicators[i].classList.remove('show')
                        }
                        this.powerupIndicatorsClasses.$set(this.totalPowerUps, ['powerupIndicators'])
                    }
                    // delete the pick
                    this.playerPicks.splice(parseInt(pickDataIndex, 10), 1)

                    // reset the fight data for this fight
                    this.fightData[parseInt(fightId, 10)].finishId = 0
                    this.fightData[parseInt(fightId, 10)].round = 0
                    this.fightData[parseInt(fightId, 10)].minute = 0
                    this.fightData[parseInt(fightId, 10)].powerupId = 0
                    this.fightData[parseInt(fightId, 10)].powerupImage = ''

                    // remove fighter selection indicator
                    fightersEls = document.querySelectorAll('div.fightsList__fighterImgWrap[data-fight-id="' + fightId + '"] .fightsList__fighter')
                    fighterIndicatorEls = document.querySelectorAll('div.fightsList__fighterImgWrap[data-fight-id="' + fightId + '"] .fightsList__selectedIndicator')
                    fightPickEl = document.querySelector('div.fightsList__pick[data-fight-id="' + fightId + '"]')
                    fightPickEl.classList.remove('show')
                    for ( var i = 0; i < fightersEls.length; ++i ) {
                        fightersEls[i].classList.remove('selected')
                        fighterIndicatorEls[i].classList.remove('show')
                    }
                    // remove highlight on any finish, round or minute chosen
                    pickIndicators = document.querySelectorAll('span[data-fight-id="' + fightId + '"]')
                    for ( var i = 0; i < pickIndicators.length; ++i ) {
                        pickIndicators[i].classList.remove('show')
                    }
                    // remove point indicators
                    document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] span.fightsList__finishPointsIndicator').classList.remove('show')
                    document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] span.fightsList__roundPointsIndicator').classList.remove('show')
                    document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] span.fightsList__minutePointsIndicator').classList.remove('show')
                    document.querySelector('.fightsList__pick[data-fight-id="' + fightId + '"] span.fightsList__powerupPointsIndicator').classList.remove('show')

                    // this.currentFightId = ''
                    // this.currentFighterId = ''

                    // remove fighter indicators image
                    // cycle through the current set of elements and save the style attributes
                    // then cycle through the set and reset the images on each element, blanking anything is higher that the current number of fights selected
                    fighterIndicatorToRemove = document.querySelector('.fighterIndicators[data-fight-id="' + fightId + '"]')
                    fighterIndicatorToRemove.removeAttribute('style')

                    for (i = 0; i < 5; ++i) {
                        fighterIndicator = document.getElementById('fi' + i);
                        if (fighterIndicator.hasAttribute('style') ) {
                            styleAttrs.push({
                                style: fighterIndicator.getAttribute('style'),
                                fightId: fighterIndicator.getAttribute('data-fight-id')
                            })
                        }
                    }

                    for (i = 0; i < 5; ++i) {
                        fighterIndicator = document.getElementById('fi' + i)
                        if ( i < styleAttrs.length ) {
                            fighterIndicator.setAttribute('style', styleAttrs[i].style)
                            fighterIndicator.setAttribute('data-fight-id', styleAttrs[i].fightId)
                        } else {
                            fighterIndicator.removeAttribute('style')
                            fighterIndicator.removeAttribute('data-fight-id')
                        }
                    }

                    // remove power up indicators if any associated

                }

            },

            selectFighter(fighterId, fightId) {
                var fighterImageToSelect,
                    fighterIndicatorToSelect,
                    powerUpImageToSelect

                fighterImageToSelect = document.querySelector('img.fightsList__fighter[data-fighter-id="' + fighterId + '"]')
                if ( ! fighterImageToSelect.classList.contains('selected') ) {
                    fighterImageToSelect.classList.add('selected')
                    fighterIndicatorToSelect = document.querySelector('div.fightsList__selectedIndicator[data-fighter-id="' + fighterId  + '"]')
                    fighterIndicatorToSelect.classList.add('show')
                    // if a powerup is selected make sure to show it on the new fighter
                    if ( this.fightData[parseInt(fightId, 10)].powerupId !== 0) {
                        powerUpImageToSelect = document.querySelector('img.fightsList__powerup[data-fighter-id="' + fighterId + '"]')
                        powerUpImageToSelect.classList.add('show')
                    }

                    this.currentFighterId = fighterId
                    this.fighterName()
                } else {
                    this.currentFighterId = fighterId
                    this.fighterName()
                }
            },

            deselectFighter(fighterId) {
                var fighterImageToDeselect,
                    fighterIndicatorToDeselect,
                    powerUpImageToDeselect,
                    clearBtnToDeselect


                fighterImageToDeselect = document.querySelector('img.fightsList__fighter[data-fighter-id="' + fighterId + '"]')
                if ( fighterImageToDeselect.classList.contains('selected') ) {
                    fighterImageToDeselect.classList.remove('selected')
                    fighterIndicatorToDeselect = document.querySelector('div.fightsList__selectedIndicator[data-fighter-id="' + fighterId  + '"]')
                    fighterIndicatorToDeselect.classList.remove('show')
                    powerUpImageToDeselect = document.querySelector('img.fightsList__powerup[data-fighter-id="' + fighterId + '"]')
                    powerUpImageToDeselect.classList.remove('show')
                    clearBtnToDeselect = document.querySelector('.fightsList__clearButton[data-fighter-id="' + fighterId + '"]')
                    clearBtnToDeselect.classList.remove('show')
                }
            },

            fighterName() {
                var vm = this,
                    selectedFight,
                    selectedFighter,
                    findFight = function(fight) {
                        return fight.id === parseInt(vm.currentFightId, 10)
                    },
                    findFighter = function(fighter) {
                        return fighter.id === parseInt(vm.currentFighterId, 10)
                    }

                selectedFight = this.fightsList[0].fights.find(findFight)
                selectedFighter = selectedFight.fighters.find(findFighter)

                this.currentFighterName = selectedFighter.firstname // + ' ' + selectedFighter.lastname
            },

            commitPicks() {
                var localfightData = this.fightData,
                    localContestId = this.contestId,
                    compiledPicks,
                    errors,
                    vm = this
                // compile data with playerPicks and fightData
                // sync the pick with the server
                // take to player picks page
                // or stay on page and confirm entry?

                if ( this.playerPicks.length < 5 ) {
                    this.alert({
                        header: 'Heads Up',
                        subject: 'You need to pick more fighters to compete in this contest',
                        body: '<div class="highlight">Current Picks</div><div class="highlight larger">' + this.playerPicks.length + '/5</div><div>Just a few more clicks...</div>',
                        action: true,
                    })
                } /*else if ( this.playerPicks.length === 5 ) {
                    this.alert({
                        header: 'Warning',
                        subject: 'Choose an Reserve Fight',
                        body: 'You\'ve seleted ' + this.playerPicks.length + ' fights you may choose one more fight as an alternate in case one of your five selected fights is scratched.',
                        action: true
                    })
                } */else if ( this.playerPicks.length === 5 ) {
                    compiledPicks = this.playerPicks.map(function(pick) {
                        var fightdata = localfightData[parseInt(pick.fightId, 10)]

                        return {
                            contest_id: localContestId,
                            fight_id: parseInt(pick.fightId, 10),
                            winning_fighter_id: parseInt(pick.fighterId, 10),
                            finish_id: parseInt(fightdata.finishId, 10),
                            round: parseInt(fightdata.round, 10),
                            minute: parseInt(fightdata.minute, 10),
                            power_up_id: parseInt(fightdata.powerupId, 10),
                        }
                    })

                    // validate selections
                    errors = this.validatePicks(compiledPicks)
                    if ( ! errors.length ) {
                        localforage.getItem('id_token').then(function(token) {
                            vm.$http.post(URL.base + '/api/v1/picks', { picks: compiledPicks }, (data) => {
                                if ( data.success ) {
                                    vm.$root.playersBalance = data.balance
                                    vm.$router.go({ path: '/contest/' + vm.contestId + '/picks' })
                                }
                            }, {
                                // Attach the JWT header
                                headers: { 'Authorization' : 'Bearer ' + token }
                            })
                        })
                    } else {
                        // display errors here
                       this.alert({
                            header: 'You Missed Something',
                            subject: 'Pick a Finish, Round and Minute for each Fight',
                            body: '<p>Looks like you forgot to select some potentially point increasing options in ' + errors.length + ' of your fights.</p>',
                            action: true,
                       })
                    }
                }
            },

            validatePicks(picks) {
                var errors = []
                // make sure in every fight picked that all data is entered
                // finish_id, minute, and roudn can't be 0
                // power up id can be zero
                picks.forEach(function(pick, i) {
                    var error = {
                        finish: false,
                        minute: false,
                        round: false,
                    }
                    if (pick.finish_id === 0 ) error.finish = true
                    if (pick.minute === 0 ) error.minute = true
                    if (pick.round === 0 ) error.round = true

                    if ( error.finish || error.minute || error.round ) errors.push(error)
                })

                return errors
            },

            alert(options) {
                this.alertNotice.header = (options.header) ? options.header : 'Alert'
                this.alertNotice.subject = (options.subject ) ? options.subject : 'You did something wrong.'
                this.alertNotice.body = (options.body ) ? options.body : ''

                this.alertNotice.action = (options.action) ? options.action : false

                this.alertNoticeClasses = ['alertNotice', 'show']
            },

            alertNoticeClose(e) {
                e.preventDefault()

                this.alertNoticeClasses = ['alertNotice']
            },

            getPosition(el) {
                var xPos = 0
                var yPos = 0

                while (el) {
                    if (el.tagName == "BODY") {
                        // deal with browser quirks with body/window/document and page scroll
                        var xScroll = el.scrollLeft || document.documentElement.scrollLeft
                        var yScroll = el.scrollTop || document.documentElement.scrollTop

                        xPos += (el.offsetLeft - xScroll + el.clientLeft)
                        yPos += (el.offsetTop - yScroll + el.clientTop)
                    } else {
                        // for all other non-BODY elements
                        xPos += (el.offsetLeft - el.scrollLeft + el.clientLeft)
                        yPos += (el.offsetTop - el.scrollTop + el.clientTop)
                    }

                    el = el.offsetParent
                }

                return {
                    x: xPos,
                    y: yPos
                }
            },
        },

        computed: {
            loaderClasses() {
                return (this.working) ? 'spinnerWrap' : 'spinnerWrap visuallyhidden'
            },
        },
    }
</script>