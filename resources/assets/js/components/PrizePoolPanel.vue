<template>
<section :class="prizeModalClasses">
    <h3 class="prizeModal__title">Prize Pool</h3>
    <div class="prizeModal__body">
    <p>In a <a @click="showContestRules" data-contest-type="{{ participantsList.contest.contest_type_id }}">{{ participantsList.contest.contest_type_name }}</a> contest with {{ participantsList.contest.max_participants }} players:</p>
        <div class="prizeModal__entryFeeWrap">
            <span class="prizeModal__entryFeeTitle">Entry Fee:</span> <span class="prizeModal__entryFee">${{ parseFloat(participantsList.contest.buy_in).toFixed(2) }}</span>
        </div>
        <table class="prizeModal__payoutTable">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Prize</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="payout of prizePool.payouts" track-by="$index">
                    <td>{{ $index + 1 }}</td>
                    <td class="prizeModal__payout">${{ parseFloat(payout).toFixed(2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="button-wrap">
        <button @click="prizeModalClose" type="button" class="button button--green">Got It</button>
    </div>
    <button @click="prizeModalClose" type="button" class="infoModal__close">x</button>
</section>
</template>

<script>

export default {

    data() {
        return {
            prizePool: {},
            prizePoolPayouts: {
                Classic: {
                    10: [0.7, 0.3],
                    20: [0.5, 0.25, 0.15, 0.10],
                    50: [0.365, 0.21, 0.15, 0.10, 0.05, 0.025, 0.025, 0.025, 0.025, 0.025],
                    100: [0.3275, 0.150, 0.08, 0.07, 0.06, 0.05, 0.04, 0.03, 0.0275, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150, 0.0150],
                },
                Greed: [1],
                '50/50': [1],
            },
            prizeModalClasses: ['prizeModal'],
            participantsList: {},
        }
    },

    methods: {

        calculatePayouts(participantsList) {
            this.participantsList = participantsList.contest || participantsList

            let total = (this.participantsList.buy_in * this.participantsList.max_participants) - ((this.participantsList.buy_in * this.participantsList.max_participants)*0.15)

            let type = this.participantsList.contest_type_name
            let numOfParticipants = this.participantsList.max_participants

            // console.log(type)

            let payoutArray = (this.participantsList.contest_type_id == 1) ? this.prizePoolPayouts[type][numOfParticipants] : this.prizePoolPayouts[type]
            let placePayouts = [];

            for(var i=0; i < payoutArray.length; i++) {
                placePayouts.push(total*payoutArray[i])
            }

            this.prizePool = {
                total: total,
                payouts: placePayouts,
            }

            this.$dispatch('prize-total-calculated', this.prizePool.total);
        },

        prizeModalClose(e) {
            e.preventDefault();

            this.prizeModalClasses = ['prizeModal'];
        },
    },

    events: {
        'participants-list-changed'(participantsList) {
            console.log('participants list changed');
            console.log(participantsList);

            this.calculatePayouts(participantsList);
        },

        'show-prize-modal'() {
            this.prizeModalClasses.push('show');
        },
    },
}

</script>