<template>
	<v-app-bar app flat dense>
		<h4 class="primary--text">{{user?user.role.name:''}}</h4>

		<v-spacer />
		<v-btn icon @click="handleFullScreen()">
			<v-icon>fullscreen</v-icon>
		</v-btn>
		<h4 class="primary--text">{{user?user.name:''}}</h4>

		<v-menu
			offset-y
			origin="center center"
			:nudge-right="120"
			:nudge-bottom="10"
			transition="scale-transition"
		>
			<template #activator="{ on, attrs }">
				<v-btn v-bind="attrs" icon large text v-on="on">
					<v-avatar size="30px">
						<img src="@/assets/images/profile.png" alt="Michael Wang" />
					</v-avatar>
				</v-btn>
			</template>
			<v-list class="pa-0">
				<v-list-item
					v-for="(item,index) in items"
					:key="index"
					:to="!item.href ? { name: item.name } : null"
					:href="item.href"
					ripple="ripple"
					:disabled="item.disabled"
					:target="item.target"
					rel="noopener"
					@click="item.click"
				>
					<v-list-item-action v-if="item.icon">
						<v-icon>{{ item.icon }}</v-icon>
					</v-list-item-action>
					<v-list-item-content>
						<v-list-item-title>{{ item.title }}</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</v-list>
		</v-menu>
	</v-app-bar>
</template>
<script>
	import { mapGetters, mapActions } from "vuex";
	export default {
		name: "AppToolbar",
		// data() {
		// return {
		// 	items: [
		// 		{
		// 			icon: "account_circle",
		// 			href: "#",
		// 			title: this.user ? this.user.role.name : "",
		// 			click: (e) => {
		// 				console.log(e);
		// 			},
		// 		},
		// 		{
		// 			icon: "fullscreen_exit",
		// 			href: "#",
		// 			title: "Logout",
		// 			click: this.handleLogout,
		// 		},
		// 	],
		// };
		// },
		computed: {
			...mapGetters({
				jwt: "auth/jwt",
				user: "auth/user",
			}),
			items() {
				const items = [
					{
						icon: "lock",
						href: "#",
						title: this.user ? this.user.role.name : "",
						click: (e) => {
							console.log(e);
						},
					},
					{
						icon: "settings",
						href: "/",
						title: "Change Password",
						click: (e) => {
							console.log(e);
						},
					},
					{
						icon: "fullscreen_exit",
						href: "#",
						title: "Logout",
						click: this.handleLogout,
					},
				];
				return items;
			},
		},
		methods: {
			...mapActions({
				clearAuth: "auth/clearAuth",
			}),
			handleFullScreen() {
				this.toggleFullScreen();
			},
			async handleLogout() {
				let successMessage = "";
				try {
					const { message, status } = await this.$request.Logout(
						this.$api.LOGOUT_URL,
						this.jwt
					);
					if (status === 1) {
						// this.$vuetify.theme.themes.light.primary = this.colors.orange.darken2
						this.clearAuth();
						this.$router.push("/login");
						successMessage = "Successfully logout";
					}
					this.handleStatus({
						status,
						message,
						that: this,
						successMessage,
					});
				} catch (e) {
					console.log(e);
				}
			},
			// this.$router.push('/login')
		},
	};
</script>
