import Vue from 'vue';
import Vuex from 'vuex';
import auth from './auth';
import portfolio from './portfolio';
import notification from './notification';
import coin from './coin';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  modules: {
    auth,
    portfolio,
    notification,
    coin,
  },
});
