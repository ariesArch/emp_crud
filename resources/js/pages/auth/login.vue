<template>
	<v-row align="center" justify="center">
		<v-col cols="12" align-self="center" justifyContent="center" v-show="loadingData">
			<LoadingRotate v-show="!showForm" :loading-time="loadingTime" />
		</v-col>
		<v-col>
			<validation-observer ref="observer" v-show="showForm">
				<v-form ref="form" @submit.prevent="Login">
					<v-card alignContent="center" hover class="mx-auto" max-width="40%" v-if="!loadingData">
						<v-toolbar color="primary" dark>Login</v-toolbar>
						<v-card-text>
							<validation-provider v-slot="{errors}" rules="required" name="staffId">
								<v-text-field
									v-model="model.staff_id"
									required
									:error-messages="errors"
									label="staffId"
									type="number"
								></v-text-field>
							</validation-provider>
							<validation-provider v-slot="{errors}" rules="required" name="password">
								<v-text-field
									v-model="model.password"
									name="password"
									required
									:error-messages="errors"
									label="Password"
									:type="isShowPassword?'text':'password'"
									:append-icon="isShowPassword? 'visibility':'visibility_off'"
									@click:append="()=>isShowPassword=!isShowPassword"
								></v-text-field>
							</validation-provider>
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn type="submit" color="primary" :disabled="disabledButton">Login</v-btn>
						</v-card-actions>
					</v-card>
				</v-form>
			</validation-observer>
		</v-col>
		<SnackBar />
	</v-row>
</template>
<script>
	import { mapActions } from "vuex";
	import localforage from "localforage";
	export default {
		data: () => ({
			model: {
				staff_id: "",
				password: "",
			},
			showForm: true,
			isShowPassword: false,
			disabledButton: false,
			isLoading: false,
			loadingData: false,
			loadingTime: 0,
		}),
		watch: {
			loadingData(newVal) {
				if (newVal === true) {
					setInterval(() => {
						if (this.loadingTime < 90) {
							this.loadingTime += 10;
						} else {
							this.loadingTime = 100;
						}
					}, 100);
				}
			},
		},
		methods: {
			...mapActions({
				setJwt: "auth/setJwt",
				setUser: "auth/setUser",
				setIsLoggedIn: "auth/setIsLoggedIn",
			}),
			async Login() {
				try {
					let successMessage = "";
					const isErrorFree = await this.$refs.observer.validate();
					if (!isErrorFree) {
						return false;
					}
					this.disabledButton = true;
					this.isLoading = true;
					const { status, message, access_token, user } =
						await this.$request.Login(this.$api.LOGIN_URL, this.model);
					if (status === 1) {
						this.setJwt(access_token);
						this.setUser(user);
						this.setIsLoggedIn(true);
						this.showForm = false;
						localforage.setItem("is_login", true);
						localforage.setItem("jwt", access_token);
						localforage.setItem("user", user);
						this.loadingData = true;
						successMessage = "Welcome to CodeTest Dashboard!";
						setTimeout(() => {
							this.loadingData = false;
							this.$router.push({ name: "dashboard" });
						}, 2000);
					}

					this.handleStatus({
						status,
						message,
						that: this,
						successMessage,
					});
				} catch (error) {
					console.log("error" + error.message);
					this.handleException({ error, that: this });
				}
				this.disabledButton = false;
				this.isLoading = false;
			},
		},
	};
</script>
