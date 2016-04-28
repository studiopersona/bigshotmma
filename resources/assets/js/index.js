import Vue from 'vue';
import App from './components/App.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';
import Events from './components/Events.vue';
import Contests from './components/Contests.vue';
import ContestLobby from './components/ContestLobby.vue';
import Fights from './components/Fights.vue';
import PlayerPicks from './components/PlayerPicks.vue';
import Standings from './components/Standings.vue';
import Results from './components/Results.vue';

import VueRouter from 'vue-router';
import VueResource from 'vue-resource';

Vue.config.debug = false;

Vue.use(VueResource);
Vue.use(VueRouter);

export var router = new VueRouter();

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

  '/event/:event_id/contests': {
    component: Contests
  },

  '/contest/:contest_id/players': {
    component: ContestLobby
  },

  '/contest/:contest_id/fights': {
    component: Fights
  },

  '/contest/:contest_id/picks': {
    component: PlayerPicks
  },

  '/contest/:contest_id/standings': {
    component: Standings
  },

  '/contest/:contest_id/results': {
    component: Results
  },

});

// Redirect to the home route if any routes are unmatched
router.redirect({
  '*': '/login'
});

// Start the app on the #app div
router.start(App, '#app');