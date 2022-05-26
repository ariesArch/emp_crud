<template>
	<v-navigation-drawer expand-on-hover v-model="drawer" app fixed :mini-variant.sync="mini">
		<v-toolbar color="primary darken-1" dark>
			<img src="@/assets/logo/tamamall.jpeg" height="36" alt="MKitchen" />
			<v-toolbar-title class="ml-0 pl-3">
				<span class="hidden-sm-and-down">Code Test</span>
			</v-toolbar-title>
		</v-toolbar>
		<vue-perfect-scrollbar class="drawer-menu--scroll" :settings="scrollSettings">
			<v-list dense expand>
				<template v-for="(item, i) in menus">
					<!--group with subitems-->
					<v-list-group
						v-if="item.items"
						:key="item.name"
						:group="item.group"
						:prepend-icon="item.icon"
						no-action="no-action"
					>
						<v-list-item slot="activator" ripple="ripple">
							<v-list-item-content>
								<v-list-item-title class="tw-uppercase">{{ item.title }}</v-list-item-title>
							</v-list-item-content>
						</v-list-item>
						<!--child item-->
						<v-list-item
							v-for="(subItem, i) in item.items"
							:key="i"
							ripple="ripple"
							:to="subItem.href ? subItem.href : null"
						>
							<v-list-item-action v-if="subItem.icon">
								<v-icon>{{ subItem.icon }}</v-icon>
							</v-list-item-action>
							<v-list-item-content>
								<v-list-item-title class="tw-uppercase">
									<span>{{ subItem.title }}</span>
								</v-list-item-title>
							</v-list-item-content>
							<v-list-item-action v-if="subItem.action">
								<v-icon :class="[subItem.actionClass || 'success--text']">{{ subItem.action }}</v-icon>
							</v-list-item-action>
						</v-list-item>
					</v-list-group>
					<v-subheader v-else-if="item.header" :key="i">{{ item.header }}</v-subheader>
					<v-divider v-else-if="item.divider" :key="i" />
					<!--top-level link-->
					<v-list-item
						v-else
						:key="item.name"
						:to="item.href ? item.href : null"
						ripple="ripple"
						:disabled="item.disabled"
						rel="noopener"
						active-class="primary--text"
					>
						<v-list-item-action v-if="item.icon">
							<v-icon>{{ item.icon }}</v-icon>
						</v-list-item-action>
						<v-list-item-content>
							<v-list-item-title class="tw-uppercase">{{ item.title }}</v-list-item-title>
						</v-list-item-content>
						<v-list-item-action v-if="item.subAction">
							<v-icon class="success--text">{{ item.subAction }}</v-icon>
						</v-list-item-action>
					</v-list-item>
				</template>
			</v-list>
		</vue-perfect-scrollbar>
	</v-navigation-drawer>
</template>
<script>
	import menu from "@/assets/util/menu";
	import VuePerfectScrollbar from "vue-perfect-scrollbar";
	import { mapGetters } from "vuex";
	export default {
		props: {
			drawer: {
				type: Boolean,
				default: true,
			},
		},
		components: {
			VuePerfectScrollbar,
		},
		computed: {
			...mapGetters({ user: "auth/user" }),
			// hasRoles() {
			// 	return (menu_roles) =>
			// 		this.user.roles.find((role) =>
			// 			menu_roles.find((menu) => menu === role.name)
			// 		);
			// },
		},
		data: () => ({
			mini: true,
			menus: menu,
			scrollSettings: {
				maxScrollbarLength: 160,
			},
		}),
		methods: {
			handleRedirect(url) {
				this.$router.push(url).catch((error) => {
					console.log(error);
				});
			},
		},
	};
</script>