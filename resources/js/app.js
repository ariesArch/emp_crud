
window.Vue = require('vue');
import Vue from 'vue';
import router from '@/router';
import App from '@/App.vue';
import store from '@/store'
import vuetify from '@/plugins/vuetify';
import '@/plugins/validate.js'
import api from '@/services/api'
import request from '@/services/request'
import model from '@/services/model'
import mixin from '@/mixin'
Vue.prototype.$api = api
Vue.prototype.$request = request
Vue.prototype.$model = model
Vue.mixin(mixin)
Vue.use(require('vue-shortkey'))
new Vue({
    router,
    store,
    ...App,
    vuetify
}).$mount('#app');
