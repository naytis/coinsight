import Vue from 'vue';
import App from './App.vue';
import router from './router';
import vuetify from './plugins/vuetify';
import 'roboto-fontface/css/roboto/roboto-fontface.css';
import '@mdi/font/css/materialdesignicons.css';
import store from './store';
import {addFilters} from './filters';

Vue.config.productionTip = false;

addFilters(Vue);

new Vue({
  router,
  vuetify,
  store,
  render: h => h(App),
}).$mount('#app');
