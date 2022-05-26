<template>
	<v-app id="inspire">
		<AppDrawer :drawer="drawer" />
		<AppToolbar />
		<v-main>
			<v-container fluid class="page-wrappe">
				<router-view></router-view>
			</v-container>
			<v-footer app class="app--footer mt-2">
				<span>© {{ new Date().getFullYear() }}</span>
			</v-footer>
		</v-main>
	</v-app>
</template>

<script>
	import { mapState } from "vuex";
	import AppDrawer from "@/components/BaseUI/AppDrawer.vue";
	import AppToolbar from "@/components/BaseUI/AppToolbar.vue";
	export default {
		props: {
			source: String,
		},
		// data: () => ({
		// 	drawer: true,
		// }),
		components: {
			AppDrawer,
			AppToolbar,
		},
		computed: mapState("util", ["drawer"]),
		methods: {
			toggleDrawer() {
				try {
					this.$store.commit("util/TOGGLE_DRAWER", !this.drawer);
				} catch (error) {
					console.log(error);
				}
			},
		},
	};
</script>
<style>
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
html, body {margin: 0; height: 100%}
  .page-wrapper {
    min-height: calc(95.3vh - 64px);
  }
  .app--footer {
    position: absolute;
    bottom: 0;
    width: 100%;
	height: 25px;
	background-color: white !important;
  }
  .section_container {
    /* min-height: calc(104vh - 70px - 59px - 87px); */
    /* max-height: calc(104vh - 70px - 59px - 87px); */
    min-height: calc(114.2vh - 70px - 59px - 87px);
    /* max-height: calc(95.3vh - 70px - 59px - 87px); */
    overflow-y: auto;
}
</style>