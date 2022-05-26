import Vue from 'vue';
import VueRouter from "vue-router";
import store from '@/store';
import localforage from 'localforage'
Vue.use(VueRouter)
import routes from "./routes";
const router = new VueRouter({
    mode: 'history',
    routes: routes
})
router.beforeEach(async (to, from, next) => {
    const jwt = await localforage.getItem('jwt')
    const user = await localforage.getItem('user')
    if (to.matched.some(record => record.meta.requiredAuth) && !jwt) {
        store.dispatch('auth/clearAuth')
        next({ name: 'login' })
    } else if (to.matched.some(record => record.meta.guest) && jwt != null) {
        next({
            path: '/'
        })
    }
    try {
        store.dispatch('auth/setJwt', jwt)
        store.dispatch('auth/setUser', user)
        next()
    } catch (error) {
        console.log(error)
    }
})
export default router