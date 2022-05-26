const state = () => ({
    drawer: true,
    snackMessage: '',
    snackColor: '',
    showSnack: false
})
const actions = {
    toggleDrawer({ commit }, payload) {
        commit('TOGGLE_DRAWER', payload)
    }
}
const mutations = {
    TOGGLE_DRAWER(state, payload) {
        state.drawer = payload
    },
    setSnackMessage(state, snackMessage) {
        state.snackMessage = snackMessage
    },
    setShowSnack(state, val) {
        state.showSnack = val
    },
    setSnackColor(state, val) {
        state.snackColor = val
    }
}
const getters = {
    drawer: (state) => state.drawer,
    snackMessage: ({ snackMessage }) => snackMessage,
    showSnack: ({ showSnack }) => showSnack,
    snackColor: ({ snackColor }) => snackColor
}
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}