// ECMASCRIPT 2015 Array Polfils ----------
if (!Array.prototype.find) {
  Array.prototype.find = function(predicate) {
    if (this === null) {
      throw new TypeError('Array.prototype.find called on null or undefined')
    }
    if (typeof predicate !== 'function') {
      throw new TypeError('predicate must be a function')
    }
    var list = Object(this)
    var length = list.length >>> 0
    var thisArg = arguments[1]
    var value

    for (var i = 0; i < length; i++) {
      value = list[i]
      if (predicate.call(thisArg, value, i, list)) {
        return value;
      }
    }
    return undefined;
  }
}

if (!Array.prototype.findIndex) {
  Array.prototype.findIndex = function(predicate) {
    if (this === null) {
      throw new TypeError('Array.prototype.findIndex called on null or undefined')
    }
    if (typeof predicate !== 'function') {
      throw new TypeError('predicate must be a function')
    }
    var list = Object(this)
    var length = list.length >>> 0
    var thisArg = arguments[1]
    var value

    for (var i = 0; i < length; i++) {
      value = list[i]
      if (predicate.call(thisArg, value, i, list)) {
        return i
      }
    }
    return -1
  }
}
// ---------------------------------------
import Vue from 'vue'
import App from './components/App.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'
import Events from './components/Events.vue'
import Contests from './components/Contests.vue'
import Entries from './components/Entries.vue'
import Lobby from './components/Lobby.vue'
import Fights from './components/Fights.vue'
import PlayerPicks from './components/PlayerPicks.vue'
import Results from './components/Results.vue'
import PlayerContests from './components/PlayerContests.vue'
import Profile from './components/Profile.vue'
import DepositProfile from './components/DepositProfile.vue'
import Deposit from './components/Deposit.vue'
import Withdraw from './components/Withdraw.vue'
import ForgotPassword from './components/ForgotPassword.vue'

import VueRouter from 'vue-router'
import VueResource from 'vue-resource'

Vue.config.debug = false
Vue.use(VueResource)
Vue.use(VueRouter)

export var router = new VueRouter()

// Set up routing and match routes to components
router.map({
  '/login': {
    component: Login
  },

  '/register': {
    component: Register
  },

  '/events': {
    component: Events
  },

  '/contests': {
    component: Contests,
  },

  '/event/:event_id/contests': {
    component: Contests
  },

  '/player/contests': {
    component: PlayerContests
  },

  '/contest/:contest_id/players': {
    component: Lobby
  },

  '/contest/:contest_id/fights': {
    component: Fights
  },

  '/contest/:contest_id/picks': {
    component: PlayerPicks
  },

  // '/contest/:contest_id/standings': {
    // component: Standings
  // },

  '/contest/:contest_id/results': {
    component: Results
  },

  '/profile': {
    component: Profile
  },

  '/deposit-profile': {
    component: DepositProfile
  },

  '/deposit': {
    component: Deposit
  },

  '/deposit/paypal/:transactionId': {
    component: Deposit
  },

  '/withdrawl-request': {
    component: Withdraw
  },

  '/entries/:player_id': {
    component: Entries
  },

  '/forgot-password': {
    component: ForgotPassword
  },

})

// Redirect to the home route if any routes are unmatched
router.redirect({
  '*': '/login'
})

// Start the app on the #app div
router.start(App, '#app')