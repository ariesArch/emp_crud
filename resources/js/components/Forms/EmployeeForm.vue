<template>
	<v-stepper-content class="pa-4 section_container" step="2">
		<v-row>
			<v-col md="5">
				<InfoCard :model="model" type="Employee" />
			</v-col>
			<v-col md="7">
				<validation-observer ref="observer">
					<v-form ref="form" @submit.prevent="onSaveEmployee">
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
								<div class="d-flex grow">
									<validation-provider v-slot="{ errors }" name="First Name" rules="required">
										<v-text-field
											v-model="model.first_name"
											:error-messages="errors"
											label="First Name ***"
											required
											outlined
										/>
									</validation-provider>
									<validation-provider v-slot="{ errors }" name="Last Name" rules="required">
										<v-text-field
											v-model="model.last_name"
											:error-messages="errors"
											label="Last Name ***"
											required
											outlined
											class="mx-4"
										/>
									</validation-provider>
									<validation-provider v-slot="{ errors }" name="Phone" rules="phone|max:11">
										<v-text-field
											v-model="model.phone"
											label="Phone"
											:error-messages="errors"
											required
											outlined
										/>
									</validation-provider>
								</div>
								<validation-provider v-slot="{ errors }" name="Email" rules="email">
									<v-text-field
										v-model="model.email"
										:error-messages="errors"
										label="Email"
										required
										outlined
									/>
								</validation-provider>
								<validation-provider v-slot="{ errors }" name="Company" rules="required">
									<v-autocomplete
										v-model="model.company"
										label="Company ***"
										:items="companies"
										item-text="name"
										return-object
										required
										:error-messages="errors"
										outlined
									/>
								</validation-provider>
								<validation-provider v-slot="{ errors }" name="Department" rules="required">
									<v-autocomplete
										v-model="model.department"
										label="Department ***"
										:items="departments"
										item-text="name"
										return-object
										required
										:error-messages="errors"
										outlined
									/>
								</validation-provider>
								<validation-provider v-slot="{ errors }" name="Role" rules="required">
									<v-autocomplete
										v-model="model.role"
										label="Role ***"
										:items="roles"
										item-text="name"
										return-object
										required
										:error-messages="errors"
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
			companies: {
				type: Array,
				default: () => [],
			},
			departments: {
				type: Array,
				default: () => [],
			},
			roles: {
				type: Array,
				default: () => [],
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
			async onSaveEmployee() {
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
						const create_payload = this.$model.CREATE_EMPLOYEE_PAYLOAD(
							this.model
						);
						console.log(create_payload);
						const { message, status, data } =
							await this.$request.postData(
								this.$api.EMPLOYEE_URL,
								create_payload,
								this.jwt
							);
						if (status === 1) {
							successMessage = `Success: New Employee "${data.name}" was Created!.`;
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
						const update_payload = this.$model.CREATE_EMPLOYEE_PAYLOAD(
							this.model
						);
						const { message, status, data } =
							await this.$request.putData(
								`${this.$api.EMPLOYEE_URL}/${this.model.id}`,
								update_payload,
								this.jwt
							);
						if (status === 1) {
							successMessage = `Success: Employee "${this.model.name}"  was Updated!.`;
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