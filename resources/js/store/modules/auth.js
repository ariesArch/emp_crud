import localforage from "localforage"
const state = () => ({
    user: '',
    jwt: '',
    isLoggedIn: false
})
const actions = {
    setUser({ commit }, payload) {
        commit('SET_USER', payload)
    },
    setJwt({ commit }, payload) {
        commit('SET_JWT', payload)
    },
    setIsLoggedIn({ commit }, payload) {
        commit('IS_LOGGEDIN', payload)
    },
    async clearAuth({ commit }) {
        await localforage.clear()
        console.log("clear local")
        commit('RESET_AUTH')
    },
}
const mutations = {
    SET_USER(state, user) {
        state.user = user
    },
    SET_JWT(state, jwt) {
        state.jwt = jwt
    },
    IS_LOGGEDIN(state, value) {
        state.isLoggedIn = value
        // async () => await localforage.setItem('is_login', value)
    },
    RESET_AUTH(state) {
        state.jwt = ''
        state.user = ''
    },
}
const getters = {
    user: (state) => state.user,
    jwt: (state) => state.jwt,
    isLoggedIn: ({ isLoggedIn }) => isLoggedIn
}
export default {
    namespaced: true,
    state,
    actions,
    mutations,
    getters
}