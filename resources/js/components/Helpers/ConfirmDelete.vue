<template>
	<v-row justify="center">
		<v-dialog v-model="isOpenFormDialog" max-width="400">
			<v-card>
				<!-- <v-progress-linear
          v-show="isLoading"
          :indeterminate="isLoading"
          color="primary"
				/>-->
				<v-toolbar color="primary" dark>{{ dialogTitle }}</v-toolbar>
				<v-card-title class="text-h6">
					Delete {{ screen }}
					<span class="error--text mx-1">{{ model.name?model.name:model.name_en }}</span>?
				</v-card-title>

				<v-card-text>You can contact to IT department whenever you want to restore it.</v-card-text>

				<v-card-actions>
					<v-spacer />

					<v-btn color="warning" text @click="isOpenFormDialog =false">Cancel</v-btn>

					<v-btn color="primary" text :disabled="disabledButton" @click="onDeleteItem()">Confirm</v-btn>
				</v-card-actions>
			</v-card>
		</v-dialog>
	</v-row>
</template>
<script>
	export default {
		props: {
			screen: {
				type: String,
				default: "",
			},
		},
		data: () => ({
			model: {
				id: "",
				name: "",
			},
			deleteUrl: "",
			jwt: "",
			isOpenFormDialog: false,
			dialogTitle: "Delete Confirmation",
			disabledButton: false,
			isLoading: false,
		}),
		mounted() {
			this.$parent.$on("deleteForm", (item, url, jwt) => {
				this.model = item;
				this.deleteUrl = url;
				this.jwt = jwt;
				this.isOpenFormDialog = true;
			});
		},
		methods: {
			async onDeleteItem() {
				this.disabledButton = true;
				this.isLoading = true;
				let successMessage = "";
				try {
					const { message, status } = await this.$request.deleteData(
						`${this.deleteUrl}/${this.model.id}`,
						this.jwt
					);
					if (status === 1) {
						this.$parent.$emit("deletedData", this.model);
						successMessage = `Success: ${this.screen} "${this.model.name}" have been deleted!.`;
						this.isOpenFormDialog = false;
					}
					this.handleStatus({
						status,
						message,
						that: this,
						successMessage,
					});
				} catch (error) {
					this.handleException({ error, that: this });
				}
				this.disabledButton = false;
			},
			closeForm() {
				this.isOpenFormDialog = false;
			},
		},
	};
</script>
