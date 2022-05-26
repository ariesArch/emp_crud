<template>
	<v-dialog v-model="isOpenFormDialog" persistent max-width="400">
		<v-card>
			<v-progress-linear v-show="isLoading" :indeterminate="isLoading" color="primary" />
			<v-toolbar color="primary" dark>{{ dialogTitle }}</v-toolbar>
			<validation-observer ref="observer">
				<v-form ref="form" @submit.prevent="onSaveCompany" @keyup.enter="onSaveCompany">
					<v-card-text>
						<validation-provider v-slot="{ errors }" name="Name" rules="required">
							<v-text-field v-model="model.name" :error-messages="errors" label="Name" required outlined />
						</validation-provider>
						<validation-provider v-slot="{ errors }" name="Email" rules="required|email">
							<v-text-field
								v-model="model.email"
								:error-messages="errors"
								label="Email"
								required
								outlined
							/>
						</validation-provider>
						<validation-provider v-slot="{ errors }" name="Address" rules="required">
							<v-textarea
								v-model="model.address"
								:error-messages="errors"
								label="Address"
								required
								outlined
							/>
						</validation-provider>
					</v-card-text>
					<v-card-actions class="justify-end">
						<v-btn color="primary" outlined @click="closeForm(this)">Cancel</v-btn>
						<v-btn :disabled="disabledButton" color="primary" type="submit" :loading="isLoading">Save</v-btn>
					</v-card-actions>
				</v-form>
			</validation-observer>
		</v-card>
	</v-dialog>
</template>
<script>
	export default {
		data: () => {
			return {
				model: {
					id: "",
					name: "",
					email: "",
					address: "",
				},
				isOpenFormDialog: false,
				dialogTitle: "",
				disabledButton: false,
				isLoading: false,
			};
		},
		mounted() {
			this.$parent.$on("newForm", () => {
				this.dialogTitle = "New Company";
				this.isOpenFormDialog = true;
			});
			this.$parent.$on("editForm", (item) => {
				this.dialogTitle = "Edit Company";
				this.model = item;
				this.isOpenFormDialog = true;
			});
		},
		methods: {
			async onSaveCompany() {
				this.disabledButton = true;
				const isErrorFree = await this.$refs.observer.validate();
				if (!isErrorFree) {
					this.disabledButton = false;
					return false;
				}
				this.isLoading = true;
				let successMessage = "";
				if (this.dialogTitle === "New Company") {
					try {
						const create_payload = this.$model.CREATE_COMPANY_PAYLOAD(
							this.model
						);
						const { message, status, data } =
							await this.$request.postData(
								this.$api.COMPANY_URL,
								create_payload,
								this.$parent.jwt
							);
						if (status === 1) {
							this.$emit("onCreate", data);
							successMessage = `Success: New Company "${data.name}" was Created!.`;
							this.closeFormDialog(this);
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
				} else {
					try {
						const update_payload = this.$model.CREATE_COMPANY_PAYLOAD(
							this.model
						);
						const { message, status, data } =
							await this.$request.putData(
								`${this.$api.COMPANY_URL}/${this.model.id}`,
								update_payload,
								this.$parent.jwt
							);
						if (status === 1) {
							this.$emit("onUpdate", data);
							successMessage = `Success: Company "${this.model.name}"  was Updated!.`;
							this.closeFormDialog(this);
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
				}
			},
			closeForm() {
				return this.closeFormDialog(this);
			},
		},
	};
</script>
