<template>
	<v-stepper-content class="pa-4 section_container" step="2">
		<v-row>
			<v-col md="6">
				<InfoCard :model="model" type="Company" />
			</v-col>
			<v-col md="6">
				<validation-observer ref="observer">
					<v-form ref="form" @submit.prevent="onSaveCompany">
						<v-card id="no_print">
							<v-progress-linear v-show="isLoading" :indeterminate="isLoading" color="primary" />
							<v-card-title class="py-2">
								<span class="primary--text mx-0 text-h5">{{ screenName }}</span>
								<v-spacer />
								<v-btn outlined color="primary" class="mx-2" @click="closeForm()">
									<v-icon dark>mdi-arrow-left-bold-box</v-icon>Back
								</v-btn>
								<v-btn
									:disabled="disabledButton"
									color="primary"
									type="submit"
									:loading="isLoading"
								>{{model.id?'Update':'Save'}}</v-btn>
							</v-card-title>
							<v-divider />
							<v-card-text>
								<validation-provider v-slot="{ errors }" name="Name" rules="required">
									<v-text-field
										v-model="model.name"
										:error-messages="errors"
										label="Name ***"
										required
										outlined
									/>
								</validation-provider>
								<validation-provider v-slot="{ errors }" name="Email" rules="email">
									<v-text-field
										v-model="model.email"
										:error-messages="errors"
										label="Email"
										required
										outlined
									/>
								</validation-provider>
								<v-textarea v-model="model.address" label="Address" outlined />
							</v-card-text>
						</v-card>
					</v-form>
				</validation-observer>
			</v-col>
		</v-row>
	</v-stepper-content>
</template>
<script>
	import { mapGetters } from "vuex";
	export default {
		props: {
			value: {
				type: Object,
				default: () => {},
			},
		},
		data: () => ({
			screenName: "",
			isLoading: false,
			disabledButton: false,
		}),
		computed: {
			model: {
				get() {
					return this.value;
				},
				set(value) {
					return this.$emit("input", value);
				},
			},
			...mapGetters({
				jwt: "auth/jwt",
			}),
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
				if (!this.model.id) {
					try {
						const create_payload = this.$model.CREATE_COMPANY_PAYLOAD(
							this.model
						);
						const { message, status, data } =
							await this.$request.postData(
								this.$api.COMPANY_URL,
								create_payload,
								this.jwt
							);
						if (status === 1) {
							successMessage = `Success: New Company "${data.name}" was Created!.`;
							this.closeForm(data, "created");
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
								this.jwt
							);
						if (status === 1) {
							successMessage = `Success: Company "${this.model.name}"  was Updated!.`;
							this.closeForm(data, "updated");
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
			closeForm(data = null, action = null) {
				this.$emit("onGoBack", data, action);
				this.cleanForm(this);
			},
		},
	};
</script>