import { mapActions, mapGetters, mapMutations } from 'vuex'
import SnackBar from '@/components/Helpers/SnackBar'
import LoadingRotate from '@/components/Helpers/LoadingRotate'
import ConfirmDelete from '@/components/Helpers/ConfirmDelete'
import InfoCard from '@/components/Helpers/InfoCard'
import Vue from 'vue'
export default {
    computed: {
        ...mapGetters({
            snackMessage: 'util/snackMessage',
            user: 'auth/user'
        }),
        is_admin() {
            return this.user.is_admin || false;
        }
    },
    methods: {
        ...mapMutations({
            setSnackMessage: 'util/setSnackMessage',
            setShowSnack: 'util/setShowSnack',
            setSnackColor: 'util/setSnackColor',
        }),
        ...mapActions({
            clearAuth: 'auth/clearAuth'
        }),
        openSnackBar(that, snackbarMessage, snacColor) {
            that.setSnackMessage(snackbarMessage)
            that.setSnackColor(snacColor)
            that.setShowSnack(true)
        },
        handleStatus({ status, message, that, successMessage = '' }) {
            if (status === 1) {
                that.openSnackBar(that, successMessage, 'success')
            } else if (status === 2) {
                typeof message === 'object'
                    ? that.openSnackBar(that, `Error: ${Object.values(message)[0][0]}`, 'error')
                    : that.openSnackBar(that, `Error: ${message}`, 'error')
                that.disabledButton = false
                that.isLoading = false
            } else if (status === 3) {
                console.log("catcing")
                that.clearAuth()
                that.$router.push('/')
                return false
            }
        },
        handleException({ error, that }) {
            const message = typeof error === 'object' && error.response ? error.response.data.message : `${error} catched by Frontend !`
            that.disabledButton = false
            that.isLoading = false
            that.openSnackBar(that, message, 'error')
            that.loadingData = false
        },
        cleanForm(that) {
            that.$refs.form.reset()
            that.$refs.form.resetValidation()
            that.$refs.observer.reset()
            that.model = {}
            that.disabledButton = false
            that.isLoading = false
        },
        toggleFullScreen() {
            const doc = window.document
            const docEl = doc.documentElement

            const requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen
            const cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen

            if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
                requestFullScreen.call(docEl)
            } else {
                cancelFullScreen.call(doc)
            }
        },
        paginationDataTable(that, api_url, options) {
            const { page, itemsPerPage, sortBy, sortDesc } = options
            let query = `page=${page}&paginate=${itemsPerPage}`
            for (const property in that.filters) {
                if (that.filters[property]) { query += `&${property}=${that.filters[property]}` }
            }

            const url =
                api_url +
                '?' +
                query
            // +
            // '&sortBy=' +
            // sortBy +
            // '&sortDesc=' + sortDesc
            return that.getDataFromApi(url)
        },
        setPaginationData(that, meta, options) {
            options.totalItems = meta.total
            options.page = meta.current_page
            options.pageCount = meta.last_page
            that.loadingData = false
        },
    }
}
Vue.component('SnackBar', SnackBar)
Vue.component('LoadingRotate', LoadingRotate)
Vue.component('ConfirmDelete', ConfirmDelete)
Vue.component('InfoCard', InfoCard)