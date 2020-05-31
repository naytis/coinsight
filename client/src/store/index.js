import Vue from 'vue';
import Vuex from 'vuex';
import auth from './auth';
import portfolio from './portfolio';
import notification from './notification';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    auth,
    portfolio,
    notification,
  },
});
