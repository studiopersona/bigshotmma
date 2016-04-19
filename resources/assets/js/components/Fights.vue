<template>
    <div :working="working">
        <header class="pageHeader" :working.sync="working">
            <h1 class="pageHeader__header">Fighters {{ playerPicks.length }}/5 Chosen</h1>
            <h4 class="pageHeader__subheader">{{ totalPowerUps }}/3 Power-ups Used</h4>
        </header>
        <div class="fightsList">
            <ul>
                <li class="fightsList__item" v-for="fight in fightsList[0].fights">
                    <div class="container-fluid fightsList__fightersWrap">
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
                                        :class="['fightsList__fighter', (fight.fighters[0].pivot.odds > fight.fighters[1].pivot.odds) ? 'favorite' : '']"
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
                                    <img
                                        class="fightsList__powerup left"
                                        :src="'public/image/powerups/' + fightData[fight.id].powerupImage"
                                        data-fighter-id="{{ fight.fighters[0].id}}"
                                        data-fight-id="{{ fight.id }}"
                                        @click="removePowerUp(fight.id, $event)"
                                    >
                                    <div class="fightsList__selectedIndicatorWrap">
                                        <div :class="['fightsList__selectedIndicator', (parseInt(fight.fighters[0].pivot.odds, 10) > parseInt(fight.fighters[1].pivot.odds, 10)) ? 'favorite' : '']" data-fighter-id="{{ fight.fighters[0].id }}">
                                            <span>
                                                {{ (parseInt(fight.fighters[0].pivot.odds, 10) > parseInt(fight.fighters[1].pivot.odds, 10)) ? 'Favorite +3' : 'Underdog +5' }}
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
                                <div :class="['fightsList__spread', fight.fighters[0].pivot.odds > 0 ? 'favorite' : '']">
                                    {{ fight.fighters[0].pivot.odds }}
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
                                <div :class="['fightsList__spread', fight.fighters[1].pivot.odds > 0 ? 'favorite' : '']">
                                    {{ fight.fighters[1].pivot.odds }}
                                </div>
                            </div>
                            <div class="col-xs-40  fightsList__fighterWrap">
                                <div class="fightsList__fighterImgWrap" data-fight-id="{{ fight.id }}">
                                    <img
                                        :class="['fightsList__fighter', (parseInt(fight.fighters[1].pivot.odds, 10) > parseInt(fight.fighters[0].pivot.odds, 10)) ? 'favorite' : '']"
                                        :src="'public/image/fighters/' + fight.fighters[1].fighter_image_name"
                                        alt="{{ fight.fighters[1].firstname }} {{ fight.fighters[1].lastname }} Image"
                                        data-fighter-id="{{ fight.fighters[1].id }}"
                                    >
                                    <img
                                        class="fightsList__flag right"
                                        :src="'public/image/flags/' + fight.fighters[0].nationality.country_flag_uri"
                                        alt="{{ fight.fighters[1].nationality.country_name }} Flag"
                                    >
                                    <img
                                        class="fightsList__powerup right"
                                        :src="'public/image/powerups/' + fightData[fight.id].powerupImage"
                                        data-fighter-id="{{ fight.fighters[1].id}}"
                                        data-fight-id="{{ fight.id }}"
                                        @click="removePowerUp(fight.id, $event)"
                                    >
                                     <div class="fightsList__selectedIndicatorWrap">
                                        <div
                                            :class="['fightsList__selectedIndicator', (fight.fighters[1].pivot.odds > fight.fighters[0].pivot.odds) ? 'favorite' : '']"
                                            data-fighter-id="{{ fight.fighters[1].id }}">
                                            <span>
                                                {{ (parseInt(fight.fighters[1].pivot.odds, 10) > parseInt(fight.fighters[0].pivot.odds, 10)) ? 'Favorite +3' : 'Underdog +5' }}
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
                                <select v-model="fightData[fight.id].finishId">
                                    <option value="0">Choose Finish (+5)</option>
                                    <option v-for="finish in finishes" value="{{ finish.id }}">{{ finish.name }} (+{{ finish.points }})</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <select v-model="fightData[fight.id].round">
                                    <option value="0">Choose Round (+2)</option>
                                    <option value="1">Round 1 (+2)</option>
                                    <option value="2">Round 2 (+2)</option>
                                    <option value="3">Round 3 (+2)</option>
                                    <option value="4">Round 4 (+2)</option>
                                    <option value="5">Round 5 (+2)</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <select v-model="fightData[fight.id].minute">
                                    <option value="0">Choose Minute (+1)</option>
                                    <option value="1">Minute 1 (+1)</option>
                                    <option value="2">Minute 2 (+1)</option>
                                    <option value="3">Minute 3 (+1)</option>
                                    <option value="4">Minute 4 (+1)</option>
                                    <option value="5">Minute 5 (+1)</option>
                                </select>
                            </div>
                            <div class="col-xs-100">
                                <div class="fightsList__pickHeader">Power-up (optional)</div>
                            </div>
                            <div class="col-xs-100 powerUpsList">
                                <div class="col-xs-23 col-xs-offset-2" v-for="powerUp in powerUps">
                                    <button class="powerUpList__btn" type="button">
                                        <span class="visuallyhidden">{{ powerUp.name }}</span>
                                        <img
                                            :src="'public/image/powerups/' + powerUp.image_name"
                                            @click="confirmPowerUp"
                                            data-power-up="{{ powerUp.power_up_id }}"
                                            data-fight-id="{{ fight.id }}"
                                            alt="{{ powerUp.name }} Image"
                                        >
                                        <span :style="{color: powerUp.color, fontSize: '1rem'}">{{ powerUp.name }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-100 button-wrap">
                                <button @click="clearFight(fight.id, $event)"
                                        type="button"
                                        class="button button--primary"
                                >Clear</button>
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
                        @click="commitPicks"
                    >Commit Picks</button>
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
        <section :class="alertNoticeClasses">
            <div>
                <span class="alertNotice__type">{{ alertNotice.type }}</span>: {{{ alertNotice.msg }}}
                <button @click="alertNoticeClose" type="button" class="alertModal__close">x</button>
            </div>
            <div v-if="alertNotice.action" class="button-wrap">
                {{{ alertNotice.action }}}
            </div>
        </section>
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
                alertNoticeClasses: ['alertNotice'],
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
                currentFightId: '',
                currentFighterId: '',
                fightData:[
                    {},
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                    { finishId: 0, round:0, minute:0, powerupId: 0, powerupImage: '' },
                ],
                alertNotice: {
                    type: 'Alert',
                    msg: 'This is something you should know',
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
            this.working = true;
        },

        ready() {
            this.$http.get(URL.base + '/api/v1/contest/' + this.$route.params.contest_id + '/fights', (data) => {
                this.fightsList = data.fights;
                this.working = false;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            }).error((err) => console.log(err))

            this.$http.get(URL.base + '/api/v1/power-ups', (data) => {
                this.powerUps = data;
            }, {
                // Attach the JWT header
                headers: auth.getAuthHeader()
            })

            this.$http.get(URL.base + '/api/v1/finishes', (data) => {
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

                    this.selectedPowerUp.id = newPowerUp.power_up_id;
                    this.selectedPowerUp.fightId = e.target.dataset.fightId;
                    this.selectedPowerUp.title = newPowerUp.name;
                    this.selectedPowerUp.description = newPowerUp.description,
                    this.selectedPowerUp.image_name = newPowerUp.image_name;
                    this.selectedPowerUp.color = newPowerUp.color;
                    this.selectedPowerUp.bonus_points = newPowerUp.bonus_points;
                    this.selectedPowerUp.penalty_points = newPowerUp.penalty_points;
                }

                this.powerUpModalClasses.push('show');
            },

            selectPowerUp(fightId, powerUpId, powerUpImage, e) {
                var findFight = function(playerPick) {
                        return parseInt(playerPick.fightId, 10) === parseInt(fightId, 10);
                    },
                    fightPick,
                    puIndicatorImage;

                if ( this.totalPowerUps < 3 ) {
                    if (this.fightData[fightId].powerupId === 0) ++this.totalPowerUps;
                    this.fightData[fightId].powerupId = powerUpId;
                    this.fightData[fightId].powerupImage = powerUpImage;
                    this.powerUpModalClose(e);
                    // find fighter choosen for the fight and add show class to powerup image indicator
                    fightPick = this.playerPicks.find(findFight);
                    puIndicatorImage = document.querySelector('img.fightsList__powerup[data-fight-id="' + fightId +'"][data-fighter-id="' + fightPick.fighterId + '"]');
                    puIndicatorImage.classList.add('show');
                } else {
                    this.alert({
                        msg: 'You may only apply 3 powers-ups per contest.<br>You will need to remove one or more applied power-ups in order to activate another.'
                    });
                }
            },

            findPowerUp(powerUp) {
                return powerUp.power_up_id === parseInt(this.powerUpId, 10);
            },

            powerUpModalClose(e) {
                e.preventDefault();

                this.powerUpModalClasses = ['powerUpModal'];
            },

            removePowerUp(fightId, e) {
                var powerupImageIndicator;

                this.fightData[fightId].powerupId = 0;
                this.powerupImage = '';

                // remove the show class form powerup images
                powerupImageIndicator = document.querySelectorAll('img.fightsList__powerup[data-fight-id="' + fightId + '"]');
                for (var i = 0; i < powerupImageIndicator.length; ++i) {
                    powerupImageIndicator[i].classList.remove('show');
                };

                this.totalPowerUps--;
            },

            selectFight(e) {
                if ( this.playerPicks.length === 6 ) {
                    this.alert({
                        type: 'Alert',
                        msg: 'You\'ve already selected the maximum number of fights. Please clear one of your selections if you would like to add this fight.',
                    });
                } else {
                    if ( this.currentFightId !== e.target.dataset.fightId ) {
                        this.switchFight(e);
                        this.updatePicks({
                            fighterId: e.target.dataset.fighterId,
                            fightId: e.target.dataset.fightId,
                        });

                    } else {
                        this.updatePicks({
                            fighterId: e.target.dataset.fighterId,
                            fightId: e.target.dataset.fightId,
                        });
                    }
                }
            },

            addPick(newData) {
                this.playerPicks.push({
                    fightId: this.currentFightId,
                    fighterId: newData.fighterId,
                });
            },

            updatePicks(newData) {
                var pickData,
                    pickDataIndex = -1;

                // search for this fight in picks
                pickData = this.playerPicks.find(this.findPick);
                // if a pic was already made for this fight
                if ( pickData ) {
                    // check pick fithter id with current fighter id
                    // change if different do nothing if its the same
                    if ( newData.fighterId !== '') {
                        if ( pickData.fighterId !== newData.fighterId ) {
                            this.deselectFighter(pickData.fighterId);

                            if (pickDataIndex === -1 ) pickDataIndex = this.playerPicks.findIndex(this.findPick);

                            if ( pickDataIndex !== -1 ) this.playerPicks[pickDataIndex].fighterId = newData.fighterId;

                            this.selectFighter(newData.fighterId, newData.fightId);
                        }
                    }
                } else {
                    this.addPick(newData);
                    this.selectFighter(newData.fighterId, newData.fightId);
                }
            },

            findPick(playerPick) {
                return playerPick.fightId === this.currentFightId;
            },

            switchFight(e) {
                var fightToClose,
                    fightToOpen;

                fightToClose = document.querySelector('div.fightsList__pick[data-fight-id="' + this.currentFightId + '"]');
                if ( fightToClose ) fightToClose.classList.toggle('show');

                fightToOpen = document.querySelector('div.fightsList__pick[data-fight-id="' + e.target.dataset.fightId + '"]');
                fightToOpen.classList.toggle('show');
                // update currentFightId
                this.currentFightId = e.target.dataset.fightId;
            },

            clearFight(fightId, e) {
                var findPick = function(playerPick) {
                        return parseInt(playerPick.fightId, 10) === parseInt(fightId, 10);
                    },
                    fightersEls,
                    fighterIndicatorEls,
                    fightPickEl,
                    powerupIndicators,
                    pickDataIndex;

                // search for this fight in picks
                pickDataIndex = this.playerPicks.findIndex(findPick);
                if ( pickDataIndex !== -1 ) {
                    if (this.fightData[parseInt(this.playerPicks[parseInt(pickDataIndex, 10)].fightId, 10)].powerupId !== 0 ) {
                        this.totalPowerUps--;
                        powerupIndicators = document.querySelectorAll('img.fightsList__powerup[data-fight-id="' + fightId + '"]');
                        for ( var i = 0; i < powerupIndicators.length; ++i) {
                            powerupIndicators[i].classList.remove('show');
                        }

                        // delete the pick
                        this.playerPicks.splice(parseInt(pickDataIndex, 10), 1);
                    }


                    // reset the fight data for this fight
                    this.fightData[parseInt(fightId, 10)].finishId = 0;
                    this.fightData[parseInt(fightId, 10)].round = 0;
                    this.fightData[parseInt(fightId, 10)].minute = 0;
                    this.fightData[parseInt(fightId, 10)].powerupId = 0;
                    this.fightData[parseInt(fightId, 10)].powerupImage = '';

                    // remove fighter selection indicator
                    fightersEls = document.querySelectorAll('div.fightsList__fighterImgWrap[data-fight-id="' + fightId + '"] .fightsList__fighter');
                    fighterIndicatorEls = document.querySelectorAll('div.fightsList__fighterImgWrap[data-fight-id="' + fightId + '"] .fightsList__selectedIndicator');
                    fightPickEl = document.querySelector('div.fightsList__pick[data-fight-id="' + fightId + '"]');
                    fightPickEl.classList.toggle('show');
                    for ( var i = 0; i < fightersEls.length; ++i ) {
                        fightersEls[i].classList.remove('selected');
                        fighterIndicatorEls[i].classList.remove('show');
                    };
                    this.currentFightId = '';
                    this.currentFighterId = '';
                }

            },

            selectFighter(fighterId, fightId) {
                var fighterImageToSelect,
                    fighterIndicatorToSelect,
                    powerUpImageToSelect;

                fighterImageToSelect = document.querySelector('img.fightsList__fighter[data-fighter-id="' + fighterId + '"]');
                if ( ! fighterImageToSelect.classList.contains('selected') ) {
                    fighterImageToSelect.classList.add('selected');
                    fighterIndicatorToSelect = document.querySelector('div.fightsList__selectedIndicator[data-fighter-id="' + fighterId  + '"]');
                    fighterIndicatorToSelect.classList.add('show');
                    // if a powerup is selected make sure to show it on the new fighter
                    if ( this.fightData[parseInt(fightId, 10)].powerupId !== 0) {
                        powerUpImageToSelect = document.querySelector('img.fightsList__powerup[data-fighter-id="' + fighterId + '"]');
                        powerUpImageToSelect.classList.add('show');
                    }

                    this.currentFighterId = fighterId;
                }
            },

            deselectFighter(fighterId) {
                var fighterImageToDeselect,
                    fighterIndicatorToDeselect,
                    powerUpImageToDeselect;


                fighterImageToDeselect = document.querySelector('img.fightsList__fighter[data-fighter-id="' + fighterId + '"]');
                if ( fighterImageToDeselect.classList.contains('selected') ) {
                    fighterImageToDeselect.classList.remove('selected');
                    fighterIndicatorToDeselect = document.querySelector('div.fightsList__selectedIndicator[data-fighter-id="' + fighterId  + '"]');
                    fighterIndicatorToDeselect.classList.remove('show');
                    powerUpImageToDeselect = document.querySelector('img.fightsList__powerup[data-fighter-id="' + fighterId + '"]');
                    powerUpImageToDeselect.classList.remove('show');
                }
            },

            commitPicks() {
                var localfightData = this.fightData,
                    localContestId = this.contestId,
                    compiledPicks,
                    errors;
                // compile data with playerPicks and fightData
                // sync the pick with the server
                // take to player picks page
                // or stay on page and confirm entry?

                if ( this.playerPicks.length < 5 ) {
                    this.alert({
                        type: 'Warning',
                        msg: 'You\'ve only seleted ' + this.playerPicks.length + ' fights out of 5 that you are allowed to play. Would you like to stay and select more fights or continue commiting your selections?',
                        action: '<button type="button" @click="closeAlert" class="alertNotice__confirm--no">Stay</button><button @click="continueCommit" type="button" class="alertNotice__confirm--yes">Continue</button>'
                    });
                    // alert that you have only picked x number of fights
                    // allowed to pick 5 plus one alternate
                } else if ( this.playerPicks.length === 5 ) {
                    this.alert({
                        type: 'Warning',
                        msg: 'You\'ve seleted ' + this.playerPicks.length + ' fights you may choose one more fight as an alternate in case one of your five slected fights is scratched.',
                        action: '<button type="button" @click="closeAlert" class="alertNotice__confirm--no">Stay</button><button @click="continueCommit" type="button" class="alertNotice__confirm--yes">Continue</button>'
                    });
                } else if ( this.playerPicks.length > 5 ) {
                    compiledPicks = this.playerPicks.map(function(pick) {
                        var fightdata = localfightData[parseInt(pick.fightId, 10)];

                        return {
                            contest_id: localContestId,
                            fight_id: parseInt(pick.fightId, 10),
                            winning_fighter_id: parseInt(pick.fighterId, 10),
                            finish_id: parseInt(fightdata.finishId, 10),
                            round: parseInt(fightdata.round, 10),
                            minute: parseInt(fightdata.minute, 10),
                            power_up_id: parseInt(fightdata.powerupId, 10),
                        };
                    });

                    // validate selections
                    errors = this.validatePicks(compiledPicks);
                    if ( ! errors.length ) {
                        this.$http.post(URL.base + '/api/v1/picks', { picks: compiledPicks }, (data) => {
                            if ( data.success ) this.$router.go({ path: '/contest/' + this.contestId + '/picks' });
                        }, {
                            // Attach the JWT header
                            headers: auth.getAuthHeader()
                        });
                    } else {
                        // display errors here
                        console.log(errors);
                        alert('There are errors');
                    }
                }
            },

            validatePicks(picks) {
                var errors = [];
                // make sure in every fight picked that all data is entered
                // finish_id, minute, and roudn can't be 0
                // power up id can be zero
                picks.forEach(function(pick, i) {
                    var error = {
                        finish: false,
                        minute: false,
                        round: false,
                    };
                    if (pick.finish_id === 0 ) error.finish = true;
                    if (pick.minute === 0 ) error.minute = true;
                    if (pick.round === 0 ) error.round = true;

                    if ( error.finish || error.minute || error.round ) errors.push(error);
                });

                return errors;
            },

            alert(options) {
                this.alertNotice.type = (options.type) ? options.type : 'Alert';
                this.alertNotice.msg = (options.msg ) ? options.msg : 'You did something wrong.';

                this.alertNotice.action = (options.action) ? options.action : false;

                this.alertNoticeClasses = ['alertNotice', 'show'];
            },

            alertNoticeClose(e) {
                e.preventDefault();

                this.alertNoticeClasses = ['alertNotice'];
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