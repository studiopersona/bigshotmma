// ECMASCRIPT 2015 Array Polyfils ----------
if (!Array.prototype.find) {
  Array.prototype.find = function(predicate) {
    if (this === null) {
      throw new TypeError('Array.prototype.find called on null or undefined');
    }
    if (typeof predicate !== 'function') {
      throw new TypeError('predicate must be a function');
    }
    var list = Object(this);
    var length = list.length >>> 0;
    var thisArg = arguments[1];
    var value;

    for (var i = 0; i < length; i++) {
      value = list[i];
      if (predicate.call(thisArg, value, i, list)) {
        return value;
      }
    }
    return undefined;
  };
}

if (!Array.prototype.findIndex) {
  Array.prototype.findIndex = function(predicate) {
    if (this === null) {
      throw new TypeError('Array.prototype.findIndex called on null or undefined');
    }
    if (typeof predicate !== 'function') {
      throw new TypeError('predicate must be a function');
    }
    var list = Object(this);
    var length = list.length >>> 0;
    var thisArg = arguments[1];
    var value;

    for (var i = 0; i < length; i++) {
      value = list[i];
      if (predicate.call(thisArg, value, i, list)) {
        return i;
      }
    }
    return -1;
  };
}
// ---------------------------------------
import Vue from 'vue';
import App from './../../js/components/App.vue';
import Login from './../../js/components/admin/Login.vue';
import Events from './../../js/components/admin/Events.vue';
import Contests from './../../js/components/admin/Contests.vue';
import Fights from './../../js/components/admin/Fights.vue';
import Results from './../../js/components/admin/Results.vue';


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

  '/events': {
    component: Events
  },

  '/event/:event_id/contests': {
    component: Contests
  },

  '/contest/:contest_id/fights': {
    component: Fights
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