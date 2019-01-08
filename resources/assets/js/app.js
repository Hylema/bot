import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

import App from './views/App'
import Home from './views/Home'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
    ],
});

require('./bootstrap');
window.Vue = require('vue');

//Место для записи компонентов
// Vue.component('component-test', require('./components/Example'));
// Vue.component('component-prop', require('./components/PropComponent'));
Vue.component('component-admin', require('./components/Admin'));
Vue.component('component-player', require('./components/Players'));
Vue.component('component-game-result', require('./components/GameResult'));

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});