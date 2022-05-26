<template>
	<v-snackbar
		v-model="showSnack"
		:elevation="24"
		top
		centered
		shaped
		:timeout="5000 * 1"
		light
		:color="snackColor"
		outlined
		:class="{'ml-0': $vuetify.breakpoint.smAndDown, 'ml-12': $vuetify.breakpoint.mdAndUp}"
	>
		{{ snackMessage }}
		<v-icon color="warning" text small right style="float:right" @click="setShowSnack(false)">close</v-icon>
	</v-snackbar>
</template>
<script>
	import { mapGetters, mapMutations } from "vuex";
	export default {
		computed: {
			...mapGetters({
				snackMessage: "util/snackMessage",
				snackColor: "util/snackColor",
			}),
			showSnack: {
				get() {
					return this.$store.state.util.showSnack;
				},
				set(val) {
					this.$store.commit("util/setShowSnack", val);
				},
			},
		},
		methods: {
			...mapMutations({
				setShowSnack: "util/setShowSnack",
			}),
		},
	};
</script>
<style lang="sass">
$snackbar-background-color: red !default
</style>
