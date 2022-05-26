<template>
	<v-card elevation="3">
		<v-card-title class="pa-2 primary">
			<h3 class="text-h6 font-weight-light text-center grow white--text">{{ type }} Information</h3>
		</v-card-title>
		<v-card-text>
			<div class="d-flex d-flex-row justify-center pt-4" v-if="model.first_name||model.last_name">
				<v-icon class="mr-4">mdi-badge-account-horizontal</v-icon>
				<h3 v-if="model.first_name||model.last_name">{{model.first_name }} {{model.last_name}}</h3>
			</div>
			<v-list v-show="Object.keys(this.model).length" color="transparent" flat>
				<v-list-item v-for="(item,key) in infoData" :key="key">
					<v-list-item-icon v-if="item">
						<v-icon>{{ key }}</v-icon>
					</v-list-item-icon>
					<v-list-item-content v-if="item">
						<v-list-item-title class="text-wrap" v-text="item" />
					</v-list-item-content>
				</v-list-item>
			</v-list>
			<img
				v-show="!Object.keys(this.model).length"
				src="@/assets/images/file_empty.svg"
				width="200"
				height="200"
			/>
			<slot />
		</v-card-text>
	</v-card>
</template>
<script>
	export default {
		name: "InfoCard",
		props: {
			model: {
				type: Object,
				default: () => {},
			},
			type: {
				type: String,
				default: "",
			},
		},
		computed: {
			fullName() {},
			infoData() {
				let info = {};
				if (!Object.keys(this.model).length) {
					return info;
				}
				if (this.type === "Company") {
					info = {
						"mdi-domain": this.model.name,
						"mdi-email": this.model.email,
						"mdi-city": this.model.address,
					};
				} else if (this.type === "Department") {
					info = { "mdi-domain": this.model.name };
				} else {
					info = {
						"mdi-phone": this.model.phone,
						"mdi-email": this.model.email,
						"mdi-bank":
							"company" in this.model ? this.model.company.name : "",
						dashboard:
							"department" in this.model
								? this.model.department.name
								: "",
						"mdi-city": this.model.address,
						lock: "role" in this.model ? this.model.role.name : "",
					};
				}
				return info;
			},
		},
	};
</script>
