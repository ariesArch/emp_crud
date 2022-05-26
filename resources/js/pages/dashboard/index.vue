<template>
	<v-card max-width="400" class="mx-auto">
		<v-card-title>Change Password</v-card-title>
		<v-card-text>
			<validation-observer ref="observer">
				<v-form ref="form" @submit.prevent="onSavePassword" @keyup.enter="onSavePassword">
					<validation-provider v-slot="{ errors }" name="Old Password" :rules="{required:true}">
						<v-text-field
							v-model="model.old_password"
							:append-icon="
                          isPasswordHidden ? 'visibility' : 'visibility_off'
                        "
							:type="isPasswordHidden ? 'password' : 'text'"
							label="Old Password *"
							name="password"
							required
							:error-messages="errors"
							@click:append="() => (isPasswordHidden = !isPasswordHidden)"
						/>
					</validation-provider>
					<validation-provider
						v-slot="{ errors }"
						name="New Password"
						:rules="{required:true}"
						vid="new_password"
					>
						<v-text-field
							v-model="model.new_password"
							:append-icon="
                          isPasswordHidden ? 'visibility' : 'visibility_off'
                        "
							:type="isPasswordHidden ? 'password' : 'text'"
							label="New Password *"
							name="password"
							required
							:error-messages="errors"
							@click:append="() => (isPasswordHidden = !isPasswordHidden)"
						/>
					</validation-provider>
					<validation-provider
						v-slot="{ errors }"
						name="Confirmed New Password"
						rules="required|confirmed:new_password"
					>
						<v-text-field
							v-model="model.new_password_confirmation"
							:append-icon="
                          isConfirmPasswordHidden ? 'visibility' : 'visibility_off'
                        "
							:type="isConfirmPasswordHidden ? 'password' : 'text'"
							label="Confirmed New Password *"
							name="new_password_confirmation"
							:error-messages="errors"
							required
							confirmed
							@click:append="() => (isConfirmPasswordHidden = !isConfirmPasswordHidden)"
						/>
					</validation-provider>
					<v-btn type="submit" color="primary">Update</v-btn>
				</v-form>
			</validation-observer>
		</v-card-text>
		<SnackBar />
	</v-card>
</template>
<script>
	import { mapGetters } from "vuex";
	export default {
		data: () => ({
			isPasswordHidden: true,
			isConfirmPasswordHidden: true,
			model: {
				old_password: "",
				new_password: "",
				new_password_confirmation: "",
			},
			disabledButton: false,
			isLoading: false,
		}),
		computed: {
			...mapGetters({
				jwt: "auth/jwt",
			}),
		},
		methods: {
			async onSavePassword() {
				this.disabledButton = true;
				const isErrorFree = await this.$refs.observer.validate();
				if (!isErrorFree) {
					this.disabledButton = false;
					return false;
				}
				this.isLoading = true;
				let successMessage = "";
				try {
					const { message, status, data } = await this.$request.postData(
						this.$api.UPDATE_PASSWORD_URL,
						this.model,
						this.jwt
					);
					if (status === 1) {
						successMessage = `Success: Password has been Changed!.`;
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
			},
			closeForm(data = null, action = null) {
				this.$emit("onGoBack", data, action);
				this.cleanForm(this);
			},
		},
	};
</script>